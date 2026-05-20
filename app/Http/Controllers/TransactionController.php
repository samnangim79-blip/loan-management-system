<?php

namespace App\Http\Controllers;

use App\Models\GlMap;
use App\Models\Trans;
use App\Models\TranDetail;
use App\Models\CustAcctTran;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
  public function index()
  {
    $glMaps = GlMap::all();
    return view('transactions.index', compact('glMaps'));
  }

  public function getData(Request $request)
  {
    $query = Trans::with(['branch', 'currency', 'user'])
      ->select('trans.*');

    if ($request->has('date_from') && $request->date_from) {
      $query->where('tran_date', '>=', $request->date_from);
    }

    if ($request->has('date_to') && $request->date_to) {
      $query->where('tran_date', '<=', $request->date_to);
    }

    if ($request->has('tran_type') && $request->tran_type) {
      $query->where('tran_type', $request->tran_type);
    }

    return DataTables::of($query)
      ->addColumn('branch_name', function ($row) {
        return $row->branch->branch_name ?? 'N/A';
      })
      ->addColumn('currency_code', function ($row) {
        return $row->currency->currency ?? 'USD';
      })
      ->addColumn('formatted_amount', function ($row) {
        return number_format($row->amount ?? 0, 2);
      })
      ->addColumn('formatted_date', function ($row) {
        return $row->tran_date ? $row->tran_date->format('Y-m-d') : 'N/A';
      })
      ->addColumn('type_badge', function ($row) {
        // Map actual seeded transaction types
        $types = [
          1 => '<span class="badge bg-success">Deposit</span>',
          2 => '<span class="badge bg-primary">Loan Disbursement</span>',
          3 => '<span class="badge bg-warning">Withdrawal</span>',
          4 => '<span class="badge bg-secondary">Loan Payment</span>',
          5 => '<span class="badge bg-dark">Interest Payment</span>',
          6 => '<span class="badge bg-info">Fixed Deposit</span>',
          7 => '<span class="badge bg-danger">Service Fee</span>',
          8 => '<span class="badge bg-purple">Wire Transfer</span>',
          9 => '<span class="badge bg-orange">Currency Exchange</span>',
          10 => '<span class="badge bg-red">Penalty Fee</span>',
          11 => '<span class="badge bg-green">FD Maturity</span>',
          12 => '<span class="badge bg-blue">Incoming Wire</span>',
        ];
        return $types[$row->tran_type] ?? '<span class="badge bg-secondary">Type ' . $row->tran_type . '</span>';
      })
      ->addColumn('user_name', function ($row) {
        return $row->user->login_name ?? 'System';
      })
      ->addColumn('status_badge', function ($row) {
        if ($row->approved_by) {
          return '<span class="badge bg-success">Approved</span>';
        } else {
          return '<span class="badge bg-warning">Pending</span>';
        }
      })
      ->addColumn('action', function ($row) {
        $btn = '<div class="btn-group" role="group">';
        $btn .= '<button type="button" class="btn btn-sm btn-info view-btn" data-id="' . $row->tran_id . '"><i class="fas fa-eye"></i></button>';
        $btn .= '<button type="button" class="btn btn-sm btn-secondary print-btn" data-id="' . $row->tran_id . '"><i class="fas fa-print"></i></button>';
        $btn .= '</div>';
        return $btn;
      })
      ->rawColumns(['type_badge', 'status_badge', 'action'])
      ->make(true);
  }

  public function show($id)
  {
    $transaction = Trans::with(['branch', 'currency', 'glMap', 'tranDetails'])
      ->findOrFail($id);

    return response()->json($transaction);
  }

  public function print($id)
  {
    $transaction = Trans::with(['branch', 'currency', 'user', 'approver', 'tranDetails'])
      ->findOrFail($id);

    return view('transactions.print', compact('transaction'));
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'branch_id' => 'required|exists:branchs,branch_id',
      'tran_date' => 'required|date',
      'gl_map_id' => 'required|exists:gl_maps,gl_map_id',
      'amount' => 'required|numeric|min:0.01',
      'ccy_id' => 'required|exists:currencys,ccy_id',
      'discription' => 'nullable|string',
      'tran_type' => 'required|integer'
    ]);

    DB::beginTransaction();
    try {
      $validated['user_id'] = Auth::id() ?? 1;
      $validated['done_date'] = now();

      $transaction = Trans::create($validated);

      // Create GL entries
      $glMap = GlMap::find($validated['gl_map_id']);
      if ($glMap) {
        // Debit entry
        TranDetail::create([
          'tran_id' => $transaction->tran_id,
          'dr_cr' => 'd',
          'gl_id' => $glMap->debit_gl_id,
          'balance' => $validated['amount']
        ]);

        // Credit entry
        TranDetail::create([
          'tran_id' => $transaction->tran_id,
          'dr_cr' => 'c',
          'gl_id' => $glMap->credit_gl_id,
          'balance' => $validated['amount']
        ]);
      }

      DB::commit();

      return response()->json([
        'success' => true,
        'message' => 'Transaction created successfully',
        'data' => $transaction
      ]);
    } catch (\Exception $e) {
      DB::rollback();
      return response()->json([
        'success' => false,
        'message' => 'Error creating transaction: ' . $e->getMessage()
      ], 500);
    }
  }

  public function getDetails($id)
  {
    $details = TranDetail::with('gl')
      ->where('tran_id', $id)
      ->get();

    return response()->json($details);
  }

  public function getAccountTransactions($accountId)
  {
    $transactions = CustAcctTran::with('transaction')
      ->where('acc_id', $accountId)
      ->orderBy('cust_tran_id', 'desc')
      ->limit(50)
      ->get();

    return response()->json($transactions);
  }

  public function getDailySummary(Request $request)
  {
    $date = $request->get('date', now()->toDateString());

    $summary = Trans::where('tran_date', $date)
      ->selectRaw('TRAN_TYPE, COUNT(*) as count, SUM(AMOUNT) as total')
      ->groupBy('tran_type')
      ->get();

    return response()->json($summary);
  }

  public function summary(Request $request)
  {
    $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
    $dateTo = $request->get('date_to', now()->toDateString());

    // Get summary statistics for the date range based on actual seeded transaction types
    $deposits = Trans::whereBetween('tran_date', [$dateFrom, $dateTo])
        ->where('tran_type', 1) // Deposits in our seeder
        ->sum('amount');

    $withdrawals = Trans::whereBetween('tran_date', [$dateFrom, $dateTo])
        ->where('tran_type', 3) // Withdrawals in our seeder
        ->sum('amount');

    $disbursements = Trans::whereBetween('tran_date', [$dateFrom, $dateTo])
        ->where('tran_type', 2) // Loan disbursements in our seeder
        ->sum('amount');

    $payments = Trans::whereBetween('tran_date', [$dateFrom, $dateTo])
        ->where('tran_type', 4) // Loan payments in our seeder
        ->sum('amount');

    $totalTransactions = Trans::whereBetween('tran_date', [$dateFrom, $dateTo])->count();

    return response()->json([
        'success' => true,
        'data' => [
            'deposits' => $deposits,
        ],
        'totalWithdrawals' => $withdrawals,
        'totalDisbursed' => $disbursements,
        'totalPayments' => $payments,
        'totalTransactions' => $totalTransactions,
        'dateFrom' => $dateFrom,
        'dateTo' => $dateTo
    ]);
  }

  public function search(Request $request)
  {
    $query = $request->get('q', '');

    $transactions = Trans::where('tran_id', 'like', "%{$query}%")
      ->orWhere('description', 'like', "%{$query}%")
      ->limit(20)
      ->get(['tran_id', 'amount', 'tran_date', 'description']);

    return response()->json($transactions);
  }
}
