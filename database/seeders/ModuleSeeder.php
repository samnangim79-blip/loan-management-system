<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert sample module data for access control
        DB::table('modules')->insert([
            [
                'module_id' => 1,
                'module' => 'Customer Management',
                'control_name' => 'customer',
                'url' => '/customers',
                'type' => 1,
                'status' => 0,
            ],
            [
                'module_id' => 2,
                'module' => 'Account Management',
                'control_name' => 'account',
                'url' => '/accounts',
                'type' => 1,
                'status' => 0,
            ],
            [
                'module_id' => 3,
                'module' => 'Loan Management',
                'control_name' => 'loan',
                'url' => '/loans',
                'type' => 1,
                'status' => 0,
            ],
            [
                'module_id' => 4,
                'module' => 'Cash Management',
                'control_name' => 'cash',
                'url' => '/cash',
                'type' => 1,
                'status' => 0,
            ],
            [
                'module_id' => 5,
                'module' => 'Deposit Management',
                'control_name' => 'deposit',
                'url' => '/deposits',
                'type' => 1,
                'status' => 0,
            ],
            [
                'module_id' => 6,
                'module' => 'Collateral Management',
                'control_name' => 'collateral',
                'url' => '/collaterals',
                'type' => 1,
                'status' => 0,
            ],
            [
                'module_id' => 7,
                'module' => 'Interest Calculation',
                'control_name' => 'interest',
                'url' => '/interest',
                'type' => 1,
                'status' => 0,
            ],
            [
                'module_id' => 8,
                'module' => 'Fixed Deposit',
                'control_name' => 'fixed_deposit',
                'url' => '/fixed-deposits',
                'type' => 1,
                'status' => 0,
            ],
            [
                'module_id' => 9,
                'module' => 'Cheque Management',
                'control_name' => 'cheque',
                'url' => '/cheques',
                'type' => 1,
                'status' => 0,
            ],
            [
                'module_id' => 10,
                'module' => 'Passbook Management',
                'control_name' => 'passbook',
                'url' => '/passbooks',
                'type' => 1,
                'status' => 0,
            ],
            [
                'module_id' => 11,
                'module' => 'Financial Reports',
                'control_name' => 'reports_fin',
                'url' => '/reports/financial',
                'type' => 1,
                'status' => 0,
            ],
            [
                'module_id' => 12,
                'module' => 'Regulatory Reports',
                'control_name' => 'reports_reg',
                'url' => '/reports/regulatory',
                'type' => 1,
                'status' => 0,
            ],
            [
                'module_id' => 13,
                'module' => 'User Management',
                'control_name' => 'user',
                'url' => '/users',
                'type' => 3,
                'status' => 0,
            ],
            [
                'module_id' => 14,
                'module' => 'System Configuration',
                'control_name' => 'config',
                'url' => '/config',
                'type' => 3,
                'status' => 0,
            ],
            [
                'module_id' => 15,
                'module' => 'Audit Trail',
                'control_name' => 'audit',
                'url' => '/audit',
                'type' => 1,
                'status' => 0,
            ],
        ]);
    }
}
