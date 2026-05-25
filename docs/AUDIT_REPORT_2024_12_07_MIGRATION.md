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
