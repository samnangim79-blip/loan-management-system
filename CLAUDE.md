# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Laravel 12 Loan Management System for microfinance operations with server-side DataTables, multi-language support (en/kh/zh), and social authentication.

**Stack**: Laravel 12, Bootstrap 5, jQuery 3.7, Yajra DataTables, SweetAlert2, Flatpickr, Tom Select, Vite + Tailwind 4

## Development Commands

### Essential Commands (Composer Scripts)

```bash
# Full setup (first time)
composer setup    # Installs deps, creates .env, generates key, runs migrations, builds assets

# Development server (runs 4 concurrent processes)
composer dev      # Starts: server + queue worker + pail (logs) + vite (hot reload)

# Testing
composer test     # Clears config + runs PHPUnit
php artisan test  # Run all tests
php artisan test --filter TestName  # Run specific test
```

### Manual Development Commands

```bash
# Database
php artisan migrate:fresh --seed    # Fresh migration with all seeders
php artisan db:seed --class=SpecificSeederName

# Frontend
npm run dev       # Vite development server
npm run build     # Production build

# Code Quality
vendor/bin/pint   # Laravel Pint code formatting
```

## Architecture Overview

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

### Key Architectural Concepts

**1. Database & Models**

- **NO Eloquent timestamps**: System uses manual date tracking (`created_date`, `modify_date`, `created_by`)
- **Single migration**: All tables in `2024_12_07_000000_create_loan_management_system_tables.php`
- **Mixed case columns**: Some legacy columns are UPPERCASE (`ACCOUNT_STATUS`, `OS_BAL`)
- **Foreign keys**: Must match exact column names (not Laravel conventions)
- **Decimal precision**: `decimal(20,5)` for amounts, `decimal:2` for rates

**Model Pattern** (ALL models follow this):
```php
protected $table = 'customer_infos';
protected $primaryKey = 'cust_id';
public $timestamps = false;  // CRITICAL: Never use Laravel timestamps

// Manual tracking required
$validated['created_by'] = auth()->id() ?? 1;
$validated['created_date'] = now();

// Status/type constants
const STATUS_ACTIVE = 1;

// Casts
protected $casts = [
    'dob' => 'date',
    'amount' => 'decimal:5',
];
```

**2. Permission System**

- Uses `AccessProfile` and `AccessProfileDetail` for module-based permissions
- Transaction limits per profile (deposit/withdrawal/loan/non_cash)
- Available via `HasPermissions` trait in controllers
- Helper functions: `user_has_permission()`, `user_limits()`, `user_can_transact()`

```php
// In controllers
if (!$this->canAccessResource('customers', 'create')) {
    return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
}

$this->authorizeTransaction('deposit', $amount);
```

**3. DataTables Pattern**

Every resource index needs **paired routes**:

```php
// routes/web.php
Route::prefix('customers')->group(function () {
    Route::get('/', [CustomerController::class, 'index']);         // Returns view
    Route::get('/data', [CustomerController::class, 'getData']);   // DataTable JSON
});

// Controller
public function getData() {
    return DataTables::of(Customer::with('village'))
        ->addColumn('action', fn($row) => '<button data-id="'.$row->cust_id.'">...</button>')
        ->rawColumns(['action', 'contact'])
        ->make(true);
}
```

**4. AJAX Response Convention**

```php
// Success
return response()->json([
    'success' => true,
    'message' => 'Customer created successfully',
    'data' => $customer
], 201);

// Error
return response()->json([
    'success' => false,
    'message' => $e->getMessage()
], 500);
```

**5. Database Transactions**

Always wrap multi-step operations:

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

### Key Entities & Relationships

**Core Entities**:
- `Customer`: PK `cust_id`, dual-lang (`name_en`/`name_kh`), linked to `Village`
- `AccountInfo`: PK `acct_id`, status constants (`STATUS_ACTIVE=1`), tracks loan/savings accounts
- `Trans`: PK `tran_id`, type constants (`TYPE_DEPOSIT=1`, `TYPE_LOAN_PAYMENT=5`), central transaction log
- `LoanSchedule`: PK `loan_schedule_id`, lifecycle tracking with collateral relations

**Location Hierarchy**: `Country → Province → District → Commune → Village`

**GL Structure**: 4-level chart of accounts `GlL1 → GlL2 → GlL3 → GlL4 → Gl`

**Access Control**: `Module ← AccessProfileDetail → AccessProfile → UserLogin`

### Multi-Language System

**Supported Languages**: English (en), Khmer (kh), Chinese (zh)

**Database**: Dual-language columns (`name_en`/`name_kh`, `province`/`province_kh`)

**Translation Files**: `lang/{locale}/common.php`

**Key Components**:
- `SetLocale` middleware: Auto-applies language from session
- `LanguageController`: Handles language switching
- Helper: `js_translations(['common.actions.save'])` - exports to JS
- Helper: `current_language()` - returns current language data

