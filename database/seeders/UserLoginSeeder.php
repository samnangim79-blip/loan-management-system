<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserLoginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert sample user login data
        DB::table('user_logins')->insert([
            [
                'user_id' => 1,
                'staff_id' => 1,
                'login_name' => 'jmanager',
                'password' => 'password123', // Plain text for legacy system
                'next_pwd_expire' => date('Y-m-d', strtotime('+90 days')),
                'failed_log' => 0,
                'log_ip' => '',
                'status' => 0, // Active
                'sys_cash_limit' => 1000000.00000,
                'profile_id' => 1, // Manager
                'branch_id' => 1,
            ],
            [
                'user_id' => 2,
                'staff_id' => 2,
                'login_name' => 'sloan',
                'password' => 'password123',
                'next_pwd_expire' => date('Y-m-d', strtotime('+90 days')),
                'failed_log' => 0,
                'log_ip' => '',
                'status' => 0, // Active
                'sys_cash_limit' => 200000.00000,
                'profile_id' => 3, // Loan Officer
                'branch_id' => 1,
            ],
            [
                'user_id' => 3,
                'staff_id' => 3,
                'login_name' => 'mteller',
                'password' => 'password123',
                'next_pwd_expire' => date('Y-m-d', strtotime('+90 days')),
                'failed_log' => 0,
                'log_ip' => '',
                'status' => 0, // Active
                'sys_cash_limit' => 100000.00000,
                'profile_id' => 4, // Teller
                'branch_id' => 1,
            ],
            [
                'user_id' => 4,
                'staff_id' => 4,
                'login_name' => 'lcashier',
                'password' => 'password123',
                'next_pwd_expire' => date('Y-m-d', strtotime('+90 days')),
                'failed_log' => 0,
                'log_ip' => '',
                'status' => 0, // Active
                'sys_cash_limit' => 50000.00000,
                'profile_id' => 5, // Cashier
                'branch_id' => 2,
            ],
            [
                'user_id' => 5,
                'staff_id' => 5,
                'login_name' => 'dsupervisor',
                'password' => 'password123',
                'next_pwd_expire' => date('Y-m-d', strtotime('+90 days')),
                'failed_log' => 0,
                'log_ip' => '',
                'status' => 0, // Active
                'sys_cash_limit' => 500000.00000,
                'profile_id' => 2, // Supervisor
                'branch_id' => 2,
            ],
            [
                'user_id' => 6,
                'staff_id' => 6,
                'login_name' => 'eanalyst',
                'password' => 'password123',
                'next_pwd_expire' => date('Y-m-d', strtotime('+90 days')),
                'failed_log' => 0,
                'log_ip' => '',
                'status' => 0, // Active
                'sys_cash_limit' => 0.00000,
                'profile_id' => 6, // Credit Analyst
                'branch_id' => 1,
            ],
            [
                'user_id' => 7,
                'staff_id' => 7,
                'login_name' => 'radmin',
                'password' => 'password123',
                'next_pwd_expire' => date('Y-m-d', strtotime('+90 days')),
                'failed_log' => 0,
                'log_ip' => '',
                'status' => 0, // Active
                'sys_cash_limit' => 75000.00000,
                'profile_id' => 7, // Admin Officer
                'branch_id' => 3,
            ],
            [
                'user_id' => 8,
                'staff_id' => 8,
                'login_name' => 'jcustomer',
                'password' => 'password123',
                'next_pwd_expire' => date('Y-m-d', strtotime('+90 days')),
                'failed_log' => 0,
                'log_ip' => '',
                'status' => 0, // Active
                'sys_cash_limit' => 25000.00000,
                'profile_id' => 4, // Teller level access
                'branch_id' => 2,
            ],
        ]);
    }
}
