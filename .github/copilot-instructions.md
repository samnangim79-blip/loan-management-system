# Loan Management System - AI Coding Guidelines

## Project Overview

Laravel 12 Loan Management System for microfinance operations with server-side DataTables, multi-language support (en/kh/zh), and social authentication. Stack: Laravel 12, Bootstrap 5, jQuery 3.7, Yajra DataTables, SweetAlert2, Flatpickr, Tom Select, Vite + Tailwind 4.

## Architecture

### Domain Model Flow

```
Customer (customer_infos)
    ↓ cust_id
AccountInfo (account_infos) ← Trans (trans: central txn log)
    ↓ acct_id              ↓ branch_id/tran_type
LoanSchedule               Branch
    ↓                      AccessProfile → UserLogin
Collateral                    ↓ profile_id
                          AccessProfileDetail → Module
```

**Key entities:**

-   `Customer`: PK `cust_id`, dual-lang (`name_en`/`name_kh`), linked to `Village`
-   `AccountInfo`: PK `acct_id`, status constants (`STATUS_ACTIVE=1`), tracks loan/savings accounts
-   `Trans`: PK `tran_id`, type constants (`TYPE_DEPOSIT=1`, `TYPE_LOAN_PAYMENT=5`), central transaction log
-   `LoanSchedule`: PK `loan_schedule_id`, lifecycle tracking with collateral relations
-   **Location hierarchy**: `Country → Province → District → Commune → Village`
-   **GL structure**: 4-level chart of accounts `GlL1 → GlL2 → GlL3 → GlL4 → Gl`

### Critical Model Conventions

```php
// ALL models follow this pattern:
protected $table = 'customer_infos';
protected $primaryKey = 'cust_id';
public $timestamps = false;  // ⚠️ NEVER use Laravel timestamps

// Manual date tracking required:
$validated['created_by'] = auth()->id() ?? 1;
$validated['created_date'] = now();

// Status/type via class constants:
const STATUS_ACTIVE = 1;
const TYPE_DEPOSIT = 1;

// Foreign keys match EXACT column names:
return $this->belongsTo(Village::class, 'village_id', 'id');
```

**Mixed naming**: Tables use `snake_case` (`customer_infos`) but some columns are `UPPERCASE` (`ACCOUNT_STATUS`, `OS_BAL`).

## Development Workflow

### Commands (defined in composer.json)

```bash
composer setup    # Full install: deps + .env + key + migrate + npm install/build
composer dev      # Concurrent: server + queue + logs (pail) + vite (hot reload)
composer test     # config:clear + phpunit
```

**Dev server stack runs concurrently:**

-   `php artisan serve` (web server)
-   `php artisan queue:listen --tries=1` (queue worker)
-   `php artisan pail --timeout=0` (log viewer)
-   `npm run dev` (Vite HMR)

### Frontend Architecture

**DataTables pattern** (see `CustomerController`, `LocationController`):

```php
// routes/web.php - every resource needs /data endpoint
Route::prefix('customers')->group(function () {
    Route::get('/', [CustomerController::class, 'index']);  // Returns view
    Route::get('/data', [CustomerController::class, 'getData']);  // DataTable JSON
    // ... standard CRUD
});

// Controller implementation:
public function getData() {
    return DataTables::of(Customer::with('village'))
        ->addColumn('action', fn($row) => '<button data-id="'.$row->cust_id.'">...</button>')
        ->rawColumns(['action', 'contact'])  // Allow HTML in these columns
        ->make(true);
}
```

**AJAX API endpoints** (`routes/web.php:441-457`):

```php
Route::prefix('api')->group(function () {
    Route::get('/villages/search', ...);  // Tom Select dropdowns
    Route::get('/accounts/search', ...);  // Autocomplete fields
    Route::get('/customers/{id}', ...);   // Single record fetch
});
```

**JSON response convention:**

