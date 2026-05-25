# Audit Report — Project vs `2024_12_07_000000_create_loan_management_system_tables.php`

This document captures the full audit of the Laravel project against the canonical
schema migration at `database/migrations/2024_12_07_000000_create_loan_management_system_tables.php`,
and the fixes applied in this PR.

## Scope

- **Source of truth**: `database/migrations/2024_12_07_000000_create_loan_management_system_tables.php`
- **Targets audited**: Eloquent models, controllers, routes, views, and seeders
- **Project rules enforced** (from `CLAUDE.md`):
  - NO Eloquent timestamps; manual date tracking
  - All tables defined in the canonical migration
  - Mixed-case legacy columns must be referenced exactly as defined
  - `decimal(20,5)` for amounts and `decimal:2` for rates

## Audit Method

1. Spun up a fresh SQLite database and ran `php artisan migrate`. The canonical
   migration + supplementary migrations apply cleanly.
2. Ran `php artisan db:seed` — surfaced two classes of issues:
   - Wrongly-cased model class names in `UserRolePermissionSeeder` (`Accessprofile`
     vs `AccessProfile`) caused a fatal `Class not found` on case-sensitive
     filesystems.
   - Three seeders used MySQL-only `SET FOREIGN_KEY_CHECKS=0/1` statements that
     fail on SQLite (and any non-MySQL driver).
3. Statically reviewed every model in `app/Models/` against the columns defined
   in the canonical migration to ensure `$table`, `$primaryKey`, `$fillable`,
   `$casts`, and dynamic property accessors match the schema.
4. Verified that all expected workflow ("tmp" / approval) tables defined in the
   canonical migration have corresponding Eloquent models.

## Findings

### A. Critical bugs — uppercase property access (4 models)

The canonical migration declares all columns in lowercase (`dr_cr`, `category`,
`acct_id`, `cust_id`, `arrear_int`, `arrear_prin`, `arear_penalty`,
`arear_saving`). On MySQL these can be accessed case-insensitively, but on
SQLite — and via any standard PSR autoloader on Linux — Eloquent attribute
access is case-sensitive. The following methods would return `null` (and then
fail or behave incorrectly) on a case-sensitive DB driver:

| File | Method | Wrong access | Correct |
| ---- | ------ | ------------ | ------- |
| `app/Models/AccountType.php` | `getCategoryTextAttribute()` | `$this->CATEGORY` | `$this->category` |
| `app/Models/CustAcctTran.php` | `isDebit()` / `isCredit()` | `$this->DR_CR` | `$this->dr_cr` |
| `app/Models/JointAccountHolder.php` | `getKey()` | `$this->ACCT_ID`, `$this->CUST_ID` | `$this->acct_id`, `$this->cust_id` |
| `app/Models/LoanArrear.php` | `getTotalArrearAttribute()` | `$this->ARREAR_INT`, `$this->ARREAR_PRIN`, `$this->AREAR_PENALTY`, `$this->AREAR_SAVING` | lowercase equivalents |

All four have been fixed in this PR.

### B. Cross-driver seeder bugs (3 seeders)

The following seeders used MySQL-specific `SET FOREIGN_KEY_CHECKS=...` raw
statements which break on SQLite. Replaced with Laravel's database-agnostic
`Schema::disableForeignKeyConstraints()` / `Schema::enableForeignKeyConstraints()`:

- `database/seeders/VillagesTableSeeder.php`
- `database/seeders/PaymentFrequencySeeder.php`
- `database/seeders/PurposeLoanSeeder.php`

### C. Wrong model class casing in seeder

`database/seeders/UserRolePermissionSeeder.php` referenced `Accessprofile` and
`AccessprofileDetail` (lowercase `p`), but the actual model classes are
`AccessProfile` and `AccessProfileDetail`. On case-sensitive filesystems
this produced a fatal `Class not found` during seeding. Fixed.

### D. Missing Eloquent models for workflow ("tmp") tables

The canonical migration defines a set of `*_tmps` tables for pending /
approval-workflow records. There were no corresponding Eloquent models, which
made it impossible to use these tables through Eloquent without falling back to
raw query builder calls. Added the following models (all `$timestamps = false`,
matching project convention):

