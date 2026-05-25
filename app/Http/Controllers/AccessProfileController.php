<?php

namespace App\Http\Controllers;

use App\Models\AccessProfile;
use App\Models\AccessProfileDetail;
use App\Models\Module;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class AccessProfileController extends Controller
{
  public function index()
  {
    $modules = Module::all();
    return view('access-profiles.index', compact('modules'));
  }

  public function getData()
  {
    $profiles = AccessProfile::select('access_profiles.*');

    return DataTables::of($profiles)
      ->addColumn('limits', function ($row) {
        return 'D: $' . number_format($row->deposit_limit ?? 0, 2) .
          ' | W: $' . number_format($row->withdrawal_limit ?? 0, 2) .
          ' | L: $' . number_format($row->loan_limit ?? 0, 2);
      })
      ->addColumn('action', function ($row) {
        $btn = '<div class="btn-group" role="group">';
        $btn .= '<button type="button" class="btn btn-sm btn-info view-btn" data-id="' . $row->profile_id . '"><i class="fas fa-eye"></i></button>';
        $btn .= '<button type="button" class="btn btn-sm btn-primary edit-btn" data-id="' . $row->profile_id . '"><i class="fas fa-edit"></i></button>';
        $btn .= '<button type="button" class="btn btn-sm btn-success permissions-btn" data-id="' . $row->profile_id . '"><i class="fas fa-shield-alt"></i></button>';
        $btn .= '<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="' . $row->profile_id . '"><i class="fas fa-trash"></i></button>';
        $btn .= '</div>';
        return $btn;
      })
      ->rawColumns(['action'])
      ->make(true);
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'profile' => 'required|string|max:50',
      'deposit_limit' => 'nullable|numeric|min:0',
      'withdrawal_limit' => 'nullable|numeric|min:0',
      'loan_limit' => 'nullable|numeric|min:0',
      'non_cash_limit' => 'nullable|numeric|min:0'
    ]);

    $profile = AccessProfile::create($validated);

    return response()->json([
      'success' => true,
      'message' => 'Access profile created successfully',
      'data' => $profile
    ]);
  }

  public function show($id)
  {
    $profile = AccessProfile::with('modules')->findOrFail($id);
    return response()->json($profile);
  }

  public function update(Request $request, $id)
  {
    $profile = AccessProfile::findOrFail($id);

    $validated = $request->validate([
      'profile' => 'required|string|max:50',
      'deposit_limit' => 'nullable|numeric|min:0',
      'withdrawal_limit' => 'nullable|numeric|min:0',
      'loan_limit' => 'nullable|numeric|min:0',
      'non_cash_limit' => 'nullable|numeric|min:0'
    ]);

    $profile->update($validated);

    return response()->json([
      'success' => true,
      'message' => 'Access profile updated successfully',
      'data' => $profile
    ]);
  }

  public function destroy($id)
  {
    $profile = AccessProfile::findOrFail($id);

    if ($profile->users()->count() > 0) {
      return response()->json([
        'success' => false,
        'message' => 'Cannot delete profile with existing users'
      ], 422);
    }

    DB::beginTransaction();
    try {
      $profile->profileDetails()->delete();
      $profile->delete();
      DB::commit();

      return response()->json([
        'success' => true,
        'message' => 'Access profile deleted successfully'
      ]);
    } catch (\Exception $e) {
      DB::rollback();
      return response()->json([
        'success' => false,
        'message' => 'Error deleting profile: ' . $e->getMessage()
      ], 500);
    }
  }

  public function getPermissions($id)
  {
    $profile = AccessProfile::findOrFail($id);
    $modules = Module::all();
    $assignedModules = $profile->profileDetails()->pluck('module_id')->toArray();

    return response()->json([
      'modules' => $modules,
      'assigned' => $assignedModules
    ]);
  }

  public function updatePermissions(Request $request, $id)
  {
    $validated = $request->validate([
      'modules' => 'array',
      'modules.*' => 'exists:modules,module_id'
    ]);

    $profile = AccessProfile::findOrFail($id);

    DB::beginTransaction();
    try {
      // Remove existing permissions
      $profile->profileDetails()->delete();

      // Add new permissions
      if (!empty($validated['modules'])) {
        foreach ($validated['modules'] as $moduleId) {
          AccessProfileDetail::create([
            'profile_id' => $id,
            'module_id' => $moduleId
          ]);
        }
      }

      DB::commit();

      return response()->json([
        'success' => true,
        'message' => 'Permissions updated successfully'
      ]);
    } catch (\Exception $e) {
      DB::rollback();
      return response()->json([
        'success' => false,
        'message' => 'Error updating permissions: ' . $e->getMessage()
      ], 500);
    }
  }
}
