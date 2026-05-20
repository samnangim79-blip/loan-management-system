<?php

namespace App\Http\Controllers;

use App\Models\Gl;
use App\Models\GlL1;
use App\Models\GlL2;
use App\Models\GlL3;
use App\Models\GlL4;
use App\Models\GlMap;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class GlController extends Controller
{
  // GL Accounts
  public function index()
  {
    $l4s = GlL4::all();
    return view('gl.index', compact('l4s'));
  }

  public function getData()
  {
    $gls = Gl::with('level4')->select('gls.*');

    return DataTables::of($gls)
      ->addColumn('level4_desc', function ($row) {
        return $row->level4->l4_desc ?? 'N/A';
      })
      ->addColumn('action', function ($row) {
        $btn = '<div class="btn-group" role="group">';
        $btn .= '<button type="button" class="btn btn-sm btn-info view-btn" data-id="' . $row->gl_id . '"><i class="fas fa-eye"></i></button>';
        $btn .= '<button type="button" class="btn btn-sm btn-primary edit-btn" data-id="' . $row->gl_id . '"><i class="fas fa-edit"></i></button>';
        $btn .= '<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->gl_id . '"><i class="fas fa-trash"></i></button>';
        $btn .= '</div>';
        return $btn;
      })
      ->rawColumns(['action'])
      ->make(true);
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'gl_code' => 'required|string|max:11|unique:gls,gl_code',
      'gl_name' => 'required|string|max:80',
      'gl_name_kh' => 'nullable|string|max:80',
      'l4_id' => 'required|exists:gl_l4s,l4_id'
    ]);

    $gl = Gl::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'GL Account created successfully',
      'data' => $gl
    ]);
  }

  public function show($id)
  {
    $gl = Gl::with('level4')->findOrFail($id);
    return response()->json($gl);
  }

  public function update(Request $request, $id)
  {
    $gl = Gl::findOrFail($id);

    $validated = $request->validate([
      'gl_code' => 'required|string|max:11|unique:gls,gl_code,' . $id . ',gl_id',
      'gl_name' => 'required|string|max:80',
      'gl_name_kh' => 'nullable|string|max:80',
      'l4_id' => 'required|exists:gl_l4s,l4_id'
    ]);

    $gl->update($validated);

    return response()->json([
      'success' => true,
      'message' => 'GL Account updated successfully',
      'data' => $gl
    ]);
  }

  public function destroy($id)
  {
    $gl = Gl::findOrFail($id);
    $gl->delete();

    return response()->json([
      'success' => true,
      'message' => 'GL Account deleted successfully'
    ]);
  }

  // GL Hierarchy
  public function hierarchy()
  {
    $l1s = GlL1::all();
    $l2s = GlL2::with('level1')->get();
    $l3s = GlL3::with('level2')->get();
    $l4s = GlL4::with('level3')->get();

    return view('gl.hierarchy', compact('l1s', 'l2s', 'l3s', 'l4s'));
  }

  public function getHierarchyData()
  {
    $hierarchy = GlL1::with(['level2s.level3s.level4s.gls'])->get();
    return response()->json($hierarchy);
  }

  // GL Level 1
  public function storeL1(Request $request)
  {
    $validated = $request->validate([
      'l1_id' => 'required|integer|unique:gl_l1s,l1_id',
      'l1_desc' => 'required|string|max:250',
      'drcr' => 'required|in:DR,CR'
    ]);

    $l1 = GlL1::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'GL Level 1 created successfully',
      'data' => $l1
    ]);
  }

  // GL Level 2
  public function storeL2(Request $request)
  {
    $validated = $request->validate([
      'l2_id' => 'required|integer|unique:gl_l2s,l2_id',
      'l2_desc' => 'required|string|max:250',
      'l1_id' => 'required|exists:gl_l1s,l1_id'
    ]);

    $l2 = GlL2::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'GL Level 2 created successfully',
      'data' => $l2
    ]);
  }

  // GL Level 3
  public function storeL3(Request $request)
  {
    $validated = $request->validate([
      'l3_id' => 'required|integer|unique:gl_l3s,l3_id',
      'l3_desc' => 'required|string|max:250',
      'l2_id' => 'required|exists:gl_l2s,l2_id'
    ]);

    $l3 = GlL3::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'GL Level 3 created successfully',
      'data' => $l3
    ]);
  }

  // GL Level 4
  public function storeL4(Request $request)
  {
    $validated = $request->validate([
      'l4_id' => 'required|integer|unique:gl_l4s,l4_id',
      'l4_desc' => 'required|string|max:250',
      'l3_id' => 'required|exists:gl_l3s,l3_id'
    ]);

    $l4 = GlL4::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'GL Level 4 created successfully',
      'data' => $l4
    ]);
  }

  // GL Mapping
  public function mappingIndex()
  {
    $gls = Gl::all();
    return view('gl.mapping', compact('gls'));
  }

  public function getMappingData()
  {
    $maps = GlMap::select('gl_maps.*');

    return DataTables::of($maps)
      ->addColumn('action', function ($row) {
        $btn = '<div class="btn-group" role="group">';
        $btn .= '<button type="button" class="btn btn-sm btn-primary edit-btn" data-id="' . $row->gl_map_id . '"><i class="fas fa-edit"></i></button>';
        $btn .= '<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->gl_map_id . '"><i class="fas fa-trash"></i></button>';
        $btn .= '</div>';
        return $btn;
      })
      ->rawColumns(['action'])
      ->make(true);
  }

  public function storeMapping(Request $request)
  {
    $validated = $request->validate([
      'short_code' => 'required|string|max:5',
      'tran_desc' => 'required|string|max:200',
      'debit_gl_id' => 'required|exists:gls,gl_id',
      'credit_gl_id' => 'required|exists:gls,gl_id'
    ]);

    $validated['created_by'] = Auth::id() ?? 1;

    $map = GlMap::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'GL Mapping created successfully',
      'data' => $map
    ]);
  }

  public function search(Request $request)
  {
    $query = $request->get('q', '');

    $gls = Gl::where('gl_name', 'like', "%{$query}%")
      ->orWhere('gl_code', 'like', "%{$query}%")
      ->limit(20)
      ->get(['gl_id', 'gl_code', 'gl_name']);

    return response()->json($gls);
  }

  public function all()
  {
    $gls = Gl::with('level4')->get();
    return response()->json($gls);
  }

  public function getTree(Request $request)
  {
    $data = GlL1::with(['level2s.level3s.level4s.gls'])->get();

    // If it's an AJAX request, return JSON
    if ($request->wantsJson() || $request->ajax()) {
      return response()->json($data);
    }

    // Otherwise, return the tree view
    return view('gl.tree');
  }
}
