<?php

namespace App\Http\Middleware;

use App\Models\AccessProfile;
use App\Models\Module;
use App\Models\User;
use App\Models\UserLogin;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
  /**
   * Handle an incoming request.
   */
  public function handle(Request $request, Closure $next, ?string $permission = null): Response
  {
    if (!Auth::check()) {
      if ($request->expectsJson()) {
        return response()->json(['error' => 'Unauthenticated.'], 401);
      }
      return redirect()->route('login');
    }

    if ($permission && !$this->hasPermission(Auth::user(), $permission)) {
      if ($request->expectsJson()) {
        return response()->json(['error' => 'Forbidden. You do not have permission to access this resource.'], 403);
      }
      abort(403, 'You do not have permission to access this resource.');
    }

    return $next($request);
  }

  /**
   * Check if user has a specific permission (module access)
   */
  public function hasPermission($user, string $permission): bool
  {
    // Super admin check - get from config or user attribute
    if ($this->isSuperAdmin($user)) {
      return true;
    }

    $profile = $this->getUserProfile($user);

    if (!$profile) {
      return false;
    }

    // Check if the permission is a module control name
    return $profile->hasModule($permission);
  }

  /**
   * Check if user has all specified permissions
   */
  public function hasAllPermissions($user, array $permissions): bool
  {
    if ($this->isSuperAdmin($user)) {
      return true;
    }

    foreach ($permissions as $permission) {
      if (!$this->hasPermission($user, $permission)) {
        return false;
      }
    }

    return true;
  }

  /**
   * Check if user has any of the specified permissions
   */
  public function hasAnyPermission($user, array $permissions): bool
  {
    if ($this->isSuperAdmin($user)) {
      return true;
    }

    foreach ($permissions as $permission) {
      if ($this->hasPermission($user, $permission)) {
        return true;
      }
    }

    return false;
  }

  /**
   * Check if user can access a specific resource with an action
   */
  public function canAccessResource($user, string $resource, string $action = 'access'): bool
  {
    if ($this->isSuperAdmin($user)) {
      return true;
    }

    // Map resource.action to module control name
    $permission = $this->mapResourceToPermission($resource, $action);

    return $this->hasPermission($user, $permission);
  }

  /**
   * Check CRUD permissions
   */
  public function canCRUD($user, string $resource, string $action): bool
  {
    if ($this->isSuperAdmin($user)) {
      return true;
    }

    $actionMap = [
      'create' => 'add',
      'read' => 'view',
      'update' => 'edit',
      'delete' => 'delete',
      'view' => 'view',
      'add' => 'add',
      'edit' => 'edit',
      'list' => 'view',
      'index' => 'view',
      'store' => 'add',
      'show' => 'view',
      'destroy' => 'delete',
    ];

    $mappedAction = $actionMap[strtolower($action)] ?? $action;
    $permission = strtolower($resource) . '_' . $mappedAction;

    return $this->hasPermission($user, $permission);
  }

  /**
   * Get all permissions for a user
   */
  public function getUserPermissions($user): array
  {
    $profile = $this->getUserProfile($user);

    if (!$profile) {
      return [];
    }

    $modules = $profile->modules()->where('status', 0)->get();

    return $modules->pluck('control_name')->toArray();
  }

  /**
   * Get all accessible resources for a user
   */
  public function getAccessibleResources($user): array
  {
    $profile = $this->getUserProfile($user);

    if (!$profile) {
      return [];
    }

    $modules = $profile->modules()->where('status', 0)->get();

    return $modules->map(function ($module) {
      return [
        'id' => $module->module_id,
        'name' => $module->module,
        'control_name' => $module->control_name,
        'url' => $module->url,
        'type' => $module->type,
      ];
    })->toArray();
  }

  /**
   * Check if user can manage other users
   */
  public function canManageUsers($user): bool
  {
    if ($this->isSuperAdmin($user)) {
      return true;
    }

    return $this->hasAnyPermission($user, [
      'user_management',
      'users',
      'user_add',
      'user_edit',
      'user_delete',
      'staff_management',
      'access_profiles',
    ]);
  }

  /**
   * Check if user can access dashboard
   */
  public function canAccessDashboard($user): bool
  {
    // All authenticated users can access dashboard by default
    return Auth::check();
  }

  /**
   * Check if user is a super admin
   */
  public function isSuperAdmin($user): bool
  {
    // Check by email (for development/admin accounts)
    $superAdminEmails = config('auth.super_admins', ['admin@example.com']);

    if ($user instanceof User && in_array($user->email, $superAdminEmails)) {
      return true;
    }

    // Check by profile name for UserLogin
    if ($user instanceof UserLogin && $user->profile) {
      $adminProfiles = ['Super Admin', 'Administrator', 'Admin', 'super_admin'];
      return in_array($user->profile->profile, $adminProfiles);
    }

    // Check if User has a linked UserLogin with admin profile
    if ($user instanceof User) {
      $userLogin = $this->getUserLoginFromUser($user);
      if ($userLogin && $userLogin->profile) {
        $adminProfiles = ['Super Admin', 'Administrator', 'Admin', 'super_admin'];
        return in_array($userLogin->profile->profile, $adminProfiles);
      }
    }

    return false;
  }

  /**
   * Get the access profile for a user
   */
  public function getUserProfile($user): ?AccessProfile
  {
    if ($user instanceof UserLogin) {
      return $user->profile;
    }

    if ($user instanceof User) {
      // Try to get linked UserLogin
      $userLogin = $this->getUserLoginFromUser($user);
      return $userLogin?->profile;
    }

    return null;
  }

  /**
   * Get UserLogin from User (if linked by email or staff)
   */
  protected function getUserLoginFromUser(User $user): ?UserLogin
  {
    // Check by login name matching email or username
    $userLogin = UserLogin::where('login_name', $user->email)->first();

    if (!$userLogin && $user->username) {
      $userLogin = UserLogin::where('login_name', $user->username)->first();
    }

    return $userLogin;
  }

  /**
   * Map resource and action to permission string
   */
  protected function mapResourceToPermission(string $resource, string $action): string
  {
    // Common resource mappings
    $resourceMap = [
      'customers' => 'customer',
      'accounts' => 'account',
      'loans' => 'loan',
      'transactions' => 'transaction',
      'deposits' => 'deposit',
      'withdrawals' => 'withdrawal',
      'users' => 'user',
      'staff' => 'staff',
      'reports' => 'report',
      'settings' => 'config',
      'config' => 'config',
    ];

    $resource = $resourceMap[strtolower($resource)] ?? strtolower($resource);

    return $resource . '_' . strtolower($action);
  }

  /**
   * Get user's transaction limits
   */
  public function getUserLimits($user): array
  {
    $profile = $this->getUserProfile($user);

    if (!$profile) {
      return [
        'deposit_limit' => 0,
        'withdrawal_limit' => 0,
        'loan_limit' => 0,
        'non_cash_limit' => 0,
      ];
    }

    return [
      'deposit_limit' => (float) $profile->deposit_limit,
      'withdrawal_limit' => (float) $profile->withdrawal_limit,
      'loan_limit' => (float) $profile->loan_limit,
      'non_cash_limit' => (float) $profile->non_cash_limit,
    ];
  }

  /**
   * Check if user can perform a transaction of a given amount
   */
  public function canPerformTransaction($user, string $type, float $amount): bool
  {
    if ($this->isSuperAdmin($user)) {
      return true;
    }

    $limits = $this->getUserLimits($user);

    $limitMap = [
      'deposit' => 'deposit_limit',
      'withdrawal' => 'withdrawal_limit',
      'loan' => 'loan_limit',
      'non_cash' => 'non_cash_limit',
    ];

    $limitKey = $limitMap[strtolower($type)] ?? null;

    if (!$limitKey) {
      return false;
    }

    $limit = $limits[$limitKey];

    // If limit is 0 or null, no limit is set (unlimited)
    if ($limit <= 0) {
      return true;
    }

    return $amount <= $limit;
  }
}
