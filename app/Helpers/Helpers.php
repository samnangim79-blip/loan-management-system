<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

if (! function_exists('create_slug')) {
    /**
     * description
     *
     * @param  string  $str
     * @return string lowercase
     */
    function create_slug($string)
    {
        $t = $string;
        $specChars = [
            ' ' => '-',
            '!' => '',
            '"' => '',
            '#' => '',
            '$' => '',
            '%' => '',
            '&' => 'and',
            '\'' => '',
            '(' => '',
            ')' => '',
            '*' => '',
            '+' => '',
            ',' => '',
            '₹' => '',
            '.' => '',
            '/-' => '',
            ':' => '',
            ';' => '',
            '<' => '',
            '=' => '',
            '>' => '',
            '?' => '',
            '@' => '',
            '[' => '',
            '\\' => '',
            ']' => '',
            '^' => '',
            '_' => '',
            '`' => '',
            '{' => '',
            '|' => '',
            '}' => '',
            '~' => '',
            '-----' => '-',
            '----' => '-',
            '---' => '-',
            '/' => '',
            '--' => '-',
            '/_' => '-',
        ];
        foreach ($specChars as $k => $v) {
            $t = str_replace($k, $v, $t);
        }

        return Str::lower($t);
    }
}

// if (!function_exists('setting')) {
//   function setting($key = false, $defaultValue = false)
//   {
//     $setting = app('Setting');
//     if ($key === false) {
//       return $setting;
//     }

//     $value = $setting->get($key);

//     return $value ? $value : $defaultValue;
//   }
// }

if (! function_exists('assetUrl')) {
    function assetUrl()
    {
        $host = $_SERVER['HTTP_HOST'] ?? request()->getHost();
        $config = request()->getScheme() . '://' . $host;
        // $config .= '/public/';
        $config .= '/assets/backend/';   // use for localhost:8000 or 127.0.0.1:8000

        return $config;
    }
}

if (! function_exists('uploadUrl')) {
    function uploadUrl()
    {
        return asset('public/uploads/');
    }
}

if (! function_exists('errorImageUrl')) {
    function errorImageUrl()
    {
        // return asset('public/images/avatar3.png');
        return asset('/images/avatar3.png'); // for using localhost:8000 or 127.0.0.1:8000
    }
}

// //check trans('Key') is set or not create a key for it
// if (!function_exists('checkTrans')) {
//   function checkTrans($key = null, $replace = [], $locale = null)
//   {
//     if (is_null($key)) {
//       return app('translator');
//     } else {
//       $translation = app('translator')->get($key, $replace, $locale);
//       if ($translation === $key) {
//         // If the translation is not found, you can log it or handle it as needed
//         // For example, you can log it to a file or database
//         // Log::warning("Translation key '{$key}' not found.");
//       }
//       return $translation;
//     }
//   }
// }

// if (!function_exists('trans')) {
//   function trans($key = null, $replace = [], $locale = null)
//   {
//     if (is_null($key)) {
//       return app('translator');
//     }

//     return app('translator')->get($key, $replace, $locale);
//   }
// }

// if (!function_exists('__')) {
//   function __($key = null, $replace = [], $locale = null)
//   {
//     return trans($key, $replace, $locale);
//   }
// }

// if (!function_exists('setting')) {
//   function setting($key, $default = null)
//   {
//     return \App\Models\BusinessSetting::get($key, $default);
//   }
// }

/*
|--------------------------------------------------------------------------
| Permission Helper Functions
|--------------------------------------------------------------------------
*/

if (!function_exists('user_has_permission')) {
    /**
     * Check if current user has a specific permission
     */
    function user_has_permission(string $permission): bool
    {
        if (!Auth::check()) {
            return false;
        }
        $middleware = app(\App\Http\Middleware\PermissionMiddleware::class);
        return $middleware->hasPermission(Auth::user(), $permission);
    }
}

if (!function_exists('user_has_any_permission')) {
    /**
     * Check if current user has any of the specified permissions
     */
    function user_has_any_permission(array $permissions): bool
    {
        if (!Auth::check()) {
            return false;
        }
        $middleware = app(\App\Http\Middleware\PermissionMiddleware::class);
        return $middleware->hasAnyPermission(Auth::user(), $permissions);
    }
}

if (!function_exists('user_has_all_permissions')) {
    /**
     * Check if current user has all specified permissions
     */
    function user_has_all_permissions(array $permissions): bool
    {
        if (!Auth::check()) {
            return false;
        }
        $middleware = app(\App\Http\Middleware\PermissionMiddleware::class);
        return $middleware->hasAllPermissions(Auth::user(), $permissions);
    }
}

if (!function_exists('user_is_super_admin')) {
    /**
     * Check if current user is a super admin
     */
    function user_is_super_admin(): bool
    {
        if (!Auth::check()) {
            return false;
        }
        $middleware = app(\App\Http\Middleware\PermissionMiddleware::class);
        return $middleware->isSuperAdmin(Auth::user());
    }
}

if (!function_exists('user_can_manage_users')) {
    /**
     * Check if current user can manage users
     */
    function user_can_manage_users(): bool
    {
        if (!Auth::check()) {
            return false;
        }
        $middleware = app(\App\Http\Middleware\PermissionMiddleware::class);
        return $middleware->canManageUsers(Auth::user());
    }
}

if (!function_exists('user_permissions')) {
    /**
     * Get all permissions for current user
     */
    function user_permissions(): array
    {
        if (!Auth::check()) {
            return [];
        }
        $middleware = app(\App\Http\Middleware\PermissionMiddleware::class);
        return $middleware->getUserPermissions(Auth::user());
    }
}

if (!function_exists('user_limits')) {
    /**
     * Get transaction limits for current user
     */
    function user_limits(): array
    {
        if (!Auth::check()) {
            return [
                'deposit_limit' => 0,
                'withdrawal_limit' => 0,
                'loan_limit' => 0,
                'non_cash_limit' => 0,
            ];
        }
        $middleware = app(\App\Http\Middleware\PermissionMiddleware::class);
        return $middleware->getUserLimits(Auth::user());
    }
}

if (!function_exists('user_can_transact')) {
    /**
     * Check if current user can perform a transaction of a given amount
     */
    function user_can_transact(string $type, float $amount): bool
    {
        if (!Auth::check()) {
            return false;
        }
        $middleware = app(\App\Http\Middleware\PermissionMiddleware::class);
        return $middleware->canPerformTransaction(Auth::user(), $type, $amount);
    }
}
