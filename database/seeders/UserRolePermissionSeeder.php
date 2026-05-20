<?php

namespace Database\Seeders;

use App\Models\AccessProfile;
use App\Models\AccessProfileDetail;
use App\Models\Module;
use App\Models\User;
use App\Models\UserLogin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeds: Modules, Access profiles, profile Details, and Sample Users
     */
    public function run(): void
    {
        $this->seedModules();
        $this->seedAccessprofiles();
        $this->seedSampleUsers();

        $this->command->info('User roles and permissions seeded successfully!');
    }

    /**
     * Seed all system modules (permissions)
     */
    protected function seedModules(): void
    {
        $modules = [
            // ========================================
            // Dashboard & Reports (1-9)
            // ========================================
            ['module_id' => 1, 'module' => 'Dashboard', 'control_name' => 'dashboard_view', 'url' => '/dashboard', 'type' => 1, 'status' => 0],
            ['module_id' => 2, 'module' => 'Reports', 'control_name' => 'report_view', 'url' => '/reports', 'type' => 1, 'status' => 0],
            ['module_id' => 3, 'module' => 'Export Reports', 'control_name' => 'report_export', 'url' => '/reports/export', 'type' => 1, 'status' => 0],

            // ========================================
            // Customer Management (10-19)
            // ========================================
            ['module_id' => 10, 'module' => 'Customer List', 'control_name' => 'customer_view', 'url' => '/customers', 'type' => 1, 'status' => 0],
            ['module_id' => 11, 'module' => 'Add Customer', 'control_name' => 'customer_add', 'url' => '/customers/create', 'type' => 1, 'status' => 0],
            ['module_id' => 12, 'module' => 'Edit Customer', 'control_name' => 'customer_edit', 'url' => '/customers/edit', 'type' => 1, 'status' => 0],
            ['module_id' => 13, 'module' => 'Delete Customer', 'control_name' => 'customer_delete', 'url' => '/customers/delete', 'type' => 1, 'status' => 0],
            ['module_id' => 14, 'module' => 'Customer Groups', 'control_name' => 'customer_group_view', 'url' => '/groups', 'type' => 1, 'status' => 0],
            ['module_id' => 15, 'module' => 'Customer Assets', 'control_name' => 'customer_asset_view', 'url' => '/customer-assets', 'type' => 1, 'status' => 0],

            // ========================================
            // Account Management (20-29)
            // ========================================
            ['module_id' => 20, 'module' => 'Account List', 'control_name' => 'account_view', 'url' => '/accounts', 'type' => 1, 'status' => 0],
            ['module_id' => 21, 'module' => 'Add Account', 'control_name' => 'account_add', 'url' => '/accounts/create', 'type' => 1, 'status' => 0],
            ['module_id' => 22, 'module' => 'Edit Account', 'control_name' => 'account_edit', 'url' => '/accounts/edit', 'type' => 1, 'status' => 0],
            ['module_id' => 23, 'module' => 'Delete Account', 'control_name' => 'account_delete', 'url' => '/accounts/delete', 'type' => 1, 'status' => 0],
            ['module_id' => 24, 'module' => 'Account Types', 'control_name' => 'account_type_view', 'url' => '/account-types', 'type' => 3, 'status' => 0],
            ['module_id' => 25, 'module' => 'Joint Accounts', 'control_name' => 'joint_account_view', 'url' => '/joint-accounts', 'type' => 1, 'status' => 0],

            // ========================================
            // Loan Management (30-49)
            // ========================================
            ['module_id' => 30, 'module' => 'Loan List', 'control_name' => 'loan_view', 'url' => '/loans', 'type' => 1, 'status' => 0],
            ['module_id' => 31, 'module' => 'Add Loan', 'control_name' => 'loan_add', 'url' => '/loans/create', 'type' => 1, 'status' => 0],
            ['module_id' => 32, 'module' => 'Edit Loan', 'control_name' => 'loan_edit', 'url' => '/loans/edit', 'type' => 1, 'status' => 0],
            ['module_id' => 33, 'module' => 'Delete Loan', 'control_name' => 'loan_delete', 'url' => '/loans/delete', 'type' => 1, 'status' => 0],
            ['module_id' => 34, 'module' => 'Approve Loan', 'control_name' => 'loan_approve', 'url' => '/loans/approve', 'type' => 1, 'status' => 0],
            ['module_id' => 35, 'module' => 'Disburse Loan', 'control_name' => 'loan_disburse', 'url' => '/loans/disburse', 'type' => 1, 'status' => 0],
            ['module_id' => 36, 'module' => 'Loan Schedules', 'control_name' => 'loan_schedule_view', 'url' => '/loan-schedules', 'type' => 1, 'status' => 0],
            ['module_id' => 37, 'module' => 'Loan Arrears', 'control_name' => 'loan_arrear_view', 'url' => '/loan-arrears', 'type' => 1, 'status' => 0],
            ['module_id' => 38, 'module' => 'Loan Purpose', 'control_name' => 'loan_purpose_view', 'url' => '/purpose-loans', 'type' => 3, 'status' => 0],
            ['module_id' => 39, 'module' => 'Payment Frequency', 'control_name' => 'payment_frequency_view', 'url' => '/payment-frequencies', 'type' => 3, 'status' => 0],

            // ========================================
            // Transaction Management (50-69)
            // ========================================
            ['module_id' => 50, 'module' => 'Transaction List', 'control_name' => 'transaction_view', 'url' => '/transactions', 'type' => 1, 'status' => 0],
            ['module_id' => 51, 'module' => 'Deposit', 'control_name' => 'deposit', 'url' => '/transactions/deposit', 'type' => 2, 'status' => 0],
            ['module_id' => 52, 'module' => 'Withdrawal', 'control_name' => 'withdrawal', 'url' => '/transactions/withdrawal', 'type' => 2, 'status' => 0],
            ['module_id' => 53, 'module' => 'Loan Payment', 'control_name' => 'loan_payment', 'url' => '/transactions/loan-payment', 'type' => 2, 'status' => 0],
            ['module_id' => 54, 'module' => 'Transfer', 'control_name' => 'transfer', 'url' => '/transactions/transfer', 'type' => 2, 'status' => 0],
            ['module_id' => 55, 'module' => 'Cash Management', 'control_name' => 'cash_manage', 'url' => '/cash-management', 'type' => 2, 'status' => 0],
            ['module_id' => 56, 'module' => 'Void Transaction', 'control_name' => 'transaction_void', 'url' => '/transactions/void', 'type' => 3, 'status' => 0],
            ['module_id' => 57, 'module' => 'Reverse Transaction', 'control_name' => 'transaction_reverse', 'url' => '/transactions/reverse', 'type' => 3, 'status' => 0],

            // ========================================
            // Collateral Management (70-79)
            // ========================================
            ['module_id' => 70, 'module' => 'Collateral List', 'control_name' => 'collateral_view', 'url' => '/collaterals', 'type' => 1, 'status' => 0],
            ['module_id' => 71, 'module' => 'Add Collateral', 'control_name' => 'collateral_add', 'url' => '/collaterals/create', 'type' => 1, 'status' => 0],
            ['module_id' => 72, 'module' => 'Edit Collateral', 'control_name' => 'collateral_edit', 'url' => '/collaterals/edit', 'type' => 1, 'status' => 0],
            ['module_id' => 73, 'module' => 'Delete Collateral', 'control_name' => 'collateral_delete', 'url' => '/collaterals/delete', 'type' => 1, 'status' => 0],
            ['module_id' => 74, 'module' => 'Collateral Types', 'control_name' => 'collateral_type_view', 'url' => '/collateral-types', 'type' => 3, 'status' => 0],

            // ========================================
            // Fixed Deposit (80-89)
            // ========================================
            ['module_id' => 80, 'module' => 'Fixed Deposit List', 'control_name' => 'fixed_deposit_view', 'url' => '/fixed-deposits', 'type' => 1, 'status' => 0],
            ['module_id' => 81, 'module' => 'Add Fixed Deposit', 'control_name' => 'fixed_deposit_add', 'url' => '/fixed-deposits/create', 'type' => 1, 'status' => 0],
            ['module_id' => 82, 'module' => 'Manage Fixed Deposit', 'control_name' => 'fixed_deposit_edit', 'url' => '/fixed-deposits/edit', 'type' => 1, 'status' => 0],
            ['module_id' => 83, 'module' => 'Withdraw Fixed Deposit', 'control_name' => 'fixed_deposit_withdraw', 'url' => '/fixed-deposits/withdraw', 'type' => 1, 'status' => 0],

            // ========================================
            // General Ledger (90-99)
            // ========================================
            ['module_id' => 90, 'module' => 'Chart of Accounts', 'control_name' => 'gl_view', 'url' => '/gl', 'type' => 3, 'status' => 0],
            ['module_id' => 91, 'module' => 'Add GL Account', 'control_name' => 'gl_add', 'url' => '/gl/create', 'type' => 3, 'status' => 0],
            ['module_id' => 92, 'module' => 'Edit GL Account', 'control_name' => 'gl_edit', 'url' => '/gl/edit', 'type' => 3, 'status' => 0],
            ['module_id' => 93, 'module' => 'GL Mapping', 'control_name' => 'gl_mapping_view', 'url' => '/gl-mappings', 'type' => 3, 'status' => 0],
            ['module_id' => 94, 'module' => 'Journal Entries', 'control_name' => 'journal_view', 'url' => '/journals', 'type' => 3, 'status' => 0],
            ['module_id' => 95, 'module' => 'Add Journal Entry', 'control_name' => 'journal_add', 'url' => '/journals/create', 'type' => 3, 'status' => 0],

            // ========================================
            // Fixed Assets (100-109)
            // ========================================
            ['module_id' => 100, 'module' => 'Fixed Asset List', 'control_name' => 'fixed_asset_view', 'url' => '/fixed-assets', 'type' => 3, 'status' => 0],
            ['module_id' => 101, 'module' => 'Add Fixed Asset', 'control_name' => 'fixed_asset_add', 'url' => '/fixed-assets/create', 'type' => 3, 'status' => 0],
            ['module_id' => 102, 'module' => 'Edit Fixed Asset', 'control_name' => 'fixed_asset_edit', 'url' => '/fixed-assets/edit', 'type' => 3, 'status' => 0],
            ['module_id' => 103, 'module' => 'Asset Types', 'control_name' => 'asset_type_view', 'url' => '/asset-types', 'type' => 3, 'status' => 0],
            ['module_id' => 104, 'module' => 'Depreciation', 'control_name' => 'depreciation_view', 'url' => '/depreciation', 'type' => 3, 'status' => 0],

            // ========================================
            // Staff Management (110-119)
            // ========================================
            ['module_id' => 110, 'module' => 'Staff List', 'control_name' => 'staff_view', 'url' => '/staff', 'type' => 3, 'status' => 0],
            ['module_id' => 111, 'module' => 'Add Staff', 'control_name' => 'staff_add', 'url' => '/staff/create', 'type' => 3, 'status' => 0],
            ['module_id' => 112, 'module' => 'Edit Staff', 'control_name' => 'staff_edit', 'url' => '/staff/edit', 'type' => 3, 'status' => 0],
            ['module_id' => 113, 'module' => 'Delete Staff', 'control_name' => 'staff_delete', 'url' => '/staff/delete', 'type' => 3, 'status' => 0],

            // ========================================
            // User Management (120-129)
            // ========================================
            ['module_id' => 120, 'module' => 'User List', 'control_name' => 'user_view', 'url' => '/users', 'type' => 3, 'status' => 0],
            ['module_id' => 121, 'module' => 'Add User', 'control_name' => 'user_add', 'url' => '/users/create', 'type' => 3, 'status' => 0],
            ['module_id' => 122, 'module' => 'Edit User', 'control_name' => 'user_edit', 'url' => '/users/edit', 'type' => 3, 'status' => 0],
            ['module_id' => 123, 'module' => 'Delete User', 'control_name' => 'user_delete', 'url' => '/users/delete', 'type' => 3, 'status' => 0],
            ['module_id' => 124, 'module' => 'Reset Password', 'control_name' => 'user_reset_password', 'url' => '/users/reset-password', 'type' => 3, 'status' => 0],

            // ========================================
            // Access profiles (130-139)
            // ========================================
            ['module_id' => 130, 'module' => 'Access profile List', 'control_name' => 'access_profile_view', 'url' => '/access-profiles', 'type' => 3, 'status' => 0],
            ['module_id' => 131, 'module' => 'Add Access profile', 'control_name' => 'access_profile_add', 'url' => '/access-profiles/create', 'type' => 3, 'status' => 0],
            ['module_id' => 132, 'module' => 'Edit Access profile', 'control_name' => 'access_profile_edit', 'url' => '/access-profiles/edit', 'type' => 3, 'status' => 0],
            ['module_id' => 133, 'module' => 'Delete Access profile', 'control_name' => 'access_profile_delete', 'url' => '/access-profiles/delete', 'type' => 3, 'status' => 0],

            // ========================================
            // Branch Management (140-149)
            // ========================================
            ['module_id' => 140, 'module' => 'Branch List', 'control_name' => 'branch_view', 'url' => '/branches', 'type' => 3, 'status' => 0],
            ['module_id' => 141, 'module' => 'Add Branch', 'control_name' => 'branch_add', 'url' => '/branches/create', 'type' => 3, 'status' => 0],
            ['module_id' => 142, 'module' => 'Edit Branch', 'control_name' => 'branch_edit', 'url' => '/branches/edit', 'type' => 3, 'status' => 0],
            ['module_id' => 143, 'module' => 'Delete Branch', 'control_name' => 'branch_delete', 'url' => '/branches/delete', 'type' => 3, 'status' => 0],
            ['module_id' => 144, 'module' => 'Branch Transactions', 'control_name' => 'branch_tran_view', 'url' => '/branch-transactions', 'type' => 3, 'status' => 0],

            // ========================================
            // System Settings (150-169)
            // ========================================
            ['module_id' => 150, 'module' => 'System Config', 'control_name' => 'config_view', 'url' => '/config', 'type' => 3, 'status' => 0],
            ['module_id' => 151, 'module' => 'Edit Config', 'control_name' => 'config_edit', 'url' => '/config/edit', 'type' => 3, 'status' => 0],
            ['module_id' => 152, 'module' => 'Currencies', 'control_name' => 'currency_view', 'url' => '/currencies', 'type' => 3, 'status' => 0],
            ['module_id' => 153, 'module' => 'Add Currency', 'control_name' => 'currency_add', 'url' => '/currencies/create', 'type' => 3, 'status' => 0],
            ['module_id' => 154, 'module' => 'Countries', 'control_name' => 'country_view', 'url' => '/countries', 'type' => 3, 'status' => 0],
            ['module_id' => 155, 'module' => 'Public Holidays', 'control_name' => 'holiday_view', 'url' => '/holidays', 'type' => 3, 'status' => 0],
            ['module_id' => 156, 'module' => 'Nationalities', 'control_name' => 'nationality_view', 'url' => '/nationalities', 'type' => 3, 'status' => 0],

            // ========================================
            // Location Management (170-179)
            // ========================================
            ['module_id' => 170, 'module' => 'Provinces', 'control_name' => 'province_view', 'url' => '/provinces', 'type' => 3, 'status' => 0],
            ['module_id' => 171, 'module' => 'Districts', 'control_name' => 'district_view', 'url' => '/districts', 'type' => 3, 'status' => 0],
            ['module_id' => 172, 'module' => 'Communes', 'control_name' => 'commune_view', 'url' => '/communes', 'type' => 3, 'status' => 0],
            ['module_id' => 173, 'module' => 'Villages', 'control_name' => 'village_view', 'url' => '/villages', 'type' => 3, 'status' => 0],

            // ========================================
            // Audit & Logs (180-189)
            // ========================================
            ['module_id' => 180, 'module' => 'Audit Logs', 'control_name' => 'audit_view', 'url' => '/audit-logs', 'type' => 3, 'status' => 0],
            ['module_id' => 181, 'module' => 'Login History', 'control_name' => 'login_history_view', 'url' => '/login-history', 'type' => 3, 'status' => 0],
            ['module_id' => 182, 'module' => 'System Logs', 'control_name' => 'system_log_view', 'url' => '/system-logs', 'type' => 3, 'status' => 0],

            // ========================================
            // Backup & Maintenance (190-199)
            // ========================================
            ['module_id' => 190, 'module' => 'Database Backup', 'control_name' => 'backup_view', 'url' => '/backup', 'type' => 3, 'status' => 0],
            ['module_id' => 191, 'module' => 'Create Backup', 'control_name' => 'backup_create', 'url' => '/backup/create', 'type' => 3, 'status' => 0],
            ['module_id' => 192, 'module' => 'Restore Backup', 'control_name' => 'backup_restore', 'url' => '/backup/restore', 'type' => 3, 'status' => 0],
            ['module_id' => 193, 'module' => 'Day End Process', 'control_name' => 'day_end', 'url' => '/day-end', 'type' => 3, 'status' => 0],
            ['module_id' => 194, 'module' => 'Month End Process', 'control_name' => 'month_end', 'url' => '/month-end', 'type' => 3, 'status' => 0],
            ['module_id' => 195, 'module' => 'Year End Process', 'control_name' => 'year_end', 'url' => '/year-end', 'type' => 3, 'status' => 0],
        ];

        foreach ($modules as $module) {
            Module::updateOrCreate(
                ['module_id' => $module['module_id']],
                $module
            );
        }

        $this->command->info('Seeded '.count($modules).' modules (permissions)');
    }

    /**
     * Seed access profiles (roles) with their permissions
     */
    protected function seedAccessprofiles(): void
    {
        $profiles = [
            // ========================================
            // Super Admin - Full access to everything
            // ========================================
            [
                'profile_id' => 1,
                'profile' => 'Super Admin',
                'deposit_limit' => 0, // 0 = unlimited
                'withdrawal_limit' => 0,
                'loan_limit' => 0,
                'non_cash_limit' => 0,
                'modules' => 'all', // Special flag for all modules
            ],

            // ========================================
            // Branch Manager - Full branch operations
            // ========================================
            [
                'profile_id' => 2,
                'profile' => 'Branch Manager',
                'deposit_limit' => 100000,
                'withdrawal_limit' => 50000,
                'loan_limit' => 500000,
                'non_cash_limit' => 200000,
                'modules' => [
                    // Dashboard & Reports
                    1,
                    2,
                    3,
                    // Customer Management
                    10,
                    11,
                    12,
                    13,
                    14,
                    15,
                    // Account Management
                    20,
                    21,
                    22,
                    23,
                    25,
                    // Loan Management
                    30,
                    31,
                    32,
                    33,
                    34,
                    35,
                    36,
                    37,
                    // Transactions
                    50,
                    51,
                    52,
                    53,
                    54,
                    55,
                    56,
                    // Collateral
                    70,
                    71,
                    72,
                    73,
                    // Fixed Deposit
                    80,
                    81,
                    82,
                    83,
                    // Staff (view/add only)
                    110,
                    111,
                    // Cash Management
                    144,
                ],
            ],

            // ========================================
            // Loan Officer - Loan focused operations
            // ========================================
            [
                'profile_id' => 3,
                'profile' => 'Loan Officer',
                'deposit_limit' => 10000,
                'withdrawal_limit' => 5000,
                'loan_limit' => 100000,
                'non_cash_limit' => 50000,
                'modules' => [
                    // Dashboard & Reports
                    1,
                    2,
                    // Customer Management
                    10,
                    11,
                    12,
                    14,
                    15,
                    // Account Management
                    20,
                    21,
                    22,
                    // Loan Management (core focus)
                    30,
                    31,
                    32,
                    34,
                    36,
                    37,
                    // Limited Transactions
                    50,
                    53,
                    // Collateral (view/add)
                    70,
                    71,
                    72,
                ],
            ],

            // ========================================
            // Senior Teller - Enhanced transaction access
            // ========================================
            [
                'profile_id' => 4,
                'profile' => 'Senior Teller',
                'deposit_limit' => 20000,
                'withdrawal_limit' => 15000,
                'loan_limit' => 0,
                'non_cash_limit' => 30000,
                'modules' => [
                    // Dashboard
                    1,
                    // Customer (view only)
                    10,
                    // Account (view only)
                    20,
                    // Transactions (all types)
                    50,
                    51,
                    52,
                    53,
                    54,
                    55,
                    // Cash Management
                    55,
                ],
            ],

            // ========================================
            // Teller - Basic transaction operations
            // ========================================
            [
                'profile_id' => 5,
                'profile' => 'Teller',
                'deposit_limit' => 5000,
                'withdrawal_limit' => 5000,
                'loan_limit' => 0,
                'non_cash_limit' => 10000,
                'modules' => [
                    // Dashboard
                    1,
                    // Customer (view only)
                    10,
                    // Account (view only)
                    20,
                    // Basic Transactions
                    50,
                    51,
                    52,
                    53,
                    54,
                ],
            ],

            // ========================================
            // Cashier - Limited cash operations
            // ========================================
            [
                'profile_id' => 6,
                'profile' => 'Cashier',
                'deposit_limit' => 2000,
                'withdrawal_limit' => 2000,
                'loan_limit' => 0,
                'non_cash_limit' => 5000,
                'modules' => [
                    // Dashboard
                    1,
                    // Customer (view only)
                    10,
                    // Account (view only)
                    20,
                    // Limited Transactions
                    50,
                    51,
                    52,
                ],
            ],

            // ========================================
            // Auditor - View only access
            // ========================================
            [
                'profile_id' => 7,
                'profile' => 'Auditor',
                'deposit_limit' => 0,
                'withdrawal_limit' => 0,
                'loan_limit' => 0,
                'non_cash_limit' => 0,
                'modules' => [
                    // Dashboard & Reports
                    1,
                    2,
                    3,
                    // View access to all operational modules
                    10,
                    20,
                    30,
                    36,
                    37,
                    50,
                    70,
                    80,
                    // General Ledger
                    90,
                    93,
                    94,
                    // Audit & Logs
                    180,
                    181,
                    182,
                ],
            ],

            // ========================================
            // Accountant - Finance focused
            // ========================================
            [
                'profile_id' => 8,
                'profile' => 'Accountant',
                'deposit_limit' => 0,
                'withdrawal_limit' => 0,
                'loan_limit' => 0,
                'non_cash_limit' => 0,
                'modules' => [
                    // Dashboard & Reports
                    1,
                    2,
                    3,
                    // View operational data
                    10,
                    20,
                    30,
                    50,
                    // General Ledger (full access)
                    90,
                    91,
                    92,
                    93,
                    94,
                    95,
                    // Fixed Assets
                    100,
                    101,
                    102,
                    103,
                    104,
                    // Day/Month/Year End
                    193,
                    194,
                    195,
                ],
            ],

            // ========================================
            // Customer Service - Client facing
            // ========================================
            [
                'profile_id' => 9,
                'profile' => 'Customer Service',
                'deposit_limit' => 0,
                'withdrawal_limit' => 0,
                'loan_limit' => 0,
                'non_cash_limit' => 0,
                'modules' => [
                    // Dashboard
                    1,
                    // Customer (full CRUD)
                    10,
                    11,
                    12,
                    14,
                    // Account (view/add)
                    20,
                    21,
                    // View loans
                    30,
                    36,
                    // View transactions
                    50,
                ],
            ],

            // ========================================
            // IT Admin - System maintenance
            // ========================================
            [
                'profile_id' => 10,
                'profile' => 'IT Admin',
                'deposit_limit' => 0,
                'withdrawal_limit' => 0,
                'loan_limit' => 0,
                'non_cash_limit' => 0,
                'modules' => [
                    // Dashboard
                    1,
                    // User Management
                    120,
                    121,
                    122,
                    123,
                    124,
                    // Access profiles
                    130,
                    131,
                    132,
                    133,
                    // System Settings
                    150,
                    151,
                    152,
                    153,
                    154,
                    155,
                    156,
                    // Location Management
                    170,
                    171,
                    172,
                    173,
                    // Audit & Logs
                    180,
                    181,
                    182,
                    // Backup & Maintenance
                    190,
                    191,
                    192,
                ],
            ],
        ];

        // Get all module IDs for super admin
        $allModuleIds = Module::pluck('module_id')->toArray();

        foreach ($profiles as $profileData) {
            $moduleIds = $profileData['modules'];
            unset($profileData['modules']);

            $profile = AccessProfile::updateOrCreate(
                ['profile_id' => $profileData['profile_id']],
                $profileData
            );

            // Clear existing profile details
            AccessProfileDetail::where('profile_id', $profile->profile_id)->delete();

            // Add new profile details
            $modules = ($moduleIds === 'all') ? $allModuleIds : $moduleIds;
            foreach ($modules as $moduleId) {
                AccessProfileDetail::create([
                    'profile_id' => $profile->profile_id,
                    'module_id' => $moduleId,
                ]);
            }
        }

        $this->command->info('Seeded '.count($profiles).' access profiles (roles)');
    }

    /**
     * Seed sample users with different roles
     */
    protected function seedSampleUsers(): void
    {
        // Update the default Laravel User table users
        $users = [
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'username' => 'superadmin',
                'email' => 'superadmin@loan.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'is_verified' => true,
            ],
            [
                'first_name' => 'Branch',
                'last_name' => 'Manager',
                'username' => 'manager',
                'email' => 'manager@loan.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'is_verified' => true,
            ],
            [
                'first_name' => 'Loan',
                'last_name' => 'Officer',
                'username' => 'loanofficer',
                'email' => 'loanofficer@loan.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'is_verified' => true,
            ],
            [
                'first_name' => 'Teller',
                'last_name' => 'User',
                'username' => 'teller',
                'email' => 'teller@loan.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'is_verified' => true,
            ],
            [
                'first_name' => 'Auditor',
                'last_name' => 'User',
                'username' => 'auditor',
                'email' => 'auditor@loan.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'is_verified' => true,
            ],
            [
                'first_name' => 'Senior',
                'last_name' => 'Teller',
                'username' => 'seniorteller',
                'email' => 'seniorteller@loan.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'is_verified' => true,
            ],
            [
                'first_name' => 'Cashier',
                'last_name' => 'User',
                'username' => 'cashier',
                'email' => 'cashier@loan.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'is_verified' => true,
            ],
            [
                'first_name' => 'Accountant',
                'last_name' => 'User',
                'username' => 'accountant',
                'email' => 'accountant@loan.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'is_verified' => true,
            ],
            [
                'first_name' => 'Customer',
                'last_name' => 'Service',
                'username' => 'customerservice',
                'email' => 'customerservice@loan.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'is_verified' => true,
            ],
            [
                'first_name' => 'IT',
                'last_name' => 'Admin',
                'username' => 'itadmin',
                'email' => 'itadmin@loan.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'is_verified' => true,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        // Check if branches exist (to avoid FK constraint issues)
        $branchExists = DB::table('branchs')->where('branch_id', 1)->exists();
        $defaultBranchId = $branchExists ? 1 : null;

        // Seed UserLogin entries (legacy system users)
        $userLogins = [
            [
                'user_id' => 1,
                'login_name' => 'superadmin',
                'password' => 'password', // In production, use Hash
                'status' => 0,
                'profile_id' => 1, // Super Admin
                'branch_id' => $defaultBranchId,
            ],
            [
                'user_id' => 2,
                'login_name' => 'manager',
                'password' => 'password',
                'status' => 0,
                'profile_id' => 2, // Branch Manager
                'branch_id' => $defaultBranchId,
            ],
            [
                'user_id' => 3,
                'login_name' => 'loanofficer',
                'password' => 'password',
                'status' => 0,
                'profile_id' => 3, // Loan Officer
                'branch_id' => $defaultBranchId,
            ],
            [
                'user_id' => 4,
                'login_name' => 'seniorteller',
                'password' => 'password',
                'status' => 0,
                'profile_id' => 4, // Senior Teller
                'branch_id' => $defaultBranchId,
            ],
            [
                'user_id' => 5,
                'login_name' => 'teller',
                'password' => 'password',
                'status' => 0,
                'profile_id' => 5, // Teller
                'branch_id' => $defaultBranchId,
            ],
            [
                'user_id' => 6,
                'login_name' => 'cashier',
                'password' => 'password',
                'status' => 0,
                'profile_id' => 6, // Cashier
                'branch_id' => $defaultBranchId,
            ],
            [
                'user_id' => 7,
                'login_name' => 'auditor',
                'password' => 'password',
                'status' => 0,
                'profile_id' => 7, // Auditor
                'branch_id' => null, // Head office
            ],
            [
                'user_id' => 8,
                'login_name' => 'accountant',
                'password' => 'password',
                'status' => 0,
                'profile_id' => 8, // Accountant
                'branch_id' => null, // Head office
            ],
            [
                'user_id' => 9,
                'login_name' => 'customerservice',
                'password' => 'password',
                'status' => 0,
                'profile_id' => 9, // Customer Service
                'branch_id' => $defaultBranchId,
            ],
            [
                'user_id' => 10,
                'login_name' => 'itadmin',
                'password' => 'password',
                'status' => 0,
                'profile_id' => 10, // IT Admin
                'branch_id' => null, // Head office
            ],
        ];

        foreach ($userLogins as $userData) {
            UserLogin::updateOrCreate(
                ['user_id' => $userData['user_id']],
                $userData
            );
        }

        $this->command->info('Seeded '.count($users).' Laravel users and '.count($userLogins).' UserLogin entries');
    }
}
