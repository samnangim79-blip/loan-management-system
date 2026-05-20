<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\BranchTran;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
  public function index()
  {
    return view('branches.index');
  }

  public function all()
  {
    $branches = Branch::select('branch_id', 'branch_name', 'phone', 'email', 'website')->get();
    return response()->json($branches);
  }

  public function getData()
  {
    $branches = Branch::select('branchs.*');

    return DataTables::of($branches)
      ->addColumn('action', function ($row) {
        $btn = '<div class="btn-group" role="group">';
        $btn .= '<button type="button" class="btn btn-sm btn-info view-btn" data-id="' . $row->branch_id . '"><i class="fas fa-eye"></i></button>';
        $btn .= '<button type="button" class="btn btn-sm btn-primary edit-btn" data-id="' . $row->branch_id . '"><i class="fas fa-edit"></i></button>';
        $btn .= '<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->branch_id . '"><i class="fas fa-trash"></i></button>';
        $btn .= '</div>';
        return $btn;
      })
      ->rawColumns(['action'])
      ->make(true);
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'branch_id' => 'required|unique:branchs,branch_id',
      'branch_name' => 'required|string|max:255',
      'phone' => 'nullable|string|max:50',
      'email' => 'nullable|email|max:100',
      'website' => 'nullable|string|max:100'
    ]);

    $branch = Branch::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'Branch created successfully',
      'data' => $branch
    ]);
  }

  public function show($id)
  {
    $branch = Branch::with(['staff', 'accounts'])->findOrFail($id);
    return response()->json($branch);
  }

  public function update(Request $request, $id)
  {
    $branch = Branch::findOrFail($id);

    $validated = $request->validate([
      'branch_name' => 'required|string|max:255',
      'phone' => 'nullable|string|max:50',
      'email' => 'nullable|email|max:100',
      'website' => 'nullable|string|max:100'
    ]);

    $branch->update($validated);

    return response()->json([
      'success' => true,
      'message' => 'Branch updated successfully',
      'data' => $branch
    ]);
  }

  public function destroy($id)
  {
    $branch = Branch::findOrFail($id);

    if ($branch->staff()->count() > 0 || $branch->accounts()->count() > 0) {
      return response()->json([
        'success' => false,
        'message' => 'Cannot delete branch with existing staff or accounts'
      ], 422);
    }

    $branch->delete();

    return response()->json([
      'success' => true,
      'message' => 'Branch deleted successfully'
    ]);
  }

  public function getTransactions($id)
  {
    $transactions = BranchTran::where('branch_id', $id)
      ->orderBy('tran_date', 'desc')
      ->get();

    return response()->json($transactions);
  }

  public function startDay(Request $request, $id)
  {
    $validated = $request->validate([
      'tran_date' => 'required|date'
    ]);

    $branchTran = BranchTran::create([
      'branch_id' => $id,
      'tran_date' => $validated['tran_date'],
      'started_by' => Auth::id() ?? 1,
      'started_date' => now()
    ]);

    return response()->json([
      'success' => true,
      'message' => 'Branch day started successfully',
      'data' => $branchTran
    ]);
  }

  public function endDay(Request $request, $id)
  {
    $branchTran = BranchTran::where('branch_id', $id)
      ->whereNull('ended_date')
      ->latest('started_date')
      ->first();

    if (!$branchTran) {
      return response()->json([
        'success' => false,
        'message' => 'No active branch day found'
      ], 422);
    }

    $branchTran->update([
      'ended_by' => Auth::id() ?? 1,
      'ended_date' => now()
    ]);

    return response()->json([
      'success' => true,
      'message' => 'Branch day ended successfully',
      'data' => $branchTran
    ]);
  }
}
