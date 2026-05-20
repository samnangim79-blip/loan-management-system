<?php

namespace App\Http\Controllers;

use App\Models\Nationality;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class NationalityController extends Controller
{
  public function index()
  {
    return view('nationalities.index');
  }

  public function getData()
  {
    $nationalities = Nationality::select('nationalitys.*');

    return DataTables::of($nationalities)
      ->addColumn('action', function ($row) {
        $btn = '<div class="btn-group" role="group">';
        $btn .= '<button type="button" class="btn btn-sm btn-primary edit-btn" data-id="' . $row->nationality_id . '"><i class="fas fa-edit"></i></button>';
        $btn .= '<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->nationality_id . '"><i class="fas fa-trash"></i></button>';
        $btn .= '</div>';
        return $btn;
      })
      ->rawColumns(['action'])
      ->make(true);
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'nationality' => 'required|string|max:255',
      'nationality_kh' => 'nullable|string|max:255'
    ]);

    $nationality = Nationality::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'Nationality created successfully',
      'data' => $nationality
    ]);
  }

  public function show($id)
  {
    $nationality = Nationality::findOrFail($id);
    return response()->json($nationality);
  }

  public function update(Request $request, $id)
  {
    $nationality = Nationality::findOrFail($id);

    $validated = $request->validate([
      'nationality' => 'required|string|max:255',
      'nationality_kh' => 'nullable|string|max:255'
    ]);

    $nationality->update($validated);

    return response()->json([
      'success' => true,
      'message' => 'Nationality updated successfully',
      'data' => $nationality
    ]);
  }

  public function destroy($id)
  {
    $nationality = Nationality::findOrFail($id);
    $nationality->delete();

    return response()->json([
      'success' => true,
      'message' => 'Nationality deleted successfully'
    ]);
  }

  public function all()
  {
    $nationalities = Nationality::all(['nationality_id', 'nationality', 'nationality_kh']);
    return response()->json(['success' => true, 'data' => $nationalities]);
  }
}
