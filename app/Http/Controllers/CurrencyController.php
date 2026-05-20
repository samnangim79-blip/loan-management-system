<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\ExRateHistory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CurrencyController extends Controller
{
  public function index()
  {
    return view('currencies.index');
  }

  public function getData()
  {
    $currencies = Currency::select('currencys.*');

    return DataTables::of($currencies)
      ->addColumn('formatted_rate', function ($row) {
        return number_format($row->CCY_RATE, 5);
      })
      ->addColumn('action', function ($row) {
        $btn = '<div class="btn-group" role="group">';
        $btn .= '<button type="button" class="btn btn-sm btn-info view-btn" data-id="' . $row->CCY_ID . '"><i class="fas fa-eye"></i></button>';
        $btn .= '<button type="button" class="btn btn-sm btn-primary edit-btn" data-id="' . $row->CCY_ID . '"><i class="fas fa-edit"></i></button>';
        $btn .= '<button type="button" class="btn btn-sm btn-warning history-btn" data-id="' . $row->CCY_ID . '"><i class="fas fa-history"></i></button>';
        $btn .= '</div>';
        return $btn;
      })
      ->rawColumns(['action'])
      ->make(true);
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'currency' => 'required|string|max:4|unique:currencys,CURRENCY',
      'ccy_rate' => 'required|numeric|min:0',
      'round_value' => 'nullable|integer|min:0',
      'decimal_place' => 'nullable|integer|min:0|max:5',
      'compare_value' => 'nullable|integer',
      'value_format' => 'nullable|integer'
    ]);

    $validated['round_value'] = $validated['round_value'] ?? 0;
    $validated['decimal_place'] = $validated['decimal_place'] ?? 2;

    $currency = Currency::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'Currency created successfully',
      'data' => $currency
    ]);
  }

  public function show($id)
  {
    $currency = Currency::findOrFail($id);
    return response()->json($currency);
  }

  public function update(Request $request, $id)
  {
    $currency = Currency::findOrFail($id);

    $validated = $request->validate([
      'currency' => 'required|string|max:4|unique:currencys,CURRENCY,' . $id . ',CCY_ID',
      'ccy_rate' => 'required|numeric|min:0',
      'round_value' => 'nullable|integer|min:0',
      'decimal_place' => 'nullable|integer|min:0|max:5',
      'compare_value' => 'nullable|integer',
      'value_format' => 'nullable|integer'
    ]);

    // Log rate change to history
    if ($currency->CCY_RATE != $validated['ccy_rate']) {
      ExRateHistory::create([
        'ex_rate' => $currency->CCY_RATE,
        'rate_date' => now()->toDateString()
      ]);
    }

    $currency->update($validated);

    return response()->json([
      'success' => true,
      'message' => 'Currency updated successfully',
      'data' => $currency
    ]);
  }

  public function getRateHistory($id)
  {
    $history = ExRateHistory::orderBy('rate_date', 'desc')
      ->limit(50)
      ->get();

    return response()->json($history);
  }

  public function all()
  {
    $currencies = Currency::all(['ccy_id', 'currency', 'ccy_rate']);
    return response()->json($currencies);
  }
}
