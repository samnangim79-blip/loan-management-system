<?php

namespace App\Providers;

use App\Http\Middleware\PermissionMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Telegram\Provider as TelegramProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the PermissionMiddleware as a singleton
        $this->app->singleton(PermissionMiddleware::class, function ($app) {
            return new PermissionMiddleware();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Telegram Socialite provider
        $this->app['events']->listen(SocialiteWasCalled::class, function (SocialiteWasCalled $event) {
            $event->extendSocialite('telegram', TelegramProvider::class);
        });

        // Register Blade directives for permissions
        $this->registerBladeDirectives();
    }

    /**
     * Register custom Blade directives for permission checks
     */
    protected function registerBladeDirectives(): void
    {
        // @can('permission_name')
        Blade::if('permission', function (string $permission) {
            if (!auth()->check()) {
                return false;
            }
            $middleware = app(PermissionMiddleware::class);
            return $middleware->hasPermission(auth()->user(), $permission);
        });

        // @canany(['permission1', 'permission2'])
        Blade::if('anypermission', function (array $permissions) {
            if (!auth()->check()) {
                return false;
            }
            $middleware = app(PermissionMiddleware::class);
            return $middleware->hasAnyPermission(auth()->user(), $permissions);
        });

        // @canall(['permission1', 'permission2'])
        Blade::if('allpermissions', function (array $permissions) {
            if (!auth()->check()) {
                return false;
            }
            $middleware = app(PermissionMiddleware::class);
            return $middleware->hasAllPermissions(auth()->user(), $permissions);
        });

        // @superadmin
        Blade::if('superadmin', function () {
            if (!auth()->check()) {
                return false;
            }
            $middleware = app(PermissionMiddleware::class);
            return $middleware->isSuperAdmin(auth()->user());
        });

        // @canmanageusers
        Blade::if('canmanageusers', function () {
            if (!auth()->check()) {
                return false;
            }
            $middleware = app(PermissionMiddleware::class);
            return $middleware->canManageUsers(auth()->user());
        });

        // @canaccess('resource', 'action')
        Blade::if('canaccess', function (string $resource, string $action = 'access') {
            if (!auth()->check()) {
                return false;
            }
            $middleware = app(PermissionMiddleware::class);
            return $middleware->canAccessResource(auth()->user(), $resource, $action);
        });
    }
}