- `app/Models/LoanScheduleTmp.php` — `loan_schedule_tmps`
- `app/Models/CollateralTmp.php` — `collateral_tmps`
- `app/Models/CollateralDetailTmp.php` — `collateral_detail_tmps`
- `app/Models/CollateralReleaseTmp.php` — `collateral_release_tmps`
- `app/Models/CustAcctTranTmp.php` — `cust_acct_tran_tmps`
- `app/Models/GroupTmp.php` — `group_tmps`
- `app/Models/GroupDetailTmp.php` — `group_detail_tmps`
- `app/Models/LoanCustomScheduleTmp.php` — `loan_custom_schedule_tmps`
- `app/Models/LoanCustomScheduleDetailTmp.php` — `loan_custom_schedule_detail_tmps`
- `app/Models/TranTmp.php` — `tran_tmps`
- `app/Models/TranDetailTmp.php` — `tran_detail_tmps`

Each model sets `$table`, `$primaryKey`, `$fillable`, and `$casts` to match the
columns defined in the canonical migration, plus the obvious `belongsTo` /
`hasMany` relationships.

### E. Second-pass deep audit — controllers, middleware, views

A second exhaustive pass searched for any remaining UPPERCASE column/property
references that would silently break on case-sensitive DB drivers, plus any
`exists:` validation rules pointing at non-existent columns. The following
additional issues were found and fixed:

#### Models (2)

| File | Issue |
| ---- | ----- |
| `app/Models/CustIncomeHistory.php` | `getNetIncomeAttribute()` used `$this->INCOME / EXPENSE / LIABILITY` — fixed to lowercase. |
| `app/Models/Config.php` | `Config::getValue()` returned `$config->CONFIG_VALUE` — fixed to `config_value`. |

#### Controllers (7)

| File | Issue |
| ---- | ----- |
| `app/Http/Controllers/AccessProfileController.php` | DataTable callbacks used `$row->DEPOSIT_LIMIT/WITHDRAWAL_LIMIT/LOAN_LIMIT/PROFILE_ID` and validation `exists:modules,MODULE_ID` — fixed. |
| `app/Http/Controllers/CashController.php` | Validation `exists:currencys,CCY_ID` — fixed to `ccy_id`. |
| `app/Http/Controllers/CurrencyController.php` | DataTable callbacks + `unique:currencys,CURRENCY[,...,CCY_ID]` and `$currency->CCY_RATE` — all fixed to lowercase. |
| `app/Http/Controllers/FixedAssetController.php` | 12 sites: DataTable callbacks (`$row->FA_ID/FA_TYPE/CURRENCY/PURCHASE_PRICE/NET_VALUE/DISPOSE_DATE/FA_TYPE_ID`), validation rules (`unique:fixed_assets,FA_CODE`, `exists:fixed_asset_types,FA_TYPE_ID`, `exists:currencys,CCY_ID`, `exists:gls,GL_ID`), and `$asset->NET_VALUE` — all fixed. |
| `app/Http/Controllers/GroupController.php` | `getMembers()` and `searchLoans()` used `$detail->CONTRACT_NO`, `$loan->AMOUNT/OS_BALANCE`, `$customer->NAME_EN`; `addMember()` validation `exists:loan_schedules,CONTRACT_NO` — all fixed. |
| `app/Http/Controllers/LocationController.php` | **Critical** — `getDistricts/getCommunes/getVillages` selected non-existent legacy columns (`district_id, district, district_kh` etc) instead of the actual modern columns (`id, name_en, name_kh`) from the migration. `storeDistrict/storeCommune/storeVillage` and their `update*` siblings validated the wrong field names and the wrong FK targets (`exists:provinces,PROVINCE_ID` etc). DataTable callbacks referenced `$row->DISTRICT_ID / COMMUNE_ID / VILLAGE_ID` and `$row->district->DISTRICT` etc. Manual `created_by/created_date/modify_by/modify_date` were being set on tables that use `$table->timestamps()` per the migration. All 15 sites fixed. |
| `app/Http/Controllers/PassbookController.php` | DataTable callbacks (`$row->PASS_ISSUE_ID/PASS_ID/PASSBOOK_ID/STATUS/APPROVED_DATE`), customer/account/branch property access (`NAME_EN`, `ACCT_NO`, `BRANCH_NAME`), validation `exists:account_infos,ACCT_ID` and `exists:branchs,BRANCH_ID`, and `$issue->ACCT_ID/PASSBOOK_NO` on Passbook creation — all fixed. |

