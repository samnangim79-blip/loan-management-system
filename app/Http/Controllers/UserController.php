<?php

namespace App\Http\Controllers;

use App\Models\UserLogin;
use App\Models\AccessProfile;
use App\Models\Branch;
use App\Models\Staff;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
  public function index()
  {
    $profiles = AccessProfile::all();
    $branches = Branch::all();
    return view('users.index', compact('profiles', 'branches'));
  }

  public function getData()
  {
    $users = UserLogin::with(['staff', 'profile', 'branch'])->select('user_logins.*');

    return DataTables::of($users)
      ->addColumn('staff_name', function ($row) {
        return $row->staff->full_name ?? 'N/A';
      })
      ->addColumn('profile_name', function ($row) {
        return $row->profile->profile ?? 'N/A';
      })
      ->addColumn('branch_name', function ($row) {
        return $row->branch->branch_name ?? 'N/A';
      })
      ->addColumn('status_badge', function ($row) {
        $statuses = [
          0 => '<span class="badge bg-success">Active</span>',
          1 => '<span class="badge bg-warning">Suspended</span>',
          2 => '<span class="badge bg-danger">Deleted</span>'
        ];
        return $statuses[$row->status] ?? '<span class="badge bg-secondary">Unknown</span>';
      })
      ->addColumn('action', function ($row) {
        $btn = '<div class="btn-group" role="group">';
        $btn .= '<button type="button" class="btn btn-sm btn-info view-btn" data-id="' . $row->user_id . '"><i class="fas fa-eye"></i></button>';
        $btn .= '<button type="button" class="btn btn-sm btn-primary edit-btn" data-id="' . $row->user_id . '"><i class="fas fa-edit"></i></button>';
        $btn .= '<button type="button" class="btn btn-sm btn-warning reset-pwd-btn" data-id="' . $row->user_id . '"><i class="fas fa-key"></i></button>';
        $btn .= '<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->user_id . '"><i class="fas fa-trash"></i></button>';
        $btn .= '</div>';
        return $btn;
      })
      ->rawColumns(['status_badge', 'action'])
      ->make(true);
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'staff_id' => 'required|exists:staffs,staff_id',
      'login_name' => 'required|string|max:50|unique:user_logins,login_name',
      'password' => 'required|string|min:6|max:50',
      'profile_id' => 'required|exists:access_profiles,profile_id',
      'branch_id' => 'required|exists:branchs,branch_id',
      'sys_cash_limit' => 'nullable|numeric|min:0'
    ]);

    $validated['status'] = 0; // Active
    $validated['failed_log'] = 0;
    $validated['next_pwd_expire'] = now()->addDays(90);

    $user = UserLogin::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'User created successfully',
      'data' => $user
    ]);
  }

  public function show($id)
  {
    $user = UserLogin::with(['staff', 'profile', 'branch'])->findOrFail($id);
    return response()->json($user);
  }

  public function update(Request $request, $id)
  {
    $user = UserLogin::findOrFail($id);

    $validated = $request->validate([
      'staff_id' => 'required|exists:staffs,staff_id',
      'login_name' => 'required|string|max:50|unique:user_logins,login_name,' . $id . ',user_id',
      'profile_id' => 'required|exists:access_profiles,profile_id',
      'branch_id' => 'required|exists:branchs,branch_id',
      'sys_cash_limit' => 'nullable|numeric|min:0',
      'status' => 'required|in:0,1,2'
    ]);

    $user->update($validated);

    return response()->json([
      'success' => true,
      'message' => 'User updated successfully',
      'data' => $user
    ]);
  }

  public function destroy($id)
  {
    $user = UserLogin::findOrFail($id);
    $user->update(['status' => 2]); // Soft delete by setting status to Deleted

    return response()->json([
      'success' => true,
      'message' => 'User deleted successfully'
    ]);
  }

  public function resetPassword(Request $request, $id)
  {
    $validated = $request->validate([
      'password' => 'required|string|min:6|max:50'
    ]);

    $user = UserLogin::findOrFail($id);
    $user->update([
      'password' => $validated['password'],
      'failed_log' => 0,
      'next_pwd_expire' => now()->addDays(90)
    ]);

    return response()->json([
      'success' => true,
      'message' => 'Password reset successfully'
    ]);
  }

  public function suspend($id)
  {
    $user = UserLogin::findOrFail($id);
    $user->update(['status' => 1]);

    return response()->json([
      'success' => true,
      'message' => 'User suspended successfully'
    ]);
  }

  public function activate($id)
  {
    $user = UserLogin::findOrFail($id);
    $user->update([
      'status' => 0,
      'failed_log' => 0
    ]);

    return response()->json([
      'success' => true,
      'message' => 'User activated successfully'
    ]);
  }
}