```php
// Success (201/200):
return response()->json([
    'success' => true,
    'message' => 'Customer created successfully',
    'data' => $customer
]);

// Error (400/500):
return response()->json([
    'success' => false,
    'message' => $e->getMessage()
], 500);
```

**UI libraries:**

-   **Forms**: Flatpickr (dates), Tom Select (searchable selects)
-   **Feedback**: SweetAlert2 (confirmations), Toastr (notifications)
-   **Tables**: DataTables with Bootstrap 5 theme (`datatables.net-bs5`)

## Code Patterns

### Database Transactions (see `CustomerController`, `LoanController`)

```php
use Illuminate\Support\Facades\DB;

DB::beginTransaction();
try {
    $customer = Customer::create($validated);
    $account = AccountInfo::create([...]);
    DB::commit();
    return response()->json(['success' => true, ...]);
} catch (\Exception $e) {
    DB::rollback();
    return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
}
```

### Auth Handling

```php
// Controllers use fallback for dev (no auth guard required):
$validated['created_by'] = auth()->id() ?? 1;

// Permission checking via HasPermissions trait:
if (!$this->canAccessResource('customers', 'create')) {
    return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
}

// Access profile module check:
$profile->hasModule('loan_management')  // Returns bool
```

### Validation & Data Integrity

```php
// Always validate foreign keys with exists rule:
'village_id' => 'required|exists:villages,id',
'nationality_id' => 'nullable|exists:nationalitys,nationality_id',

// Decimal precision for money:
'amount' => 'required|numeric|decimal:5',  // Stored as decimal(20,5)
'interest_rate' => 'required|numeric|decimal:2',

// Date casting in models:
protected $casts = [
    'dob' => 'date',
    'created_date' => 'date',
    'amount' => 'decimal:5',
];
```

## Key Files Reference

| Purpose               | Location                                                                         | Notes                                           |
| --------------------- | -------------------------------------------------------------------------------- | ----------------------------------------------- |
| **Global helpers**    | `app/Helpers/Helpers.php`                                                        | Auto-loaded via `composer.json` `files`         |
| **Language helpers**  | `app/Helpers/LanguageHelpers.php`                                                | Auto-loaded, `js_translations()` for frontend   |
| **Translation trait** | `app/Traits/HasTranslations.php`                                                 | MorphMany relations, auto-cache clearing        |
| **Permission trait**  | `app/Traits/HasPermissions.php`                                                  | `canAccessResource()`, `hasPermission()`        |
| **All schema**        | `database/migrations/2024_12_07_000000_create_loan_management_system_tables.php` | Single migration for entire system              |
| **Routes**            | `routes/web.php`                                                                 | Guest (auth), standard resources, API endpoints |

## Localization

-   **DB columns**: `name_en`/`name_kh`, `province`/`province_kh` (dual-language storage)
-   **Locales**: `en` (default), `kh` (Khmer), `zh` (Chinese) in `lang/` directory
-   **Translation service**: `app/Services/TranslationService.php` with caching
-   **Helpers**: `TranslationHelper::getLocaleName($model, $field)` gets localized value
-   **Frontend**: `js_translations()` helper in `LanguageHelpers.php` exports to JS

## Critical Constraints

1. **NO Eloquent timestamps**: Always manually set `created_date`/`modify_date`/`created_by`
2. **Single migration**: All tables in `2024_12_07_000000_create_loan_management_system_tables.php`
3. **Foreign keys**: Must match exact column names in relationships (not Laravel conventions)
4. **Status/type values**: Use model constants (`AccountInfo::STATUS_ACTIVE`), never hardcode integers
5. **Decimal precision**: `decimal(20,5)` for amounts, `decimal:2` for rates/percentages
6. **DataTable endpoints**: Every index needs paired `/data` route returning `DataTables::of()->make(true)`
7. **AJAX responses**: Always return `{ success: bool, message: string, data?: any }`
8. **Mixed case columns**: Some are `UPPERCASE` (legacy), query carefully
