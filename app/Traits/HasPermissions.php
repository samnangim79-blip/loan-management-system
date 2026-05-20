<?php

namespace App\Traits;

use App\Http\Middleware\PermissionMiddleware;

trait HasPermissions
{
    /**
     * Get the permission middleware instance
     */
    protected function getPermissionMiddleware(): PermissionMiddleware
    {
        return new PermissionMiddleware();
    }

    /**
     * Check if current user has permission
     */
    public function hasPermission(string $permission): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return $this->getPermissionMiddleware()->hasPermission(auth()->user(), $permission);
    }

    /**
     * Check if current user has all permissions
     */
    public function hasAllPermissions(array $permissions): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return $this->getPermissionMiddleware()->hasAllPermissions(auth()->user(), $permissions);
    }

    /**
     * Check if current user has any permission
     */
    public function hasAnyPermission(array $permissions): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return $this->getPermissionMiddleware()->hasAnyPermission(auth()->user(), $permissions);
    }

    /**
     * Check if current user can access resource
     */
    public function canAccessResource(string $resource, string $action = 'access'): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return $this->getPermissionMiddleware()->canAccessResource(auth()->user(), $resource, $action);
    }

    /**
     * Check CRUD permissions for current user
     */
    public function canCRUD(string $resource, string $action): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return $this->getPermissionMiddleware()->canCRUD(auth()->user(), $resource, $action);
    }

    /**
     * Get current user's permissions
     */
    public function getUserPermissions(): array
    {
        if (!auth()->check()) {
            return [];
        }

        return $this->getPermissionMiddleware()->getUserPermissions(auth()->user());
    }

    /**
     * Get current user's accessible resources
     */
    public function getAccessibleResources(): array
    {
        if (!auth()->check()) {
            return [];
        }

        return $this->getPermissionMiddleware()->getAccessibleResources(auth()->user());
    }

    /**
     * Check if current user can manage users
     */
    public function canManageUsers(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return $this->getPermissionMiddleware()->canManageUsers(auth()->user());
    }

    /**
     * Check if current user can access dashboard
     */
    public function canAccessDashboard(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return $this->getPermissionMiddleware()->canAccessDashboard(auth()->user());
    }

    /**
     * Check if current user is a super admin
     */
    public function isSuperAdmin(): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return $this->getPermissionMiddleware()->isSuperAdmin(auth()->user());
    }

    /**
     * Get current user's transaction limits
     */
    public function getUserLimits(): array
    {
        if (!auth()->check()) {
            return [
                'deposit_limit' => 0,
                'withdrawal_limit' => 0,
                'loan_limit' => 0,
                'non_cash_limit' => 0,
            ];
        }

        return $this->getPermissionMiddleware()->getUserLimits(auth()->user());
    }

    /**
     * Check if current user can perform a transaction of a given amount
     */
    public function canPerformTransaction(string $type, float $amount): bool
    {
        if (!auth()->check()) {
            return false;
        }

        return $this->getPermissionMiddleware()->canPerformTransaction(auth()->user(), $type, $amount);
    }

    /**
     * Get current user's access profile
     */
    public function getUserProfile()
    {
        if (!auth()->check()) {
            return null;
        }

        return $this->getPermissionMiddleware()->getUserProfile(auth()->user());
    }

    /**
     * Abort if user doesn't have permission
     */
    public function authorizePermission(string $permission, string $message = 'You do not have permission to perform this action.'): void
    {
        if (!$this->hasPermission($permission)) {
            abort(403, $message);
        }
    }

    /**
     * Abort if user doesn't have any of the permissions
     */
    public function authorizeAnyPermission(array $permissions, string $message = 'You do not have permission to perform this action.'): void
    {
        if (!$this->hasAnyPermission($permissions)) {
            abort(403, $message);
        }
    }

    /**
     * Abort if user doesn't have all permissions
     */
    public function authorizeAllPermissions(array $permissions, string $message = 'You do not have permission to perform this action.'): void
    {
        if (!$this->hasAllPermissions($permissions)) {
            abort(403, $message);
        }
    }

    /**
     * Abort if user cannot access resource
     */
    public function authorizeResource(string $resource, string $action = 'access', ?string $message = null): void
    {
        if (!$this->canAccessResource($resource, $action)) {
            $message = $message ?: "You do not have permission to {$action} {$resource}.";
            abort(403, $message);
        }
    }

    /**
     * Abort if user cannot perform transaction
     */
    public function authorizeTransaction(string $type, float $amount, ?string $message = null): void
    {
        if (!$this->canPerformTransaction($type, $amount)) {
            $limits = $this->getUserLimits();
            $limitKey = strtolower($type) . '_limit';
            $limit = $limits[$limitKey] ?? 0;
            $message = $message ?: "Transaction amount exceeds your limit of " . number_format($limit, 2) . " for {$type}.";
            abort(403, $message);
        }
    }
}