#### Middleware (1)

| File | Issue |
| ---- | ----- |
| `app/Http/Middleware/PermissionMiddleware.php` | `getAvailableModules()` returned `$module->MODULE_ID/MODULE/CONTROL_NAME/URL/TYPE`; `isSuperAdmin()` checked `$profile->PROFILE`; `getUserLimits()` returned `$profile->DEPOSIT_LIMIT/WITHDRAWAL_LIMIT/LOAN_LIMIT/NON_CASH_LIMIT` — all fixed. |

#### Views (1)

| File | Issue |
| ---- | ----- |
| `resources/views/fixed-assets/index.blade.php` | `<option>` values bound to `$type->FA_TYPE_ID/FA_TYPE` and `$currency->CCY_ID/CURRENCY` — fixed to lowercase. |

### F. Third-pass deep audit — view-side (JS / form names) + missing routes

The second pass covered every server-side reference; this third pass scanned
**every Blade template** for client-side references (form `name="..."`, AJAX
JSON access, DataTable column `data: '...'`) that did not match the column
names from the canonical migration. Each of these would silently submit the
wrong field name to the controller (so validation always sees `null`) or read
the wrong key from the JSON response (so the UI shows blank fields).

#### Blade DataTable column definitions

| File | Issue |
| ---- | ----- |
| `resources/views/fixed-assets/index.blade.php` | DataTable used `FA_ID`, `FA_CODE`, `FA_DESC`, `PURCHASE_DATE`, `USEFULL_LIFE`, `DISPOSE_DATE`. JSON modal also used `asset.FA_CODE/FA_DESC/PURCHASE_DATE/PURCHASE_PRICE/USEFULL_LIFE/NET_VALUE/DISPOSE_DATE/FA_COMMENT/DISPOSE_VALUE/DISPOSE_COMMENT`, `asset.asset_type?.FA_TYPE`, `asset.currency?.CURRENCY`, `depre.DEPRE_DATE/AMOUNT`. Edit handler used `FA_ID/FA_CODE/FA_DESC/FA_COMMENT/FA_TYPE_ID/USEFULL_LIFE/CREDIT_GL`. Depreciate handler used `FA_ID/FA_CODE/FA_DESC/NET_VALUE`. — All fixed. |
| `resources/views/fixed-assets/types.blade.php` | DataTable used `FA_TYPE_ID/FA_TYPE/GL_ID/DEPRE_GL/EXP_GL/DISPOSE_GL`. Edit handler used `rowData.FA_TYPE_ID/FA_TYPE/GL_ID/DEPRE_GL/EXP_GL/DISPOSE_GL`. — All fixed. |

#### Blade `row.UPPERCASE` JSON access (DataTable view dialogs)

| File | Issue |
| ---- | ----- |
| `resources/views/passbooks/maintenance.blade.php` | `row.APPROVED_DATE/PASS_ID/TRAN_DATE/QTY/PASS_FROM_NO/PASS_TO_NO` — fixed. |
| `resources/views/passbooks/list.blade.php` | `row.PASSBOOK_ID/PASSBOOK_NO/LAST_PRINTED_PAGE/LAST_PRINTED_LINE` — fixed. |

#### Blade form field names — submitted as form data; controller validation expected lowercase

