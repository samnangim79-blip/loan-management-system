<?php

namespace App\Http\Controllers;

use App\Models\CashMgt;
use App\Models\Currency;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\PenddingCashTransfer;
use Illuminate\Support\Facades\Auth;

class CashController extends Controller
{
    public function index()
    {
        $currencies = Currency::all();
        return view('cash.index', compact('currencies'));
    }

    public function getData(Request $request)
    {
        $cash = CashMgt::with('currency')->select('cash_mgts.*');

        // Apply filters
        if ($request->filled('date_from')) {
            $cash->whereDate('tran_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $cash->whereDate('tran_date', '<=', $request->date_to);
        }

        if ($request->filled('type')) {
            $cash->where('in_out', $request->type);
        }

        if ($request->filled('currency')) {
            $cash->where('ccy_id', $request->currency);
        }

        return DataTables::of($cash)
            ->addColumn('currency_code', function ($row) {
                return $row->currency->currency ?? 'N/A';
            })
            ->addColumn('in_out_badge', function ($row) {
                return $row->in_out == 'i'
                    ? '<span class="badge bg-success">In</span>'
                    : '<span class="badge bg-danger">Out</span>';
            })
            ->addColumn('formatted_amount', function ($row) {
                return number_format($row->amount ?? 0, 2);
            })
            ->addColumn('formatted_balance', function ($row) {
                return number_format($row->balance ?? 0, 2);
            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="btn-group" role="group">';
                $btn .= '<button type="button" class="btn btn-sm btn-info view-btn" data-id="' . $row->cash_mgt_id . '"><i class="fas fa-eye"></i></button>';
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['in_out_badge', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tran_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'in_out' => 'required|in:i,o',
            'ccy_id' => 'required|exists:currencys,CCY_ID',
            'remark' => 'nullable|string|max:50'
        ]);

        // Get current balance
        $lastRecord = CashMgt::where('ccy_id', $validated['ccy_id'])
            ->orderBy('cash_mgt_id', 'desc')
            ->first();

        $currentBalance = $lastRecord ? $lastRecord->balance : 0;

        // Calculate new balance
        $validated['balance'] = $validated['in_out'] == 'i'
            ? $currentBalance + $validated['amount']
            : $currentBalance - $validated['amount'];

        $validated['user_id'] = Auth::id() ?? 1;
        $validated['date_done'] = now();

        $cash = CashMgt::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Cash transaction recorded successfully',
            'data' => $cash
        ]);
    }

    public function getCurrentBalance($ccyId)
    {
        $lastRecord = CashMgt::where('ccy_id', $ccyId)
            ->orderBy('cash_mgt_id', 'desc')
            ->first();

        return response()->json([
            'balance' => $lastRecord ? $lastRecord->balance : 0
        ]);
    }

    // Pending Cash Transfers
    public function transfersIndex()
    {
        $currencies = Currency::all();
        return view('cash.transfers', compact('currencies'));
    }

    public function getTransfersData()
    {
        $transfers = PenddingCashTransfer::with(['currency', 'user'])
            ->select('pendding_cash_transfers.*');

        return DataTables::of($transfers)
            ->addColumn('currency_code', function ($row) {
                return $row->currency->currency ?? 'N/A';
            })
            ->addColumn('status_badge', function ($row) {
                return $row->status_id == 0
                    ? '<span class="badge bg-warning">Pending</span>'
                    : '<span class="badge bg-success">Received</span>';
            })
            ->addColumn('in_out_badge', function ($row) {
                return $row->in_ou == 'i'
                    ? '<span class="badge bg-info">In</span>'
                    : '<span class="badge bg-secondary">Out</span>';
            })
            ->addColumn('formatted_amount', function ($row) {
                return number_format($row->amount ?? 0, 2);
            })
            ->addColumn('action', function ($row) {
                $btn = '<div class="btn-group" role="group">';
                $btn .= '<button type="button" class="btn btn-sm btn-info view-btn" data-id="' . $row->pendding_cash_transfer_id . '"><i class="fas fa-eye"></i></button>';
                if ($row->status_id == 0) {
                    $btn .= '<button type="button" class="btn btn-sm btn-success receive-btn" data-id="' . $row->pendding_cash_transfer_id . '"><i class="fas fa-check"></i></button>';
                }
                $btn .= '</div>';
                return $btn;
            })
            ->rawColumns(['status_badge', 'in_out_badge', 'action'])
            ->make(true);
    }

    public function storeTransfer(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'in_ou' => 'required|in:i,o',
            'ccy_id' => 'required|exists:currencys,CCY_ID',
            'remark' => 'nullable|string'
        ]);

        $validated['pendding_cash_transfer_id'] = time();
        $validated['user_id'] = Auth::id() ?? 1;
        $validated['sent_date'] = now();
        $validated['status_id'] = 0; // Pending

        $transfer = PenddingCashTransfer::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Cash transfer created successfully',
            'data' => $transfer
        ]);
    }

    public function receiveTransfer($id)
    {
        $transfer = PenddingCashTransfer::findOrFail($id);

        $transfer->update([
            'status_id' => 1 // Received
        ]);

        // Record in cash management
        $lastRecord = CashMgt::where('ccy_id', $transfer->ccy_id)
            ->orderBy('cash_mgt_id', 'desc')
            ->first();

        $currentBalance = $lastRecord ? $lastRecord->balance : 0;
        $newBalance = $transfer->in_ou == 'i'
            ? $currentBalance + $transfer->amount
            : $currentBalance - $transfer->amount;

        CashMgt::create([
            'tran_date' => now()->toDateString(),
            'amount' => $transfer->amount,
            'in_out' => $transfer->in_ou,
            'balance' => $newBalance,
            'ccy_id' => $transfer->ccy_id,
            'user_id' => Auth::id() ?? 1,
            'date_done' => now(),
            'remark' => 'Cash transfer received'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cash transfer received successfully'
        ]);
    }

    public function show($id)
    {
        $cash = CashMgt::with('currency')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $cash
        ]);
    }

    public function getBalances()
    {
        $balances = [];
        $currencies = Currency::all();

        foreach ($currencies as $currency) {
            $lastRecord = CashMgt::where('ccy_id', $currency->ccy_id)
                ->orderBy('cash_mgt_id', 'desc')
                ->first();

            $balances[$currency->ccy_id] = $lastRecord ? $lastRecord->balance : 0;
        }

        return response()->json([
            'success' => true,
            'data' => $balances
        ]);
    }

    public function showTransfer($id)
    {
        $transfer = PenddingCashTransfer::with(['currency', 'user'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $transfer
        ]);
    }

    public function getTransfersSummary()
    {
        $pendingCount = PenddingCashTransfer::where('status_id', 0)->count();
        $completedToday = PenddingCashTransfer::where('status_id', 1)
            ->whereDate('sent_date', today())
            ->count();
        $pendingAmount = PenddingCashTransfer::where('status_id', 0)->sum('amount');

        return response()->json([
            'success' => true,
            'data' => [
                'pending_count' => $pendingCount,
                'completed_today' => $completedToday,
                'pending_amount' => $pendingAmount
            ]
        ]);
    }
}
