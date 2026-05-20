<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert Fixed Deposit Options
        DB::table('fd_options')->insert([
            [
                'fd_option_id' => 1,
                'fd_option' => 'Principal + Interest to Savings',
            ],
            [
                'fd_option_id' => 2,
                'fd_option' => 'Interest to Savings, Principal Rollover',
            ],
            [
                'fd_option_id' => 3,
                'fd_option' => 'Auto Rollover (Principal + Interest)',
            ],
            [
                'fd_option_id' => 4,
                'fd_option' => 'Interest to Cash, Principal Rollover',
            ],
            [
                'fd_option_id' => 5,
                'fd_option' => 'Principal + Interest to Cash',
            ],
        ]);

        // Insert Fixed Deposit Terms
        DB::table('fd_terms')->insert([
            [
                'fd_term_id' => 1,
                'term_name' => '1 Month',
                'days_num' => 30,
                'int_rate' => 2.50,
                'grace_period' => 3,
                'break_term_fee' => 50.00,
            ],
            [
                'fd_term_id' => 2,
                'term_name' => '3 Months',
                'days_num' => 90,
                'int_rate' => 3.25,
                'grace_period' => 7,
                'break_term_fee' => 100.00,
            ],
            [
                'fd_term_id' => 3,
                'term_name' => '6 Months',
                'days_num' => 180,
                'int_rate' => 4.50,
                'grace_period' => 15,
                'break_term_fee' => 200.00,
            ],
            [
                'fd_term_id' => 4,
                'term_name' => '12 Months',
                'days_num' => 365,
                'int_rate' => 6.00,
                'grace_period' => 30,
                'break_term_fee' => 500.00,
            ],
            [
                'fd_term_id' => 5,
                'term_name' => '18 Months',
                'days_num' => 548,
                'int_rate' => 7.25,
                'grace_period' => 45,
                'break_term_fee' => 750.00,
            ],
            [
                'fd_term_id' => 6,
                'term_name' => '24 Months',
                'days_num' => 730,
                'int_rate' => 8.50,
                'grace_period' => 60,
                'break_term_fee' => 1000.00,
            ],
            [
                'fd_term_id' => 7,
                'term_name' => '36 Months',
                'days_num' => 1095,
                'int_rate' => 9.75,
                'grace_period' => 90,
                'break_term_fee' => 1500.00,
            ],
        ]);

        // Insert sample Fixed Deposit Certificates
        DB::table('fd_certs')->insert([
            [
                'fd_cert_id' => 1001,
                'acct_id' => 1, // Assuming account exists from AccountInfoSeeder
                'date_issue' => '2024-01-15',
                'matured_date' => '2024-07-15',
                'amount' => 10000.00000,
                'int_rate' => 4.50,
                'extra_rate' => 0.25,
                'fd_option_id' => 1,
                'fd_term_id' => 3,
                'acct_for_int' => '1000001',
                'acct_for_prin' => '1000001',
                'future_dep_date' => null,
                'done_by' => 'SYSTEM',
            ],
            [
                'fd_cert_id' => 1002,
                'acct_id' => 2,
                'date_issue' => '2024-02-01',
                'matured_date' => '2025-02-01',
                'amount' => 25000.00000,
                'int_rate' => 6.00,
                'extra_rate' => 0.50,
                'fd_option_id' => 2,
                'fd_term_id' => 4,
                'acct_for_int' => '1000002',
                'acct_for_prin' => '1000002',
                'future_dep_date' => null,
                'done_by' => 'SYSTEM',
            ],
            [
                'fd_cert_id' => 1003,
                'acct_id' => 3,
                'date_issue' => '2024-03-10',
                'matured_date' => '2025-09-10',
                'amount' => 50000.00000,
                'int_rate' => 7.25,
                'extra_rate' => 0.75,
                'fd_option_id' => 3,
                'fd_term_id' => 5,
                'acct_for_int' => '1000003',
                'acct_for_prin' => '1000003',
                'future_dep_date' => null,
                'done_by' => 'SYSTEM',
            ],
            [
                'fd_cert_id' => 1004,
                'acct_id' => 4,
                'date_issue' => '2024-05-20',
                'matured_date' => '2026-05-20',
                'amount' => 75000.00000,
                'int_rate' => 8.50,
                'extra_rate' => 1.00,
                'fd_option_id' => 1,
                'fd_term_id' => 6,
                'acct_for_int' => '1000004',
                'acct_for_prin' => '1000004',
                'future_dep_date' => null,
                'done_by' => 'SYSTEM',
            ],
            [
                'fd_cert_id' => 1005,
                'acct_id' => 5,
                'date_issue' => '2024-06-01',
                'matured_date' => '2027-06-01',
                'amount' => 100000.00000,
                'int_rate' => 9.75,
                'extra_rate' => 1.25,
                'fd_option_id' => 2,
                'fd_term_id' => 7,
                'acct_for_int' => '1000005',
                'acct_for_prin' => '1000005',
                'future_dep_date' => null,
                'done_by' => 'SYSTEM',
            ],
        ]);

        // Insert FD Transaction status
        DB::table('fd_trans')->insert([
            [
                'fd_tran_id' => 1,
                'fd_cert_id' => 1001,
                'status' => 'ACTIVE',
            ],
            [
                'fd_tran_id' => 2,
                'fd_cert_id' => 1002,
                'status' => 'ACTIVE',
            ],
            [
                'fd_tran_id' => 3,
                'fd_cert_id' => 1003,
                'status' => 'ACTIVE',
            ],
            [
                'fd_tran_id' => 4,
                'fd_cert_id' => 1004,
                'status' => 'ACTIVE',
            ],
            [
                'fd_tran_id' => 5,
                'fd_cert_id' => 1005,
                'status' => 'ACTIVE',
            ],
        ]);

        // Insert sample future deposits
        DB::table('fd_future_deps')->insert([
            [
                'fd_dep_id' => 1,
                'fd_cert_id' => 1002,
                'amount' => 5000.00000,
                'date_to_dep' => '2024-08-01',
                'date_done' => null,
            ],
            [
                'fd_dep_id' => 2,
                'fd_cert_id' => 1003,
                'amount' => 10000.00000,
                'date_to_dep' => '2024-09-10',
                'date_done' => null,
            ],
        ]);
    }
}
