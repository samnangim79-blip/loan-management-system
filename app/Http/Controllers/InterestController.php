<?php

namespace App\Http\Controllers;

use App\Models\AccruedInt;
use App\Models\IntRate;
use App\Models\LoanSchedule;
use App\Models\AccountInfo;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class InterestController extends Controller
{
  // Interest Rates
  public function ratesIndex()
  {
    return view('interest.rates');
  }

  public function getRatesData()
  {
    $rates = IntRate::select('int_rates.*');

    return DataTables::of($rates)
      ->addColumn('formatted_rate', function ($row) {
        return number_format($row->rate, 2) . '%';
      })
      ->addColumn('acct_type_text', function ($row) {
        return $row->accountType->acct_type ?? 'Unknown';
      })
      ->addColumn('action', function ($row) {
        $btn = '<div class="btn-group" role="group">';
        $btn .= '<button type="button" class="btn btn-sm btn-primary edit-btn" data-id="' . $row->int_rate_id . '"><i class="fas fa-edit"></i></button>';
        $btn .= '<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->int_rate_id . '"><i class="fas fa-trash"></i></button>';
        $btn .= '</div>';
        return $btn;
      })
      ->rawColumns(['action'])
      ->make(true);
  }

  public function storeRate(Request $request)
  {
    $validated = $request->validate([
      'rate' => 'required|numeric|min:0|max:100',
      'acct_type_id' => 'required|exists:account_types,acct_type_id'
    ]);

    $rate = IntRate::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'Interest rate created successfully',
      'data' => $rate
    ]);
  }

  public function showRate($id)
  {
    $rate = IntRate::findOrFail($id);
    return response()->json($rate);
  }

  public function updateRate(Request $request, $id)
  {
    $rate = IntRate::findOrFail($id);

    $validated = $request->validate([
      'rate' => 'required|numeric|min:0|max:100',
      'acct_type_id' => 'required|exists:account_types,acct_type_id'
    ]);

    $rate->update($validated);

    return response()->json([
      'success' => true,
      'message' => 'Interest rate updated successfully',
      'data' => $rate
    ]);
  }

  public function destroyRate($id)
  {
    $rate = IntRate::findOrFail($id);
    $rate->delete();

    return response()->json([
      'success' => true,
      'message' => 'Interest rate deleted successfully'
    ]);
  }

  public function allRates()
  {
    $rates = IntRate::all();
    return response()->json($rates);
  }

  // Accrued Interest
  public function accruedIndex()
  {
    return view('interest.accrued');
  }

  public function getAccruedData(Request $request)
  {
    $query = AccruedInt::select('accrued_ints.*');

    if ($request->has('date_from') && $request->date_from) {
      $query->where('last_accrued_date', '>=', $request->date_from);
    }

    if ($request->has('date_to') && $request->date_to) {
      $query->where('last_accrued_date', '<=', $request->date_to);
    }

    return DataTables::of($query)
      ->addColumn('action', function ($row) {
        $btn = '<div class="btn-group" role="group">';
        $btn .= '<button type="button" class="btn btn-sm btn-info view-btn" data-id="' . $row->acct_id . '"><i class="fas fa-eye"></i></button>';
        $btn .= '</div>';
        return $btn;
      })
      ->rawColumns(['action'])
      ->make(true);
  }

  public function calculateAccrued(Request $request)
  {
    $validated = $request->validate([
      'accrued_date' => 'required|date'
    ]);

    $accruedDate = $validated['accrued_date'];

    DB::beginTransaction();
    try {
      // Calculate for active savings accounts
      $accounts = AccountInfo::where('status', AccountInfo::STATUS_ACTIVE)
        ->where('acct_type', 'sa')
        ->where('balance', '>', 0)
        ->get();

      $totalAccrued = 0;

      foreach ($accounts as $account) {
        $rate = $account->intRate->rate ?? 2.5; // Default 2.5% if no rate found
        if ($rate > 0) {
          $dailyRate = ($rate / 100) / 365;
          $accruedAmount = $account->balance * $dailyRate;

          if ($accruedAmount > 0) {
            // Update or create accrued interest record
            AccruedInt::updateOrCreate(
              ['acct_id' => $account->acct_id],
              [
                'last_accrued_date' => $accruedDate,
                'last_accrued_int' => $accruedAmount,
                'accrued_int_balance' => $accruedAmount
              ]
            );

            $totalAccrued += $accruedAmount;
          }
        }
      }

      DB::commit();

      return response()->json([
        'success' => true,
        'message' => 'Accrued interest calculated successfully for ' . count($accounts) . ' accounts',
        'total_accrued' => number_format($totalAccrued, 2)
      ]);
    } catch (\Exception $e) {
      DB::rollback();
      return response()->json([
        'success' => false,
        'message' => 'Error calculating accrued interest: ' . $e->getMessage()
      ], 500);
    }
  }

  public function accruedSummary(Request $request)
  {
    $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
    $dateTo = $request->get('date_to', now()->toDateString());

    $summary = AccruedInt::whereBetween('last_accrued_date', [$dateFrom, $dateTo])
      ->selectRaw('last_accrued_date, SUM(last_accrued_int) as total_amount, COUNT(*) as count')
      ->groupBy('last_accrued_date')
      ->orderBy('last_accrued_date')
      ->get();

    $totals = [
      'total_count' => $summary->sum('count'),
      'total_amount' => $summary->sum('total_amount')
    ];

    return response()->json([
      'success' => true,
      'summary' => $summary,
      'totals' => $totals
    ]);
  }
}
