<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Branch;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class StaffController extends Controller
{
  public function index()
  {
    $branches = Branch::all();
    return view('staff.index', compact('branches'));
  }

  public function getData()
  {
    $staff = Staff::with('branch')->select('staffs.*');

    return DataTables::of($staff)
      ->addColumn('branch_name', function ($row) {
        return $row->branch->branch_name ?? 'N/A';
      })
      ->addColumn('action', function ($row) {
        $btn = '<div class="btn-group" role="group">';
        $btn .= '<button type="button" class="btn btn-sm btn-info view-btn" data-id="' . $row->staff_id . '"><i class="fas fa-eye"></i></button>';
        $btn .= '<button type="button" class="btn btn-sm btn-primary edit-btn" data-id="' . $row->staff_id . '"><i class="fas fa-edit"></i></button>';
        $btn .= '<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->staff_id . '"><i class="fas fa-trash"></i></button>';
        $btn .= '</div>';
        return $btn;
      })
      ->rawColumns(['action'])
      ->make(true);
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'ic_no' => 'required|string|max:30',
      'full_name' => 'required|string|max:50',
      'gender' => 'required|in:M,F',
      'dob' => 'nullable|date',
      'pob' => 'nullable|string|max:100',
      'address' => 'nullable|string|max:100',
      'phone' => 'nullable|string|max:50',
      'position' => 'nullable|string|max:30',
      'branch_id' => 'required|exists:branchs,branch_id'
    ]);

    $staff = Staff::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'Staff created successfully',
      'data' => $staff
    ]);
  }

  public function show($id)
  {
    $staff = Staff::with('branch')->findOrFail($id);
    return response()->json($staff);
  }

  public function update(Request $request, $id)
  {
    $staff = Staff::findOrFail($id);

    $validated = $request->validate([
      'ic_no' => 'required|string|max:30',
      'full_name' => 'required|string|max:50',
      'gender' => 'required|in:M,F',
      'dob' => 'nullable|date',
      'pob' => 'nullable|string|max:100',
      'address' => 'nullable|string|max:100',
      'phone' => 'nullable|string|max:50',
      'position' => 'nullable|string|max:30',
      'branch_id' => 'required|exists:branchs,branch_id'
    ]);

    $staff->update($validated);

    return response()->json([
      'success' => true,
      'message' => 'Staff updated successfully',
      'data' => $staff
    ]);
  }

  public function destroy($id)
  {
    $staff = Staff::findOrFail($id);
    $staff->delete();

    return response()->json([
      'success' => true,
      'message' => 'Staff deleted successfully'
    ]);
  }

  public function search(Request $request)
  {
    $query = $request->get('q', '');

    $staff = Staff::where('full_name', 'like', "%{$query}%")
      ->orWhere('ic_no', 'like', "%{$query}%")
      ->limit(20)
      ->get(['staff_id', 'full_name', 'ic_no', 'position']);

    return response()->json($staff);
  }
}