**Translation Management**:
- Admin interface at `/translation-keys`
- Auto-translation using `stichoza/google-translate-php`
- Import/export from/to lang files

**Usage in Blade**:
```php
{{ __('common.nav.dashboard') }}
{{ __('common.messages.welcome', ['name' => $user->name]) }}
```

**Usage in JavaScript**:
```javascript
const translations = {!! js_translations(['common.actions.save']) !!};
```

### Frontend Architecture

**UI Libraries**:
- **Forms**: Flatpickr (dates), Tom Select (searchable selects)
- **Feedback**: SweetAlert2 (confirmations), Toastr (notifications)
- **Tables**: DataTables with Bootstrap 5 theme

**AJAX API Endpoints** (`routes/web.php`):
```php
Route::prefix('api')->group(function () {
    Route::get('/villages/search', ...);   // Tom Select dropdowns
    Route::get('/accounts/search', ...);   // Autocomplete fields
    Route::get('/customers/{id}', ...);    // Single record fetch
});
```

**Asset Helpers**:
- `assetUrl()`: Returns `/assets/backend/` path
- `uploadUrl()`: Returns `public/uploads/` path
- `errorImageUrl()`: Fallback avatar image

### File Structure

**Auto-loaded Helpers**:
- `app/Helpers/Helpers.php`: General utility functions
- `app/Helpers/LanguageHelpers.php`: Translation helpers

**Traits**:
- `app/Traits/HasPermissions.php`: Permission checking methods
- `app/Traits/HasTranslations.php`: MorphMany relations for translations

**Key Directories**:
- `app/Http/Controllers/`: All controllers (Customer, Loan, Transaction, etc.)
- `app/Models/`: All models (80+ models for complete system)
- `app/Services/`: Business logic (TranslationService, etc.)
- `database/seeders/`: Comprehensive test data seeders
- `resources/views/`: Blade templates organized by module
- `routes/web.php`: All routes (guest auth, resources, API endpoints)

## Critical Constraints

1. **NO Eloquent timestamps**: Always manually set `created_date`/`modify_date`/`created_by`
2. **Single migration**: All tables in `2024_12_07_000000_create_loan_management_system_tables.php`
3. **Foreign keys**: Must match exact column names in relationships
4. **Status/type values**: Use model constants (`AccountInfo::STATUS_ACTIVE`), never hardcode
5. **Decimal precision**: `decimal(20,5)` for amounts, `decimal:2` for rates
6. **DataTable endpoints**: Every index needs paired `/data` route returning `DataTables::of()->make(true)`
7. **AJAX responses**: Always return `{ success: bool, message: string, data?: any }`
8. **Mixed case columns**: Some are UPPERCASE (legacy), query carefully
9. **Transaction limits**: Always validate against user's access profile limits
10. **Validation**: Use `exists` rule for foreign keys, proper decimal validation

## Testing Credentials

**Admin Users**:
- admin@loanmgt.com / admin123
- demo@loanmgt.com / demo123

**Role-based Users**:
- jmanager@loanmgt.com / password123 (Manager)
- sloan@loanmgt.com / password123 (Loan Officer)
- mteller@loanmgt.com / password123 (Teller)

**Legacy System Users**:
- jmanager / password123
- dsupervisor / password123
- lcashier / password123

## Seeder System

Comprehensive seeders create realistic test data:
- 10 Laravel users, 8 staff members, 5 branches
- Complete GL hierarchy (4 levels)
- 5 customers, 6 accounts, 6 loan contracts
- 35 transactions across 12 types
- Geographic data (provinces, districts, communes, villages)
- Fixed deposits, collateral, cash management records

See `SEEDER_DOCUMENTATION.md` for detailed statistics and usage.

## Common Patterns

### Validation Example
```php
$validated = $request->validate([
    'name_en' => 'required|string|max:255',
    'village_id' => 'required|exists:villages,id',
    'amount' => 'required|numeric|decimal:5',
    'interest_rate' => 'required|numeric|decimal:2',
]);
```

### DataTables with Relationships
```php
public function getData() {
    $query = Customer::with(['village.commune.district.province']);

    return DataTables::of($query)
        ->addColumn('location', fn($row) => $row->village->commune->district->province->province)
        ->addColumn('action', function($row) {
            return view('customers.actions', compact('row'))->render();
        })
        ->rawColumns(['action'])
        ->make(true);
}
```

### Creating Records with Manual Timestamps
```php
$data = [
    'name_en' => $validated['name_en'],
    'created_by' => auth()->id() ?? 1,
    'created_date' => now(),
];
Customer::create($data);
```

## Additional Documentation

- `MULTILINGUAL.md`: Detailed multi-language system documentation
- `SEEDER_DOCUMENTATION.md`: Database seeder details and test data
- `GROUP_SEEDER_USAGE.md`: Group/loan management seeder usage
- `TRANSLATION_KEYS_DOCUMENTATION.md`: Translation management system
- `.github/copilot-instructions.md`: Complete AI coding guidelines
