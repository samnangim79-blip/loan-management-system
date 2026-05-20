<?php

namespace App\Http\Controllers;

use App\Models\FixedAsset;
use App\Models\FixedAssetType;
use App\Models\FixedAssetDepre;
use App\Models\Currency;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class FixedAssetController extends Controller
{
  public function index()
  {
    $assetTypes = FixedAssetType::all();
    $currencies = Currency::all();
    return view('fixed-assets.index', compact('assetTypes', 'currencies'));
  }

  public function getData()
  {
    $assets = FixedAsset::with(['assetType', 'currency'])->select('fixed_assets.*');

    return DataTables::of($assets)
      ->addColumn('type_name', function ($row) {
        return $row->assetType->FA_TYPE ?? 'N/A';
      })
      ->addColumn('currency_code', function ($row) {
        return $row->currency->CURRENCY ?? 'N/A';
      })
      ->addColumn('formatted_price', function ($row) {
        return number_format($row->PURCHASE_PRICE ?? 0, 2);
      })
      ->addColumn('formatted_net_value', function ($row) {
        return number_format($row->NET_VALUE ?? 0, 2);
      })
      ->addColumn('action', function ($row) {
        $btn = '<div class="btn-group" role="group">';
        $btn .= '<button type="button" class="btn btn-sm btn-info view-btn" data-id="' . $row->FA_ID . '"><i class="fas fa-eye"></i></button>';
        $btn .= '<button type="button" class="btn btn-sm btn-primary edit-btn" data-id="' . $row->FA_ID . '"><i class="fas fa-edit"></i></button>';
        $btn .= '<button type="button" class="btn btn-sm btn-warning depreciate-btn" data-id="' . $row->FA_ID . '"><i class="fas fa-chart-line"></i></button>';
        if (!$row->DISPOSE_DATE) {
          $btn .= '<button type="button" class="btn btn-sm btn-danger dispose-btn" data-id="' . $row->FA_ID . '"><i class="fas fa-times-circle"></i></button>';
        }
        $btn .= '</div>';
        return $btn;
      })
      ->rawColumns(['action'])
      ->make(true);
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'fa_code' => 'required|string|max:50|unique:fixed_assets,FA_CODE',
      'fa_desc' => 'required|string|max:250',
      'fa_comment' => 'nullable|string',
      'fa_type_id' => 'required|exists:fixed_asset_types,FA_TYPE_ID',
      'purchase_date' => 'required|date',
      'purchase_price' => 'required|numeric|min:0',
      'ccy_id' => 'required|exists:currencys,CCY_ID',
      'usefull_life' => 'required|integer|min:1',
      'credit_gl' => 'nullable|exists:gls,GL_ID'
    ]);

    $validated['net_value'] = $validated['purchase_price'];
    $validated['added_by'] = auth()->id() ?? 1;
    $validated['added_date'] = now();

    $asset = FixedAsset::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'Fixed asset created successfully',
      'data' => $asset
    ]);
  }

  public function show($id)
  {
    $asset = FixedAsset::with(['assetType', 'currency', 'depreciations'])->findOrFail($id);
    return response()->json($asset);
  }

  public function update(Request $request, $id)
  {
    $asset = FixedAsset::findOrFail($id);

    $validated = $request->validate([
      'fa_code' => 'required|string|max:50|unique:fixed_assets,FA_CODE,' . $id . ',FA_ID',
      'fa_desc' => 'required|string|max:250',
      'fa_comment' => 'nullable|string',
      'fa_type_id' => 'required|exists:fixed_asset_types,FA_TYPE_ID',
      'usefull_life' => 'required|integer|min:1'
    ]);

    $asset->update($validated);

    return response()->json([
      'success' => true,
      'message' => 'Fixed asset updated successfully',
      'data' => $asset
    ]);
  }

  public function depreciate(Request $request, $id)
  {
    $asset = FixedAsset::findOrFail($id);

    $validated = $request->validate([
      'depre_date' => 'required|date',
      'amount' => 'required|numeric|min:0|max:' . $asset->NET_VALUE
    ]);

    DB::beginTransaction();
    try {
      // Create depreciation record
      FixedAssetDepre::create([
        'depre_date' => $validated['depre_date'],
        'fa_id' => $id,
        'amount' => $validated['amount']
      ]);

      // Update net value
      $asset->NET_VALUE -= $validated['amount'];
      $asset->save();

      DB::commit();

      return response()->json([
        'success' => true,
        'message' => 'Depreciation recorded successfully',
        'data' => $asset
      ]);
    } catch (\Exception $e) {
      DB::rollback();
      return response()->json([
        'success' => false,
        'message' => 'Error recording depreciation: ' . $e->getMessage()
      ], 500);
    }
  }

  public function dispose(Request $request, $id)
  {
    $asset = FixedAsset::findOrFail($id);

    $validated = $request->validate([
      'dispose_date' => 'required|date',
      'dispose_value' => 'required|numeric|min:0',
      'dispose_comment' => 'nullable|string'
    ]);

    $validated['dispose_by'] = auth()->id() ?? 1;

    $asset->update($validated);

    return response()->json([
      'success' => true,
      'message' => 'Fixed asset disposed successfully',
      'data' => $asset
    ]);
  }

  public function getDepreciationHistory($id)
  {
    $depreciations = FixedAssetDepre::where('fa_id', $id)
      ->orderBy('depre_date', 'desc')
      ->get();

    return response()->json($depreciations);
  }

  // Asset Types
  public function typesIndex()
  {
    return view('fixed-assets.types');
  }

  public function getTypesData()
  {
    $types = FixedAssetType::select('fixed_asset_types.*');

    return DataTables::of($types)
      ->addColumn('action', function ($row) {
        $btn = '<div class="btn-group" role="group">';
        $btn .= '<button type="button" class="btn btn-sm btn-primary edit-btn" data-id="' . $row->FA_TYPE_ID . '"><i class="fas fa-edit"></i></button>';
        $btn .= '<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->FA_TYPE_ID . '"><i class="fas fa-trash"></i></button>';
        $btn .= '</div>';
        return $btn;
      })
      ->rawColumns(['action'])
      ->make(true);
  }

  public function storeType(Request $request)
  {
    $validated = $request->validate([
      'fa_type' => 'required|string|max:50',
      'gl_id' => 'nullable|exists:gls,GL_ID',
      'depre_gl' => 'nullable|exists:gls,GL_ID',
      'exp_gl' => 'nullable|exists:gls,GL_ID',
      'dispose_gl' => 'nullable|exists:gls,GL_ID'
    ]);

    $type = FixedAssetType::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'Asset type created successfully',
      'data' => $type
    ]);
  }

  public function updateType(Request $request, $id)
  {
    $type = FixedAssetType::findOrFail($id);

    $validated = $request->validate([
      'fa_type' => 'required|string|max:50',
      'gl_id' => 'nullable|exists:gls,GL_ID',
      'depre_gl' => 'nullable|exists:gls,GL_ID',
      'exp_gl' => 'nullable|exists:gls,GL_ID',
      'dispose_gl' => 'nullable|exists:gls,GL_ID'
    ]);

    $type->update($validated);

    return response()->json([
      'success' => true,
      'message' => 'Asset type updated successfully',
      'data' => $type
    ]);
  }

  public function destroyType($id)
  {
    $type = FixedAssetType::findOrFail($id);

    if ($type->fixedAssets()->count() > 0) {
      return response()->json([
        'success' => false,
        'message' => 'Cannot delete asset type with existing assets'
      ], 422);
    }

    $type->delete();

    return response()->json([
      'success' => true,
      'message' => 'Asset type deleted successfully'
    ]);
  }
}