| File | Form fields fixed |
| ---- | ----------------- |
| `resources/views/passbooks/maintenance.blade.php` | `BRANCH_ID`, `TRAN_DATE`, `PASS_FROM_NO`, `PASS_TO_NO` → lowercase. |
| `resources/views/passbooks/index.blade.php` | `ACCT_ID`, `PASSBOOK_NO` → lowercase. |
| `resources/views/passbooks/list.blade.php` | `LAST_PRINTED_PAGE`, `LAST_PRINTED_LINE` → lowercase. |
| `resources/views/fixed-deposits/index.blade.php` | `FD_CERT_ID`, `ACCT_ID`, `DATE_ISSUE`, `FD_TERM_ID`, `FD_OPTION_ID`, `INT_RATE`, `EXTRA_RATE`, `MATURED_DATE`, `ACCT_FOR_INT`, `ACCT_FOR_PRIN` → all lowercased. |
| `resources/views/cash/transfers.blade.php` | `TRAN_DATE`, `IN_OU`, `CCY_ID`, `FROM_TO` → lowercased. Critical: `name="IN_OU"` was being rejected by the `'in_ou' => 'required|in:i,o'` validator. |
| `resources/views/access-profiles/index.blade.php` | `DEPOSIT_LIMIT`, `WITHDRAWAL_LIMIT`, `LOAN_LIMIT`, `NON_CASH_LIMIT` → lowercased. Plus the JS edit/view handlers were reading `profile.PROFILE_ID/PROFILE/DEPOSIT_LIMIT/WITHDRAWAL_LIMIT/LOAN_LIMIT/NON_CASH_LIMIT` and the modules list `module.MODULE` — all fixed. Form `<input>` for profile name had `id=""` which broke `$('#profile').val(...)` in the edit handler — set to `id="profile"`. |
| `resources/views/customers/create.blade.php` | The address cascade dropdowns were reading `province.PROVINCE_ID/PROVINCE/PROVINCE_KH`, `district.DISTRICT_ID/DISTRICT/DISTRICT_KH`, `commune.COMMUNE_ID/COMMUNE/COMMUNE_KH`, `village.VILLAGE_ID/VILLAGE/VILLAGE_KH` — none of which exist in the modern location schema. Fixed to `id`, `name_en`, `name_kh` matching the migration. Also fixed `$('#VILLAGE_ID')` → `$('#village_id')` so the village select is correctly cleared on parent change. |
| `resources/views/fixed-deposits/index.blade.php` | `loadAccounts()` was reading `account.customer?.NAME_EN`, `account.ACCT_ID`, `account.ACCT_NO` — fixed. |
| `resources/views/interest/rates.blade.php` | Edit handler was setting `#INT_RATE/#INT_TYPE/#INT_OPTION/#DESCRIPTION` — but `int_rates` only has `int_rate_id`, `rate`, `acct_type_id` per the migration. Replaced with `#rate` and `#acct_type_id`. |
| `resources/views/config/index.blade.php` | `loadConfigs()` was reading `config.CONFIG_NAME` and `config.CONFIG_VALUE` — fixed to `config_name`, `config_value`. |

#### Missing API routes

`resources/views/customers/create.blade.php` calls `/api/provinces`,
`/api/districts/{provinceId}`, `/api/communes/{districtId}`, and
`/api/villages/{communeId}` for the location cascade. The controller methods
exist (`LocationController::getProvinces/getDistricts/getCommunes/getVillages`)
and already return the correct lowercase JSON shape, but the four route
declarations were missing — every cascade dropdown was silently broken.
Added to `routes/web.php` under the existing `Route::prefix('api')` group.

## Verification

After applying the fixes, on a fresh database:

```
php artisan migrate           # 7 migrations applied cleanly
php artisan db:seed           # all 28 seeders run end-to-end with no errors
php artisan test              # 2 passed (2 assertions)
vendor/bin/pint --test ...    # all modified/new files clean
npm run build                 # vite build successful
```

## Items intentionally NOT changed

- The seeded `users` rows continue to use the standard Laravel `users.id` and
  Auth driver. `trans.user_id` is `unsignedSmallInteger` per the canonical
  migration; this is a pre-existing data-model design choice and is preserved
  to avoid changing the schema source of truth.
- Tests were not modified — only the application code that was broken.
- The two supplementary migrations
  (`2025_12_26_132437_add_account_photos_table` and
  `2025_12_26_135926_add_new_fields_to_account_infos_table`) are left as-is
  since `CLAUDE.md` documents these as additive deltas on top of the canonical
  migration and the corresponding model (`AccountInfo`) already exposes the
  added columns.
