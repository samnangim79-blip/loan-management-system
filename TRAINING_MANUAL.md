# មេរៀនប្រើប្រាស់ប្រព័ន្ធ — Loan Management System
# (Training Manual)

> **Repository:** [`samnangim79-blip/loan-management-system`](https://github.com/samnangim79-blip/loan-management-system)
> **Canonical migration:** `database/migrations/2024_12_07_000000_create_loan_management_system_tables.php` (99 tables)
> **Language:** This document mixes Khmer (primary) with English technical terms to match the codebase.

---

## មាតិកា (Table of Contents)

1. [ទិដ្ឋភាពទូទៅ (Project Overview)](#part-1-overview)
2. [ការដំឡើង (Installation & Setup)](#part-2-installation)
3. [ស្ថាបត្យកម្ម (Architecture)](#part-3-architecture)
4. [Modules — Menu Walkthrough](#part-4-modules)
5. [User Roles & Permissions](#part-5-permissions)
6. [Multi-Language System](#part-6-i18n)
7. [Developer Reference](#part-7-dev)
8. [Common Pitfalls (Audit Findings)](#part-8-pitfalls)
9. [Testing & Verification](#part-9-testing)
10. [Troubleshooting](#part-10-troubleshooting)

---

<a id="part-1-overview"></a>
# ផ្នែកទី ១ — ទិដ្ឋភាពទូទៅ

## ១.១ អ្វីជា Project នេះ?

**Loan Management System (LMS)** គឺជា Web Application មួយដែលប្រើ Laravel Framework
សម្រាប់គ្រប់គ្រងប្រតិបត្តិការ **Microfinance** ឱ្យពេញលេញ៖

- ការគ្រប់គ្រងព័ត៌មានអតិថិជន (Customer Profile)
- ការបើកគណនី (Account Opening) — Savings, Current, Loan, Fixed Deposit
- ការផ្ដល់ប្រាក់កម្ចី (Loan Disbursement) និងតារាងសងបំណុល (Amortization Schedule)
- ប្រតិបត្តិការសាច់ប្រាក់ (Cash Transactions) — ដាក់, ដក, ផ្ទេរ
- ការតាមដាន **Collateral** (ទ្រព្យធានា)
- ការតាមដាន **Arrears** (បំណុលហួសកាល)
- **Fixed Deposit Certificates** និងការ Rollover
- **General Ledger** ៤ កម្រិត (Chart of Accounts)
- **Passbook** និង **Cheque** management
- របាយការណ៍ហិរញ្ញវត្ថុ (Daily Cash, Trial Balance, Loan Aging, ...)
- ការគ្រប់គ្រងសិទ្ធិ (RBAC) និងព្រំដែនប្រតិបត្តិការ (Transaction Limits)
- ការគាំទ្រពហុភាសា (ខ្មែរ / អង់គ្លេស / ចិន)

## ១.២ បច្ចេកវិទ្យាដែលប្រើ (Tech Stack)

| ផ្នែក | បច្ចេកវិទ្យា |
| --- | --- |
| Backend Framework | **Laravel 12** (PHP 8.2+) |
| Database | **SQLite** (default for dev) ឬ **MySQL 8.x** |
| Frontend CSS | **Bootstrap 5.3** + **Tailwind CSS 4** |
| Frontend JS | **jQuery 3.7** (primary) |
| Build Tool | **Vite 7** |
| Tables | **Yajra DataTables 12** (server-side rendering) |
| Forms — Date | **Flatpickr** |
| Forms — Search Select | **Tom Select** |
| Notifications | **SweetAlert2**, **Toastr** |
| Icons | **Font Awesome 6** |
| Auth | Native Laravel Auth + **Laravel Socialite** (Google, Facebook, Telegram) |
| Translation | **stichoza/google-translate-php** |

## ១.៣ រចនាសម្ព័ន្ធ Project (Folder Structure)

```
loan-management-system/
├── app/
│   ├── Helpers/
│   │   ├── Helpers.php              # create_slug, assetUrl, user_has_permission, ...
│   │   ├── LanguageHelpers.php      # js_translations, current_language, ...
│   │   └── TranslationHelper.php
│   ├── Http/
│   │   ├── Controllers/             # 27 controllers (see §4)
│   │   │   └── Auth/                # AuthController, SocialAuthController
│   │   └── Middleware/
│   │       ├── SetLocale.php        # Switches language by session
│   │       └── PermissionMiddleware.php
│   └── Models/                      # 96 Eloquent models (see §3.3)
│
├── database/
│   ├── migrations/
│   │   └── 2024_12_07_000000_create_loan_management_system_tables.php
│   │                                # The ONLY migration — 99 tables
│   └── seeders/                     # 32 seeders (DatabaseSeeder → 30 child seeders)
│
├── resources/
│   ├── lang/
│   │   ├── en/ kh/ zh/              # common.php, auth.php, pagination.php, ...
│   └── views/
│       ├── admin/layouts/           # admin_layout, top_header, left_sidebar
│       ├── auth/                    # login, register, forgot-password
│       ├── customers/ loans/ accounts/ transactions/ ...
│       └── dashboard.blade.php
│
├── routes/
│   └── web.php                      # All routes (incl. api/* group)
│
├── public/
│   └── assets/backend/              # CSS, JS, images
│
├── docs/
│   └── AUDIT_REPORT_2024_12_07_MIGRATION.md
│
├── composer.json
├── package.json
├── CLAUDE.md                        # Guidance for AI assistants
├── README.md
└── TRAINING_MANUAL.md               # This file
```

---

<a id="part-2-installation"></a>
# ផ្នែកទី ២ — ការដំឡើង (Installation)

## ២.១ តម្រូវការមុនដំឡើង

- **PHP 8.2** ឬខ្ពស់ជាង (សម្គាល់ក្នុង `composer.json`)
- **Composer 2.x**
- **Node.js 18+** និង **npm**
- ​Database មួយក្នុង៖
  - **SQLite** (សាមញ្ញ — សម្រាប់ dev/test)
  - **MySQL 8.x** (សម្រាប់ production)

## ២.២ ការដំឡើងពេញ​មួយជំហ៊ាន (Quick Setup)

```bash
# 1. Clone repository
git clone https://github.com/samnangim79-blip/loan-management-system.git
cd loan-management-system

# 2. ដំឡើងពេញ (composer script ដែលធ្វើគ្រប់ជំហានទាំងអស់)
composer setup
```

`composer setup` ធ្វើ:
1. `composer install` — ដំឡើង PHP dependencies
2. បង្កើត `.env` ប្រសិនបើមិនទាន់មាន
3. `php artisan key:generate` — បង្កើត `APP_KEY`
4. `php artisan migrate --force` — បង្កើតតារាងទាំងអស់
5. `npm install` + `npm run build` — Build frontend assets

## ២.៣ ការដំឡើងតាមជំហានដោយដៃ

```bash
# PHP dependencies
composer install

# Environment
cp .env.example .env
php artisan key:generate

# Choose database driver in .env:
#   DB_CONNECTION=sqlite    (default)
#   DB_CONNECTION=mysql     (also set DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD)

# សម្រាប់ SQLite: បង្កើតឯកសារ database
touch database/database.sqlite

# Migrate + seed
php artisan migrate:fresh --seed --force

# Frontend assets
npm install
npm run build
```

## ២.៤ ការដំណើរការ Development Server

```bash
# Method 1: 4 services running in parallel (recommended)
composer dev
# ↑ Runs: php artisan serve + queue:listen + pail + npm run dev

# Method 2: ដោយដៃ
php artisan serve          # Backend at http://localhost:8000
npm run dev                # Vite hot-reload
```

## ២.៥ Test Users (from `UserSeeder.php`)

មាន **10 users** ដែលត្រូវបាន seed មកស្រាប់៖

| Username | Email | Password | តួនាទី |
| --- | --- | --- | --- |
| `admin` | admin@loanmgt.com | `admin123` | Super Admin |
| `demo` | demo@loanmgt.com | `demo123` | Demo / អ្នកប្រើទូទៅ |
| `jmanager` | jmanager@loanmgt.com | `password123` | Branch Manager |
| `sloan` | sloan@loanmgt.com | `password123` | Loan Officer |
| `mteller` | mteller@loanmgt.com | `password123` | Teller |
| `lcashier` | lcashier@loanmgt.com | `password123` | Cashier |
| `dsupervisor` | dsupervisor@loanmgt.com | `password123` | Supervisor |
| `eanalyst` | eanalyst@loanmgt.com | `password123` | Analyst |
| `radmin` | radmin@loanmgt.com | `password123` | Regional Admin |
| `jcustomer` | jcustomer@loanmgt.com | `password123` | Customer |

> **សូមផ្លាស់ប្ដូរ passwords ទាំងនេះក្នុង production!**

---

<a id="part-3-architecture"></a>
# ផ្នែកទី ៣ — ស្ថាបត្យកម្ម (Architecture)

## ៣.១ លំហូរ Domain Model

```
Customer (customer_infos, PK=cust_id)
   │
   ├──► AccountInfo (account_infos, PK=acct_id) ──► AccountType ──► Currency
   │       │
   │       ├──► LoanSchedule (PK=loan_schedule_id)
   │       │       │
   │       │       ├──► Collateral ──► CollateralType
   │       │       ├──► LoanArrear, LoanArrearDetail, LoanArrearPayDetail
   │       │       └──► LoanTran ──► LoanTranType
   │       │
   │       ├──► FdCert (Fixed Deposit) ──► FdTerm, FdOption, FdTran
   │       ├──► Passbook ──► PassbookIssue, PassbookMaintenance
   │       └──► ChequeIssue ──► ChequeClear, ChequeStop, ChequeMaintenance
   │
   ├──► CustPhoto, CustAcctHold, CustIncomeHistory
   └──► Group (GroupDetail) — for Group Lending

Trans (trans, PK=tran_id)               ← Central transaction log
   │
   ├──► User (created_by)
   ├──► Branch
   ├──► GlMap ──► Gl ──► GlL4 ──► GlL3 ──► GlL2 ──► GlL1
   └──► AccountInfo

UserLogin ──► AccessProfile ──► AccessProfileDetail ──► Module
                  │
                  └──► transaction limits (deposit, withdrawal, loan, non_cash)
```

## ៣.២ ច្បាប់ស្ថាបត្យកម្មសំខាន់ៗ (Critical Conventions)

### ច្បាប់ទី១ — គ្មាន Eloquent Timestamps

ប្រព័ន្ធនេះ **មិនប្រើ** `created_at` / `updated_at` របស់ Laravel ឡើយ។
រាល់ Model ត្រូវកំណត់៖

```php
class Customer extends Model
{
    protected $table = 'customer_infos';
    protected $primaryKey = 'cust_id';
    public $timestamps = false;       // ← CRITICAL!
    // ...
}
```

**ករណីលើកលែង** (តារាងដែលប្រើ `$table->timestamps()` ក្នុង migration):
- `users`, `provinces`, `districts`, `communes`, `villages`

រាល់​​ករណីផ្សេងទៀត — ត្រូវកំណត់ដោយដៃ:

```php
$data = [
    'name_en'      => $validated['name_en'],
    'created_by'   => auth()->id() ?? 1,
    'created_date' => now(),       // Use created_date, NOT created_at
];
Customer::create($data);
```

### ច្បាប់ទី២ — Migration តែមួយ

តារាង **ទាំងអស់ ៩៩** ស្ថិតក្នុង file តែមួយ:

```
database/migrations/2024_12_07_000000_create_loan_management_system_tables.php
```

**មិនត្រូវ** បង្កើត migration ថ្មីដាច់ដោយឡែកឡើយ — ត្រូវកែប្រែ migration មេនេះ
ដោយផ្ទាល់ (បន្ទាប់មក `migrate:fresh --seed`)។

### ច្បាប់ទី៣ — ឈ្មោះ Column ច្រឡំ Case

តារាងខ្លះប្រើ column ​​ឈ្មោះជា lowercase (modern), ខ្លះប្រើ UPPERCASE (legacy)។
**ត្រូវយោងតាម migration ឱ្យបាន​ប្រាកដ**:

| Pattern | Examples |
| --- | --- |
| Modern lowercase | `cust_id`, `acct_id`, `loan_schedule_id`, `name_en`, `name_kh` |
| Legacy mixed-case | `DR_CR`, `OS_BAL`, `ACCOUNT_STATUS` (e.g. `cust_acct_trans`) |

ត្រូវយោងតាម migration ដើម្បីដឹង column ឈ្មោះ​ពិត, ហើយប្រើតែឈ្មោះ​ដែលត្រូវនឹង migration ប៉ុណ្ណោះក្នុង:
- Models (`$fillable`, attribute access)
- Controllers (validation rules, `where()`, `select()`)
- Views (JavaScript DataTable `data:`, form `name="..."`)

> ⚠️ **Linux/SQLite គឺ case-sensitive** — `$customer->NAME_EN` នឹង return `null`
> បើ​ column ឈ្មោះ ​ពិតគឺ `name_en`។ MySQL ​លើ Windows ប្រហែលជា​​ tolerant ប៉ុន្តែវានឹង​ break
> នៅពេល deploy។

### ច្បាប់ទី៤ — Foreign Keys

Foreign key column ត្រូវប្រើឈ្មោះពិតពី migration មិនមែន convention របស់ Laravel ទេ៖

```php
// ❌ INCORRECT
$customer->village();   // ← Laravel will look for village_id by convention

// ✅ CORRECT (explicit FK + owner key)
public function village()
{
    return $this->belongsTo(Village::class, 'village_id', 'id');
}
```

### ច្បាប់ទី៥ — Decimal Precision

| Value | Precision | Migration | Cast |
| --- | --- | --- | --- |
| Amounts (ចំនួនប្រាក់) | `decimal(20,5)` | `$table->decimal('amount', 20, 5)` | `'amount' => 'decimal:5'` |
| Rates (អត្រាការប្រាក់) | `decimal(5,2)` ឬ `double(5,2)` | `$table->double('rate', 5, 2)` | `'rate' => 'decimal:2'` |

### ច្បាប់ទី៦ — DataTable Routes ជាគូ

រាល់ resource index ត្រូវមាន **២ routes ជាគូ**:

```php
Route::prefix('customers')->group(function () {
    Route::get('/',     [CustomerController::class, 'index']);    // → returns view
    Route::get('/data', [CustomerController::class, 'getData']);  // → returns JSON
});
```

នៅក្នុង Blade view, JavaScript DataTable ហៅ `/customers/data` ហើយ `data:` / `name:`
**ត្រូវប្រើ lowercase ដូចគ្នានឹង database column**:

```js
columns: [
    { data: 'cust_id',   name: 'cust_id' },     // ✅ Match migration
    { data: 'name_en',   name: 'name_en' },     // ✅
    { data: 'CUST_ID',   name: 'CUST_ID' },     // ❌ WRONG
]
```

### ច្បាប់ទី៧ — AJAX Response Convention

**រាល់** AJAX endpoint ត្រលប់​​ structure ខាងក្រោម:

```php
// Success
return response()->json([
    'success' => true,
    'message' => 'Customer created successfully',
    'data'    => $customer,
], 201);

// Error
return response()->json([
    'success' => false,
    'message' => $e->getMessage(),
], 500);
```

JavaScript handler:

```js
$.ajax({
    url: '/customers',
    type: 'POST',
    data: $('#customerForm').serialize(),
    success: function(response) {
        if (response.success) {
            toastr.success(response.message);
            $('#customerModal').modal('hide');
            table.ajax.reload();
        } else {
            toastr.error(response.message);
        }
    }
});
```

### ច្បាប់ទី៨ — Database Transactions

ប្រតិបត្តិការច្រើនជំហានទាំងអស់ត្រូវ wrap ក្នុង `DB::transaction()`:

```php
use Illuminate\Support\Facades\DB;

DB::beginTransaction();
try {
    $customer = Customer::create($validated);
    $account  = AccountInfo::create([
        'cust_id'      => $customer->cust_id,
        'acct_type_id' => 1,
        'created_by'   => auth()->id() ?? 1,
        'created_date' => now(),
    ]);
    DB::commit();
    return response()->json(['success' => true, 'data' => $account], 201);
} catch (\Exception $e) {
    DB::rollback();
    return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
}
```

## ៣.៣ បញ្ជី Models ទាំងអស់ (96 models)

```
Authentication & Users
  User, UserLogin, SocialAccount, Staff, Branch

Access Control
  AccessProfile, AccessProfileDetail, Module

Customer Domain
  Customer, CustPhoto, CustAcctHold, CustAcctTran, CustAcctTranTmp,
  CustAssetDetail, CustIncomeHistory, CustUnclear

Account Domain
  AccountInfo, AccountType, AccountPhoto, OdLimit, JointAccountHolder, SaCa

Loan Domain
  LoanSchedule, LoanScheduleTmp,
  LoanCustomSchedule, LoanCustomScheduleDetail,
  LoanCustomScheduleTmp, LoanCustomScheduleDetailTmp,
  LoanArrear, LoanArrearDetail, LoanArrearPayDetail,
  LoanTran, LoanTranType,
  PurposeLoan, PaymentFrequency

Collateral Domain
  Collateral, CollateralDetail, CollateralRelease,
  CollateralTmp, CollateralDetailTmp, CollateralReleaseTmp,
  CollateralType, CollateralTypeDetail

Group Lending
  Group, GroupDetail, GroupTmp, GroupDetailTmp

Fixed Deposit
  FdCert, FdTerm, FdOption, FdTran, FdRollOver, FdFutureDep

Cheque & Passbook
  ChequeIssue, ChequeIssued, ChequeClear, ChequeCleared,
  ChequeStop, ChequeStatus, ChequeMaintenance,
  Passbook, PassbookIssue, PassbookMaintenance

Transactions / Cash
  Trans, TranTmp, TranDetail, TranDetailTmp, TranDate,
  CashMgt, PenddingCashTransfer, BranchTran

Fixed Assets
  FixedAsset, FixedAssetType, FixedAssetDepre, AssetType

General Ledger
  Gl, GlL1, GlL2, GlL3, GlL4, GlMap

Geography (location hierarchy)
  Country, Province, District, Commune, Village

Reference / Lookup
  Currency, ExRateHistory, Nationality, Language, TranslationKey,
  Config, IntRate, AccruedInt, NonWorkingDay, PublicHoliday
```

---

<a id="part-4-modules"></a>
# ផ្នែកទី ៤ — Modules ទាំងអស់ (User Guide)

តួនាទីនីមួយ​ៗ ​មាន​សិទ្ធិ​ខុសៗ​គ្នា ​(សូម​មើល​ §៥)។
ខាងក្រោម​ជា​​ menu structure ពេញ (ពី `resources/views/admin/layouts/partials/left_sidebar.blade.php`):

```
🏠 Dashboard                                          /dashboard

👥 Customers                                          /customers
    ├── Customer List                                 /customers
    └── Add Customer                                  /customers/create

💳 Accounts                                           /accounts
    ├── Account List                                  /accounts
    └── Open Account                                  /accounts/create

💰 Loans                                              /loans
    ├── Loan List                                     /loans
    ├── Loan Statistics                               /loans/statistics
    └── New Loan / Disbursement                       /loans/create

🛡️  Collaterals                                       /collaterals
    ├── Collateral List                               /collaterals
    └── Collateral Summary                            /collaterals/summary

💵 Transactions                                       /transactions
    ├── Transaction List                              /transactions
    ├── Cash Management                               /cash
    └── Cash Transfers                                /cash/transfers

🏦 Fixed Deposits                                     /fixed-deposits
    ├── FD Certificates                               /fixed-deposits
    ├── FD Terms                                      /fixed-deposits/terms
    └── FD Options                                    /fixed-deposits/options

📒 Cheques & Passbooks
    ├── Cheque List                                   /cheques
    ├── Cheque Clearing                               /cheques/clearing
    ├── Stop Cheques                                  /cheques/stops
    ├── Cheque Maintenance                            /cheques/maintenance
    ├── Passbook Issue                                /passbooks
    ├── Passbook List                                 /passbooks/list
    └── Passbook Maintenance                          /passbooks/maintenance

👥 Groups                                             /groups

📈 Interest                                           /interest
    ├── Interest Rates                                /interest/rates
    └── Accrued Interest                              /interest/accrued

📊 Reports                                            /reports
    ├── Daily Cash                                    /reports/daily
    ├── Loan Aging                                    /reports/loans
    ├── Loan Summary                                  /reports/loan-summary
    ├── Loan Arrears                                  /reports/loan-arrears
    ├── Disbursements                                 /reports/disbursements
    ├── Customer Report                               /reports/customers
    ├── Account Report                                /reports/accounts
    ├── Transaction Report                            /reports/transactions
    └── Trial Balance                                 /reports/trial-balance

📚 General Ledger                                     /gl
    ├── GL Accounts                                   /gl
    └── GL Tree (Chart of Accounts)                   /gl/tree

🏢 Fixed Assets                                       /fixed-assets
    ├── Fixed Asset List                              /fixed-assets
    └── Asset Types                                   /fixed-assets/types

⚙️  System Administration
    ├── Users                                         /users
    ├── Staff                                         /staff
    ├── Branches                                      /branches
    ├── Access Profiles                               /access-profiles
    ├── System Config                                 /config
    ├── Public Holidays                               /config/holidays
    ├── Non-working Days                              /config/non-working-days
    ├── Modules                                       /config/modules
    ├── Currencies                                    /currencies
    ├── Nationalities                                 /nationalities
    ├── Locations (Provinces/Districts/...)           /locations/provinces
    ├── Languages                                     /languages
    └── Translation Keys                              /translation-keys
```

## ៤.១ Dashboard (`/dashboard`)

**Purpose**: Statistics overview + Recent transactions + Loans due today

`DashboardController::index()` calculates:

```php
$statistics = [
    'total_customers'     => Customer::count(),
    'total_accounts'      => AccountInfo::count(),
    'active_loans'        => LoanSchedule::where('os_balance', '>', 0)->count(),
    'total_disbursed'     => LoanSchedule::sum('amount'),
    'outstanding_balance' => LoanSchedule::sum('os_balance'),
    'today_payments'      => Trans::whereDate('tran_date', today())->sum('amount'),
];
```

- **Recent Transactions** — 10 latest rows from `trans` table
- **Loans Due Today** — rows in `loan_schedules` where `next_pay_date = today`

## ៤.២ Customers — `/customers`

**Workflow:** Create customer → Open account → Disburse loan → Receive repayments

### Customer Create

1. Go to `/customers/create`
2. Required fields:
   - Identity: First/Last Name (EN + KH), DOB, Gender, ID Number, Marital Status
   - Contact: Phone, Email
   - Address: **Country → Province → District → Commune → Village** (cascading dropdowns)
   - Spouse Info (if Married)
   - Staff Officer assigned
   - Photo upload
3. Click **Save**

System calls cascading API endpoints (added during third-pass audit):
```
GET /api/provinces              → All provinces
GET /api/districts/{provinceId} → Districts in selected province
GET /api/communes/{districtId}  → Communes in selected district
GET /api/villages/{communeId}   → Villages in selected commune
```

> ⚠️ **Important:** Migration uses `id`, `name_en`, `name_kh` for
> `provinces`/`districts`/`communes`/`villages` (NOT `PROVINCE_ID`, etc.).

### View / Edit / Delete

DataTable at `/customers` shows:
- Photo, Name (EN/KH), ID, Phone, Address, Action

Action buttons:
- 👁 View → modal with full info
- ✏️ Edit → modal to update
- 🗑️ Delete → SweetAlert confirm → soft delete

## ៤.៣ Accounts — `/accounts`

**Account Types** (from `account_types` table, seeded by `AccountTypesSeeder`):
1. Savings Account
2. Current Account
3. Loan Account
4. Fixed Deposit

Account opening:
1. Select **Customer** (Tom Select autocomplete)
2. Choose **Account Type** + **Currency** (USD/KHR/THB...)
3. Enter **Initial Deposit**
4. System creates `account_infos` record + first `cust_acct_trans` row (balance = initial deposit)

Status constants:
```php
AccountInfo::STATUS_ACTIVE   = 1
AccountInfo::STATUS_DORMANT  = 2
AccountInfo::STATUS_CLOSED   = 3
AccountInfo::STATUS_FROZEN   = 4
```

## ៤.៤ Loans — `/loans`

### Loan Lifecycle

```
[1] Application      → loan_schedule_tmps (draft)
[2] Approval         → loan_schedules (active)
[3] Disbursement     → cash payout + first cust_acct_trans
[4] Repayment        → loan_trans + reduce os_balance
[5] Arrears (if any) → loan_arrears (overdue)
[6] Close            → os_balance = 0, status updated
```

### Loan Creation Flow

At `/loans/create`:

1. **Customer** (Tom Select) → System loads linked `account_infos`
2. **Loan Type** (Individual / Group)
3. **Amount, Currency, Term** (months), **Payment Frequency** (Monthly/Weekly/Daily...)
4. **Purpose of Loan** (from `purpose_loans` table)
5. **Interest Rate** (% per annum)
6. **Disbursement Date** + **First Payment Date**
7. **Collateral**: 1+ items
   - Type (Land, Vehicle, Gold, Salary, ...)
   - Estimated Value
   - Photos / Documents
8. Click **Calculate Schedule** → System produces amortization table
9. Click **Disburse** → Transfer to `loan_schedules` + build repayment schedule + Cash out

### Loan Schedule (Amortization)

ប្រព័ន្ធ​​គណនា principal/interest ​ឱ្យ​ខ្សែ​ឯករាជ្យ​នីមួយៗ ​​​ដែលត្រូវ​បាន​រក្សា​​ទុក​​ក្នុង​ `loan_custom_schedule_details`។
សម្រាប់ឧទាហរណ៍ Monthly amortization:

```
| Period | Due Date    | Principal | Interest | Total Payment | Balance |
|--------|-------------|-----------|----------|---------------|---------|
|   1    | 2025-01-15  |    100.00 |    50.00 |        150.00 | 900.00  |
|   2    | 2025-02-15  |    100.00 |    45.00 |        145.00 | 800.00  |
|  ...   | ...         | ...       | ...      | ...           | ...     |
```

## ៤.៥ Transactions — `/transactions`

ជាកន្លែងតែមួយដែលគេអាចមើល transaction ទាំងអស់៖ Cash In, Cash Out, Transfer,
Loan Payment, FD Deposit, Interest Posting, Fee, ...

**Trans table** ជា central log — រាល់​ប្រតិបត្តិការ​​សំខាន់​ៗ​បង្កើត​ row ​​មួយ:
```php
Trans::TYPE_DEPOSIT       = 1
Trans::TYPE_WITHDRAWAL    = 2
Trans::TYPE_TRANSFER      = 3
Trans::TYPE_FD_DEPOSIT    = 4
Trans::TYPE_LOAN_PAYMENT  = 5
// ... etc
```

រាល់ Trans row ត្រូវនឹង GL posting មួយ ​​​ស្រប​តាម `GlMap` (which Debit/Credit accounts to hit).

## ៤.៦ Cash Management — `/cash` & `/cash/transfers`

- **Cash position by branch / drawer / currency** (table `cash_mgts`)
- **Inter-branch transfers**: បង្កើត `pendding_cash_transfers` record
  រហូត​ដល់​ branch ​ទទួល​​​​​​​​​បាន confirm
- **Cash Closing** ​នៅ​ចុង​ថ្ងៃ — set `tran_date` ​ទៅ​ថ្ងៃ​​បន្ទាប់

## ៤.៧ Fixed Deposits — `/fixed-deposits`

- បង្កើត **FD Certificate** ​​​​មួយ​ឱ្យ​អតិថិជន (PK: `fd_cert_id`)
- ​ភ្ជាប់​ជាមួយ **FD Term** ​(3M / 6M / 12M / 24M) + **FD Option** (Interest payment frequency)
- Maturity ដល់ → ​ប្រ​​ព័ន្ធ​​​​អនុ​ញ្ញាត:
  - Withdraw (ផ្ទេរ​សាច់​ប្រាក់​ទៅ​​គណនី​​​សន្សំ)
  - Rollover (បង្កើត FD ​​ថ្មី​​​​​​​​​​​​​​​​​​​​​​​​​​​)

## ៤.៨ Cheques & Passbooks — `/cheques`, `/passbooks`

**Cheques**:
- Issue ​សៀវភៅ​មូលប្បទាន​ប័ត្រ ​មួយដែលមាន range នៃ cheque numbers
- Clear ​​​មូលប្បទាន​ប័ត្រ​​ដែល​​​​បាន​​យកមកដាក់
- Stop payment (block specific cheque numbers)
- Cheque Maintenance: ​ស្ថានភាព (used / unused / lost / stop)

**Passbooks**:
- Issue សៀវភៅ​​គណនី (Passbook) ​មួយ​ឱ្យ​អតិថិជន
- Print transactions ដែល​ត្រូវ​នឹង​ account
- Maintenance: lost passbook, replacement, ...

## ៤.៩ Groups — `/groups`

Group lending — អតិថិជន​ច្រើន​​​ដែល​សហការ​​យក​ប្រាក់​​កម្ចី​មួយ ហើយ​មាន​បន្ទុក​​រួម​​គ្នា។
ប្រ​ព័ន្ធ​តាមដាន group members (`group_details`) ​និង repayment ​ត្រូវ​ចែក​​​​ដោយ​សមាជិក។

## ៤.១០ Interest — `/interest`

- **Interest Rates** (`/interest/rates`) — set interest rate ​ដោយ Account Type
- **Accrued Interest** (`/interest/accrued`) — calculate ​អ​ត្រាការ​ប្រាក់​​ដែល​​​ត្រូវ​​បង់​ប្រចាំ​ខែ

## ៤.១១ Reports — `/reports`

Reports ទាំងអស់ផ្ដល់​​​​​​ filters (Date Range, Branch, Currency, ...) + Export (PDF/Excel).

| Report | URL | Description |
| --- | --- | --- |
| Daily Cash | `/reports/daily` | Cash in/out by branch per day |
| Loan Aging | `/reports/loans` | Loans by buckets (0-30, 31-60, 61-90, 90+) |
| Loan Summary | `/reports/loan-summary` | Active loans + outstanding |
| Loan Arrears | `/reports/loan-arrears` | Overdue loans + total arrears |
| Disbursements | `/reports/disbursements` | Loans disbursed in period |
| Customer Report | `/reports/customers` | Customer demographics + counts |
| Account Report | `/reports/accounts` | Account balances by type/branch |
| Transaction Report | `/reports/transactions` | All transactions by date/type |
| Trial Balance | `/reports/trial-balance` | GL account balances |

## ៤.១២ General Ledger — `/gl`

**Chart of Accounts** ៤ កម្រិត:

```
GlL1 (Top-level)
  └── GlL2
        └── GlL3
              └── GlL4
                    └── Gl (Leaf account, used for posting)
```

ឧទាហរណ៍:
```
1000  ASSETS                          (GlL1)
  1100  CURRENT ASSETS                (GlL2)
    1110  CASH                        (GlL3)
      1111  CASH IN HAND              (GlL4)
        1111-001  CASH BRANCH 1 USD   (Gl)
```

**GlMap** — រូបមន្ត​​​​ដែល​​បង្ហាញ ​​ប្រ​ភេទ transaction (TRAN_TYPE) ​​ត្រូវ​​​​ post ​ទៅ Debit/Credit
account ​មួយ​ណា។

## ៤.១៣ Fixed Assets — `/fixed-assets`

តាមដាន​ទ្រព្យ​ស្ថេរ​​របស់​ក្រុមហ៊ុន (Computer, Vehicle, Building, ...):
- Asset Type (categorize)
- Purchase Date, Cost, Depreciation method
- Annual depreciation calculation

---

<a id="part-5-permissions"></a>
# ផ្នែកទី ៥ — User Roles & Permissions

## ៥.១ ស្ថាបត្យកម្ម RBAC

```
User (auth)
   │
   ↓
UserLogin (links user → access profile)
   │
   ↓
AccessProfile (e.g. "Manager", "Teller", "Loan Officer")
   │
   ↓ has many
AccessProfileDetail (per-module CRUD permissions)
   │
   ↓
Module (e.g. "customers", "loans", "accounts")
```

ក្រៅពី CRUD permissions, `AccessProfile` ​​​ក៏​មាន **Transaction Limits**:

```php
profile.deposit_limit     // Max amount per deposit transaction
profile.withdrawal_limit  // Max amount per withdrawal
profile.loan_limit        // Max amount per loan disbursement
profile.non_cash_limit    // Max amount for non-cash transactions
```

## ៥.២ ការប្រើប្រាស់​​​​ Permissions ​ក្នុង Controllers

```php
use App\Traits\HasPermissions;

class CustomerController extends Controller
{
    use HasPermissions;

    public function store(Request $request)
    {
        // Check module/action permission
        if (!$this->canAccessResource('customers', 'create')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }
        // ...
    }

    public function deposit(Request $request)
    {
        // Check amount is within profile limit
        $this->authorizeTransaction('deposit', $request->amount);
        // ...
    }
}
```

## ៥.៣ Helper functions (auto-loaded from `app/Helpers/Helpers.php`)

```php
user_has_permission('customers.create')   // bool
user_is_super_admin()                     // bool
user_limits()                             // array of transaction limits
user_can_transact('deposit', $amount)     // bool
```

## ៥.៤ ការ​​បន្ថែម Permissions ​ថ្មី

1. ​បន្ថែម row ​ថ្មី​ទៅ `modules` table (តាមរយៈ `/config/modules` ឬ migration)
2. នៅ `access-profiles/{id}/edit`, check the new module's create/read/update/delete checkboxes
3. Save → System updates `access_profile_details`

---

<a id="part-6-i18n"></a>
# ផ្នែកទី ៦ — Multi-Language System

**Supported locales**: `en` (English), `kh` (Khmer), `zh` (Chinese)

## ៦.១ ​​​​​​​​​​​Translation Files (Laravel native)

```
resources/lang/
  ├── en/
  │   ├── common.php
  │   ├── auth.php
  │   ├── validation.php
  │   └── pagination.php
  ├── kh/  (same structure)
  └── zh/  (same structure)
```

## ៦.២ ការប្រើ​​​​​នៅ​ Blade

```php
{{ __('common.nav.dashboard') }}
{{ __('common.messages.welcome', ['name' => $user->name]) }}
```

## ៦.៣ ការប្រើ​​នៅ JavaScript

```php
{{-- In blade --}}
<script>
const t = {!! js_translations(['common.actions.save', 'common.actions.cancel']) !!};
console.log(t['common.actions.save']);
</script>
```

## ៦.៤ ការ​​ប្ដូរ​ភាសា

User clicks language flag in top header → POSTs to `/language/switch` → `SetLocale`
middleware ​​​អនុវត្ត​​នៅ​ request ​បន្ទាប់។

## ៦.៥ Translation Keys Management (`/translation-keys`)

Admin interface where you can:
- View all translation keys
- Auto-translate via Google Translate API (`stichoza/google-translate-php`)
- Import / Export to lang files

## ៦.៦ Dual-language Columns ​​​​​​​​​​​​​​​​នៅ​ Database

តារាង​ខ្លះ​មាន​ column ពីរ​​ (EN + KH):

| Table | EN column | KH column |
| --- | --- | --- |
| `customer_infos` | `name_en` | `name_kh` |
| `branches` | `name_en` | `name_kh` |
| `provinces` | `name_en` | `name_kh` |
| `districts` | `name_en` | `name_kh` |
| `communes` | `name_en` | `name_kh` |
| `villages` | `name_en` | `name_kh` |
| `nationalitys` | `nationality_en` | `nationality_kh` |
| `purpose_loans` | `purpose_en` | `purpose_kh` |

> ⚠️ Display​​​​ ​​​ត្រូវ​ឈរ​​លើ​​ locale​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​​:
> `app()->getLocale() === 'kh' ? $row->name_kh : $row->name_en`

---

<a id="part-7-dev"></a>
# ផ្នែកទី ៧ — Developer Reference

## ៧.១ បង្កើត CRUD Module ​ថ្មី (Step-by-step)

### Step 1 — Migration

​​បន្ថែម​​ table ​ថ្មី​ទៅ​ canonical migration file:

```php
// database/migrations/2024_12_07_000000_create_loan_management_system_tables.php
Schema::create('my_new_table', function (Blueprint $table) {
    $table->id();
    $table->string('name_en');
    $table->string('name_kh')->nullable();
    $table->decimal('amount', 20, 5)->default(0);
    $table->unsignedBigInteger('created_by')->nullable();
    $table->dateTime('created_date')->nullable();
    $table->dateTime('modify_date')->nullable();
    // ❌ DO NOT add: $table->timestamps();
});
```

### Step 2 — Model

```php
// app/Models/MyNewModel.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MyNewModel extends Model
{
    protected $table = 'my_new_table';
    protected $primaryKey = 'id';
    public $timestamps = false;  // ← critical

    protected $fillable = [
        'name_en', 'name_kh', 'amount',
        'created_by', 'created_date', 'modify_date',
    ];

    protected $casts = [
        'amount' => 'decimal:5',
    ];

    const STATUS_ACTIVE = 1;
}
```

### Step 3 — Controller

```php
// app/Http/Controllers/MyNewController.php
<?php

namespace App\Http\Controllers;

use App\Models\MyNewModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MyNewController extends Controller
{
    public function index()
    {
        return view('my-new.index');
    }

    public function getData()
    {
        return DataTables::of(MyNewModel::query())
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-sm btn-primary" data-id="'.$row->id.'">Edit</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_kh' => 'nullable|string|max:255',
            'amount'  => 'required|numeric|decimal:5',
        ]);

        $validated['created_by']   = auth()->id() ?? 1;
        $validated['created_date'] = now();

        $row = MyNewModel::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Created successfully',
            'data'    => $row,
        ], 201);
    }
}
```

### Step 4 — Routes (paired)

```php
// routes/web.php (inside auth middleware group)
Route::prefix('my-new')->group(function () {
    Route::get('/',     [MyNewController::class, 'index'])->name('my-new.index');
    Route::get('/data', [MyNewController::class, 'getData'])->name('my-new.data');
    Route::post('/',    [MyNewController::class, 'store'])->name('my-new.store');
    Route::put('/{id}', [MyNewController::class, 'update'])->name('my-new.update');
    Route::delete('/{id}', [MyNewController::class, 'destroy'])->name('my-new.destroy');
});
```

### Step 5 — Blade view

```blade
{{-- resources/views/my-new/index.blade.php --}}
@extends('admin.layouts.admin_layout')

@section('content')
<table id="myNewTable" class="table table-bordered">
    <thead>
        <tr><th>ID</th><th>Name EN</th><th>Amount</th><th>Action</th></tr>
    </thead>
</table>

@push('scripts')
<script>
$(function() {
    $('#myNewTable').DataTable({
        ajax: '{{ route('my-new.data') }}',
        columns: [
            { data: 'id',      name: 'id' },
            { data: 'name_en', name: 'name_en' },   // lowercase match!
            { data: 'amount',  name: 'amount' },
            { data: 'action',  name: 'action', orderable: false, searchable: false },
        ],
    });
});
</script>
@endpush
@endsection
```

### Step 6 — ​​​​Module ​​បន្ថែម​ទៅ `modules` table

```sql
INSERT INTO modules (name, slug, ...) VALUES ('My New', 'my-new', ...);
```

### Step 7 — Update sidebar `left_sidebar.blade.php`

```blade
<li><a href="{{ route('my-new.index') }}"><span>{{ __('common.nav.my_new') }}</span></a></li>
```

### Step 8 — Add translation key

```php
// resources/lang/en/common.php
'nav' => [
    'my_new' => 'My New Module',
],

// resources/lang/kh/common.php
'nav' => [
    'my_new' => 'ម៉ូឌុលថ្មីរបស់ខ្ញុំ',
],
```

## ៧.២ ​Useful Commands

```bash
# Reset database (drop + create + migrate + seed)
php artisan migrate:fresh --seed --force

# Seed a specific seeder only
php artisan db:seed --class=CustomerInfoSeeder

# List all routes (filter by name)
php artisan route:list --name=customers

# Format all PHP files
vendor/bin/pint

# Format only (check)
vendor/bin/pint --test

# Run tests
php artisan test
php artisan test --filter CustomerTest

# Clear caches (when route/config changes don't reflect)
php artisan optimize:clear
```

## ៧.៣ Querying Patterns

```php
// ✅ Filter by status using constant (never hardcode 1, 2, 3)
$active = AccountInfo::where('account_status', AccountInfo::STATUS_ACTIVE)->get();

// ✅ Eager load relationships
$customers = Customer::with(['village.commune.district.province'])->get();

// ✅ DataTables with relationship search
return DataTables::of(Customer::with('village'))
    ->filter(function ($query) {
        if (request('search.value')) {
            $query->where('name_en', 'like', "%".request('search.value')."%")
                  ->orWhere('name_kh', 'like', "%".request('search.value')."%");
        }
    })
    ->make(true);
```

## ៧.៤ Validation Patterns

```php
$validated = $request->validate([
    'cust_id'      => 'required|exists:customer_infos,cust_id',
    'acct_type_id' => 'required|exists:account_types,acct_type_id',
    'ccy_id'       => 'required|exists:currencys,ccy_id',  // legacy table name!
    'amount'       => 'required|numeric|decimal:5|min:0',
    'rate'         => 'required|numeric|decimal:2',
    'name_en'      => 'required|string|max:255',
    'tran_date'    => 'required|date',
]);
```

> ⚠️ Some legacy tables have unusual names: `currencys` (with an extra "s"),
> `nationalitys`, `branchs`. Always check the migration.

---

<a id="part-8-pitfalls"></a>
# Part 8 — Common Pitfalls (Audit Findings)

These are real bugs found and fixed in this codebase across three audit passes (PR #1).
សរុប **41 files** ត្រូវបានកែ + **11 Models** បន្ថែមថ្មី។

## 8.1 Pitfall — Uppercase Attribute Access on Models

### Bug
```php
// app/Models/CustIncomeHistory.php (BEFORE)
public function getNetIncomeAttribute()
{
    return $this->INCOME - $this->EXPENSE - $this->LIABILITY;
}
```

Migration column names are lowercase (`income`, `expense`, `liability`). On Linux/SQLite,
`$this->INCOME` returns `null` → result becomes `0` silently with no error.

### Fix
```php
public function getNetIncomeAttribute()
{
    return $this->income - $this->expense - $this->liability;
}
```

### Sweep
```bash
grep -rn '\$this->[A-Z_]\+' app/Models/   # must return 0
```

Files fixed: `AccountType`, `CustAcctTran`, `JointAccountHolder`, `LoanArrear`,
`CustIncomeHistory`, `Config`.

## 8.2 Pitfall — Validation Rules Referencing Wrong Columns

### Bug
```php
// AccessProfileController (BEFORE)
$request->validate([
    'modules.*' => 'required|exists:modules,MODULE_ID',  // column does not exist
]);
```

Migration has only `module_id` (lowercase) → validation rejects ALL requests silently.

### Fix
```php
$request->validate([
    'modules.*' => 'required|exists:modules,module_id',
]);
```

### Sweep
```bash
grep -rn "exists:[a-z_]\+,[A-Z]" app/Http/Controllers/
```

Files fixed: `AccessProfileController`, `CashController` (`currencys,CCY_ID` →
`currencys,ccy_id`), `CurrencyController`, `FixedAssetController` (12 sites),
`GroupController`, `LocationController`, `PassbookController`.

## 8.3 Pitfall — LocationController Selecting Non-existent Columns

### Critical bug
```php
// LocationController (BEFORE)
public function getDistricts($provinceId)
{
    return District::where('PROVINCE_ID', $provinceId)
        ->select('district_id', 'district', 'district_kh')
        ->get();
}
```

Migration has only `id`, `province_id`, `name_en`, `name_kh`. The SQL returns an error
or empty array → cascading dropdown is silently broken in the UI.

### Fix
```php
public function getDistricts($provinceId)
{
    return District::where('province_id', $provinceId)
        ->select('id', 'name_en', 'name_kh')
        ->orderBy('name_en')
        ->get();
}
```

Same fix applied to `getCommunes`, `getVillages` (15 sites total).

## 8.4 Pitfall — Manual Timestamps on Tables With `->timestamps()`

### Bug

The location tables (`provinces`, `districts`, `communes`, `villages`) use
`$table->timestamps()` in the migration — i.e. only `created_at` + `updated_at`. But
the controller was inserting custom columns:

```php
// LocationController (BEFORE)
Province::create([
    'name_en'      => $request->name_en,
    'name_kh'      => $request->name_kh,
    'created_by'   => auth()->id() ?? 1,  // column does not exist
    'created_date' => now(),              // column does not exist
]);
```

SQL error: `column "created_by" does not exist`.

### Fix
```php
Province::create([
    'name_en' => $request->name_en,
    'name_kh' => $request->name_kh,
]);
```

In the model, set `$timestamps = true` (default) for the location models, and
`$timestamps = false` for every other model in the system (since the rest of the
schema uses custom `created_date` / `modify_date` / `created_by` columns).

## 8.5 Pitfall — Blade DataTable Column Names in Uppercase

### Bug
```js
// resources/views/fixed-assets/index.blade.php (BEFORE)
$('#fixedAssetTable').DataTable({
    ajax: '/fixed-assets/data',
    columns: [
        { data: 'FA_ID',         name: 'FA_ID' },
        { data: 'FA_TYPE_NAME',  name: 'FA_TYPE_NAME' },
        { data: 'CURRENCY',      name: 'CURRENCY' },
        { data: 'PURCHASE_DATE', name: 'PURCHASE_DATE' },
    ],
});
```

The DataTable JSON response uses lowercase keys (`fa_id`, `fa_type_name`, ...). Mismatched
`data:` keys → all cells render empty.

### Fix
```js
columns: [
    { data: 'fa_id',         name: 'fa_id' },
    { data: 'fa_type_name',  name: 'fa_type_name' },
    { data: 'currency',      name: 'currency' },
    { data: 'purchase_date', name: 'purchase_date' },
],
```

### Sweep
```bash
grep -rn "data: '[A-Z_]\+'" resources/views/   # must return 0
```

Files fixed: `fixed-assets/index.blade.php` (17), `fixed-assets/types.blade.php` (13),
`passbooks/list.blade.php` (4), and others.

## 8.6 Pitfall — Form `name=""` Attributes Mismatched

### Bug
```html
<!-- passbooks/maintenance.blade.php (BEFORE) -->
<input type="text" name="ACCT_ID" id="acctId">
<input type="text" name="PASSBOOK_NO" id="passbookNo">
<textarea name="REMARK"></textarea>
```

The controller validates lowercase keys, so all POSTed UPPERCASE fields are ignored:

```php
$request->validate([
    'acct_id'     => 'required|exists:account_infos,acct_id',
    'passbook_no' => 'required|string',
    'remark'      => 'nullable|string',
]);
// → validation always fails because POSTed keys are ACCT_ID/PASSBOOK_NO/REMARK
```

### Fix
```html
<input type="text" name="acct_id" id="acctId">
<input type="text" name="passbook_no" id="passbookNo">
<textarea name="remark"></textarea>
```

### Sweep
```bash
grep -rn 'name="[A-Z_]\+"' resources/views/   # must return 0 (after excluding selects)
```

Files fixed: `passbooks/maintenance` (4), `passbooks/index` (2), `passbooks/list` (2),
`fixed-deposits/index` (10), `cash/transfers` (4), `access-profiles/index` (4).

## 8.7 Pitfall — Cascade Dropdowns Using Legacy Column Names

### Critical UI bug
```js
// customers/create.blade.php (BEFORE)
fetch(`/api/districts/${provinceId}`)
    .then(r => r.json())
    .then(data => {
        data.forEach(d => {
            $('#districtSelect').append(
                `<option value="${d.DISTRICT_ID}">${d.DISTRICT}</option>`
            );
        });
    });
```

The API returns `{id, name_en, name_kh}` (per the migration), but the view reads
`d.DISTRICT_ID/DISTRICT` → both are `undefined` → dropdown silently shows broken options.

### Fix
```js
data.forEach(d => {
    const label = (currentLocale === 'kh' && d.name_kh) ? d.name_kh : d.name_en;
    $('#districtSelect').append(
        `<option value="${d.id}">${label}</option>`
    );
});
```

## 8.8 Pitfall — Seeders Using MySQL-only Syntax

### Bug
```php
// VillagesTableSeeder.php (BEFORE)
DB::statement('SET FOREIGN_KEY_CHECKS=0;');
Village::truncate();
DB::statement('SET FOREIGN_KEY_CHECKS=1;');
```

`SET FOREIGN_KEY_CHECKS` is MySQL-only. On SQLite/Postgres the seeder crashes.

### Fix
```php
use Illuminate\Support\Facades\Schema;

Schema::disableForeignKeyConstraints();
Village::truncate();
Schema::enableForeignKeyConstraints();
```

Files fixed: `VillagesTableSeeder`, `PaymentFrequencySeeder`, `PurposeLoanSeeder`.

## 8.9 Pitfall — Class Casing Wrong on Linux

### Bug
```php
// UserRolePermissionSeeder.php (BEFORE)
use App\Models\Accessprofile;        // file is named AccessProfile.php
```

Works on macOS / Windows (case-insensitive FS) but fails on Linux/CI.

### Fix
```php
use App\Models\AccessProfile;
```

## 8.10 Pitfall — Missing Models for Temp/Workflow Tables

### Bug

11 `*_tmps` tables in the migration had **no corresponding Eloquent model**, so
multi-step workflows (loan approval staging, collateral release staging, etc.) had
to use raw `DB::table('...')` queries — bypassing casts, fillable, and relations.

### Fix

Created the missing models with proper `$table`, `$primaryKey`, `$timestamps = false`,
and `$fillable`:

`LoanScheduleTmp`, `LoanCustomScheduleTmp`, `LoanCustomScheduleDetailTmp`,
`CollateralTmp`, `CollateralDetailTmp`, `CollateralReleaseTmp`, `TranTmp`,
`TranDetailTmp`, `CustAcctTranTmp`, `GroupTmp`, `GroupDetailTmp`.

## 8.11 Pitfall — Missing API Routes for Existing Methods

### Bug

`LocationController` has methods `getDistricts`, `getCommunes`, `getVillages` —
but `routes/web.php` did not register API endpoints for them. AJAX calls to
`/api/districts/{id}` returned 404.

### Fix
```php
// routes/web.php
Route::prefix('api')->group(function () {
    Route::get('/provinces',          [LocationController::class, 'getProvinces']);
    Route::get('/districts/{id}',     [LocationController::class, 'getDistricts']);
    Route::get('/communes/{id}',      [LocationController::class, 'getCommunes']);
    Route::get('/villages/{id}',      [LocationController::class, 'getVillages']);
});
```

## 8.12 Pitfall — Schema Drift in Blade Forms

### Bug

`int-rates` blade form had handlers that set `#INT_RATE`, `#INT_TYPE`,
`#INT_OPTION`, `#DESCRIPTION` — but the `int_rates` migration table only has
`int_rate_id`, `rate`, `acct_type_id`. The extra fields never existed.

### Fix

Removed the stale fields from the form; updated edit handler to set only the
columns that exist in the migration.

---

<a id="part-9-testing"></a>
# Part 9 — Testing & Verification

## 9.1 Pre-commit Checklist

Before pushing any change, run the full validation sequence:

```bash
# 1. Lint (Laravel Pint)
vendor/bin/pint --test

# 2. Migration + Seed (fresh database)
php artisan migrate:fresh --seed --force

# 3. PHPUnit tests
php artisan test

# 4. Frontend build (ensure no asset errors)
npm run build
```

If any step fails, fix before committing.

## 9.2 Audit Sweeps to Run After Schema Changes

Run these `grep` patterns to catch regressions:

```bash
# 1. Uppercase attribute access on models (must be 0)
grep -rn '\$this->[A-Z_]\+' app/Models/

# 2. exists:table,UPPERCASE_COL in validation (must be 0)
grep -rn 'exists:[a-z_]\+,[A-Z]' app/Http/Controllers/

# 3. DataTable columns with UPPERCASE keys (must be 0)
grep -rn "data: '[A-Z_]\+'" resources/views/

# 4. Form fields with UPPERCASE name (must be 0, excluding selects/hidden)
grep -rn 'name="[A-Z_]\+"' resources/views/ | grep -v "select name"

# 5. AJAX response objects using UPPERCASE keys (must be 0)
grep -rn '\.[A-Z][A-Z_]\+' resources/views/**/*.blade.php | grep -v "window.URL"

# 6. Manual created_by/created_date on tables that use ->timestamps()
grep -rn 'created_date' app/Http/Controllers/LocationController.php
```

## 9.3 Manual Test Plan for New Modules

For every new CRUD module, walk through:

1. **Index page**: Visit `/<resource>` — DataTable loads, no console errors.
2. **Search**: Type in the search box — server-side filtering works.
3. **Create**: Open modal, submit valid data → success toast + table refreshes.
4. **Create (invalid)**: Submit empty / bad data → 422 with friendly message.
5. **Edit**: Click edit, modify field, save → success + table updates.
6. **Delete**: Click delete, confirm via SweetAlert → row removed.
7. **Pagination**: Click page 2 — works, no console errors.
8. **Permissions**: Log in as a non-admin user — actions hidden when unauthorized.
9. **Multi-language**: Switch to Khmer — labels translated, dates formatted.

## 9.4 DataTable Smoke Test

Open browser dev tools → Network tab → load `/<resource>` page:

- Request to `/<resource>/data` returns `200`.
- Response body has shape `{ draw, recordsTotal, recordsFiltered, data: [...] }`.
- Each row object has the same keys as `columns[].data` in the blade.

If any column shows blank, check that the JSON key matches `data:` exactly
(case-sensitive on Linux).

## 9.5 Cascade Dropdown Smoke Test

On `customers/create`:

1. Select a province → district dropdown populates.
2. Select a district → commune dropdown populates.
3. Select a commune → village dropdown populates.
4. Submit form → record saves with `village_id`.

If any step fails, check:
- `routes/web.php` has `/api/districts/{id}` etc. registered
- `LocationController` selects `id, name_en, name_kh`
- Blade reads `d.id` / `d.name_en` (not `d.DISTRICT_ID` / `d.DISTRICT`)

---

<a id="part-10-troubleshooting"></a>
# Part 10 — Troubleshooting

## 10.1 "Column not found" SQL Error

**Symptom**: `SQLSTATE[42S22]: Column not found: 1054 Unknown column 'X' in 'field list'`.

**Cause**: Code references an UPPERCASE column name, but the migration uses lowercase
(or vice versa).

**Fix**: Open `database/migrations/2024_12_07_000000_create_loan_management_system_tables.php`,
find the table definition, and use the **exact** column name (case-sensitive).

## 10.2 Validation Always Fails Silently

**Symptom**: AJAX returns 422 with `{ errors: { field_x: [...] } }` even though
the form looks correct.

**Cause**: The form `name=""` attribute does not match the validation key.

**Fix**: Open the blade, ensure every `<input name="...">` matches a key in the
controller's `validate([...])` call.

## 10.3 DataTable Shows Empty Cells

**Symptom**: Table loads, pagination works, but cells are blank.

**Cause**: The JSON response keys do not match `columns[].data` in the blade.

**Fix**:
1. Open Network tab → click on `/<resource>/data` → check response body.
2. Note the actual keys (e.g. `cust_id`, `name_en`).
3. Update the blade `columns: [{ data: 'cust_id', ... }]` to match.

## 10.4 Cascade Dropdown Stays Empty

**Symptom**: Selecting a province does nothing; district list stays empty.

**Cause**: One of:
- API route `/api/districts/{id}` not registered in `routes/web.php`.
- Controller `getDistricts()` selects columns that don't exist.
- Blade reads `d.DISTRICT_ID` instead of `d.id`.

**Fix**: Check all three: route, controller select clause, blade JS access.

## 10.5 Permission Check Always Denies

**Symptom**: `canAccessResource('customers', 'create')` returns false even for admin.

**Cause**: Module name in `AccessProfileDetail` table does not match the string
passed to `canAccessResource()`.

**Fix**: Check the `modules` table → use exact `module_name` value.

## 10.6 Seeder Crashes on `SET FOREIGN_KEY_CHECKS`

**Symptom**: `php artisan db:seed` fails on SQLite or Postgres.

**Cause**: Seeder uses MySQL-only `DB::statement('SET FOREIGN_KEY_CHECKS=0;')`.

**Fix**: Replace with `Schema::disableForeignKeyConstraints()` /
`Schema::enableForeignKeyConstraints()`.

## 10.7 Class Not Found on Linux/CI but Works Locally

**Symptom**: `Class 'App\Models\Accessprofile' not found` on Linux/CI, works on
macOS/Windows.

**Cause**: Case mismatch between filename (`AccessProfile.php`) and `use` statement
(`Accessprofile`). Linux is case-sensitive.

**Fix**: Use exact filename casing in all `use` statements.

## 10.8 `created_at` / `updated_at` Errors

**Symptom**: `Column 'updated_at' not found` when creating a record.

**Cause**: Model is missing `public $timestamps = false;` — Laravel tries to set
`updated_at`, but the table uses manual `modify_date`.

**Fix**: Add `public $timestamps = false;` to the model. Set
`created_date`/`modify_date`/`created_by` manually.

**Exception**: Location models (`Province`, `District`, `Commune`, `Village`) DO
use Laravel timestamps — leave `$timestamps = true` (default) for these.

## 10.9 Route Not Found (404)

**Symptom**: AJAX call returns 404 even though the controller method exists.

**Cause**: Method defined in controller but route not registered.

**Fix**: Add the route to `routes/web.php`. Verify with
`php artisan route:list | grep <name>`.

## 10.10 Frontend Asset Errors After Pulling

**Symptom**: `Vite manifest not found` or CSS missing after `git pull`.

**Fix**:
```bash
npm install
npm run build
php artisan optimize:clear
```

---

## End of Manual

For questions or contributions, open a PR against
[`samnangim79-blip/loan-management-system`](https://github.com/samnangim79-blip/loan-management-system).

Audit history is preserved in `docs/AUDIT_REPORT_2024_12_07_MIGRATION.md`.
