<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupDetail;
use App\Models\LoanSchedule;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
  public function index()
  {
    return view('groups.index');
  }

  public function getData()
  {
    $groups = Group::select('groups.*');

    return DataTables::of($groups)
      ->addColumn('member_count', function ($row) {
        return $row->details()->count();
      })
      ->addColumn('action', function ($row) {
        $btn = '<div class="btn-group" role="group">';
        $btn .= '<button type="button" class="btn btn-sm btn-info view-btn" data-id="' . $row->group_id . '"><i class="fas fa-eye"></i></button>';
        $btn .= '<button type="button" class="btn btn-sm btn-primary edit-btn" data-id="' . $row->group_id . '"><i class="fas fa-edit"></i></button>';
        $btn .= '<button type="button" class="btn btn-sm btn-success members-btn" data-id="' . $row->group_id . '"><i class="fas fa-users"></i></button>';
        $btn .= '<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->group_id . '"><i class="fas fa-trash"></i></button>';
        $btn .= '</div>';
        return $btn;
      })
      ->rawColumns(['action'])
      ->make(true);
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'group_name' => 'required|string|max:50',
      'date_issue' => 'required|date'
    ]);

    $validated['added_by'] = auth()->id() ?? 1;
    $validated['added_date'] = now()->toDateString();

    $group = Group::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'Group created successfully',
      'data' => $group
    ]);
  }

  public function show($id)
  {
    $group = Group::with('details')->findOrFail($id);
    return response()->json($group);
  }

  public function update(Request $request, $id)
  {
    $group = Group::findOrFail($id);

    $validated = $request->validate([
      'group_name' => 'required|string|max:50',
      'date_issue' => 'required|date'
    ]);

    $validated['updated_by'] = auth()->id() ?? 1;
    $validated['updated_date'] = now()->toDateString();

    $group->update($validated);

    return response()->json([
      'success' => true,
      'message' => 'Group updated successfully',
      'data' => $group
    ]);
  }

  public function destroy($id)
  {
    $group = Group::findOrFail($id);

    DB::beginTransaction();
    try {
      $group->details()->delete();
      $group->delete();
      DB::commit();

      return response()->json([
        'success' => true,
        'message' => 'Group deleted successfully'
      ]);
    } catch (\Exception $e) {
      DB::rollback();
      return response()->json([
        'success' => false,
        'message' => 'Error deleting group: ' . $e->getMessage()
      ], 500);
    }
  }

  public function getMembers($id)
  {
    $details = GroupDetail::where('group_id', $id)
      ->with(['loanSchedule.account.customer'])
      ->get();

    $members = $details->map(function ($detail) {
      $loan = LoanSchedule::where('contract_no', $detail->contract_no)->first();
      return [
        'group_detail_id' => $detail->group_detail_id,
        'contract_no' => $detail->contract_no,
        'customer_name' => $loan->account->customer->name_en ?? 'N/A',
        'loan_amount' => $loan->amount ?? 0,
        'os_balance' => $loan->os_balance ?? 0
      ];
    });

    return response()->json($members);
  }

  public function addMember(Request $request, $id)
  {
    $validated = $request->validate([
      'contract_no' => 'required|exists:loan_schedules,contract_no'
    ]);

    // Check if already a member
    $exists = GroupDetail::where('group_id', $id)
      ->where('contract_no', $validated['contract_no'])
      ->exists();

    if ($exists) {
      return response()->json([
        'success' => false,
        'message' => 'This loan is already a member of the group'
      ], 422);
    }

    $detail = GroupDetail::create([
      'group_id' => $id,
      'contract_no' => $validated['contract_no']
    ]);

    return response()->json([
      'success' => true,
      'message' => 'Member added successfully',
      'data' => $detail
    ]);
  }

  public function removeMember($groupId, $detailId)
  {
    $detail = GroupDetail::where('group_id', $groupId)
      ->where('group_detail_id', $detailId)
      ->firstOrFail();

    $detail->delete();

    return response()->json([
      'success' => true,
      'message' => 'Member removed successfully'
    ]);
  }

  public function searchLoans(Request $request)
  {
    $query = $request->get('q', '');

    $loans = LoanSchedule::with('account.customer')
      ->where('contract_no', 'like', "%{$query}%")
      ->orWhereHas('account.customer', function ($q) use ($query) {
        $q->where('name_en', 'like', "%{$query}%");
      })
      ->limit(20)
      ->get()
      ->map(function ($loan) {
        return [
          'contract_no' => $loan->contract_no,
          'customer_name' => $loan->account->customer->name_en ?? 'N/A',
          'amount' => $loan->amount
        ];
      });

    return response()->json($loans);
  }
}
