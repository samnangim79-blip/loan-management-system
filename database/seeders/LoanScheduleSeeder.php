<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LoanScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $loanSchedules = [
            [
                'loan_schedule_id' => 10,
                'contract_no' => 'LN25-0010',
                'acct_id' => 10,
                'date_issue' => '2025-02-01',
                'frequency_id' => 4, // Monthly
                'last_pay_date' => null,
                'next_pay_date' => '2025-03-01',
                'tenor' => 24,
                'amount' => 10000.00,
                'os_balance' => 10000.00,
                'int_rate' => 15.00,
                'extra_rate' => null,
                'interest_mode' => 0,
                'payment_mode' => 0,
                'savings' => null,
                'credit_to_acct' => null,
                'auto_pay_from_acct' => null,
                'user_id' => 1,
                'approved_date' => '2025-01-20',
                'approved_by' => 1,
                'purpose_id' => 1,
                'remark' => 'Personal loan for education',
                'end_pay_date' => '2027-02-01',
                'next_date' => null,
                'gl_credit' => null
            ],
            [
                'loan_schedule_id' => 11,
                'contract_no' => 'LN25-0011',
                'acct_id' => 11,
                'date_issue' => '2025-01-25',
                'frequency_id' => 4, // Monthly
                'last_pay_date' => null,
                'next_pay_date' => '2025-02-25',
                'tenor' => 36,
                'amount' => 25000.00,
                'os_balance' => 25000.00,
                'int_rate' => 12.50,
                'extra_rate' => 0.50,
                'interest_mode' => 0,
                'payment_mode' => 0,
                'savings' => 1000.00,
                'credit_to_acct' => null,
                'auto_pay_from_acct' => null,
                'user_id' => 1,
                'approved_date' => '2025-01-20',
                'approved_by' => 1,
                'purpose_id' => 2,
                'remark' => 'Business expansion loan',
                'end_pay_date' => '2028-01-25',
                'next_date' => null,
                'gl_credit' => null
            ],
            [
                'loan_schedule_id' => 12,
                'contract_no' => 'LN25-0012',
                'acct_id' => 12,
                'date_issue' => '2025-02-05',
                'frequency_id' => 2, // Weekly
                'last_pay_date' => null,
                'next_pay_date' => '2025-02-12',
                'tenor' => 52,
                'amount' => 5000.00,
                'os_balance' => 5000.00,
                'int_rate' => 18.00,
                'extra_rate' => null,
                'interest_mode' => 1,
                'payment_mode' => 1,
                'savings' => 200.00,
                'credit_to_acct' => null,
                'auto_pay_from_acct' => null,
                'user_id' => 1,
                'approved_date' => '2025-02-01',
                'approved_by' => 1,
                'purpose_id' => 3,
                'remark' => 'Agricultural micro loan',
                'end_pay_date' => '2026-02-05',
                'next_date' => null,
                'gl_credit' => null
            ],
            [
                'loan_schedule_id' => 13,
                'contract_no' => 'LN25-0013',
                'acct_id' => 13,
                'date_issue' => '2025-02-15',
                'frequency_id' => 3, // Bi-weekly
                'last_pay_date' => null,
                'next_pay_date' => '2025-03-01',
                'tenor' => 24,
                'amount' => 15000.00,
                'os_balance' => 15000.00,
                'int_rate' => 14.00,
                'extra_rate' => null,
                'interest_mode' => 0,
                'payment_mode' => 0,
                'savings' => 500.00,
                'credit_to_acct' => null,
                'auto_pay_from_acct' => null,
                'user_id' => 1,
                'approved_date' => '2025-02-10',
                'approved_by' => 1,
                'purpose_id' => 4,
                'remark' => 'Home improvement loan',
                'end_pay_date' => '2027-02-15',
                'next_date' => null,
                'gl_credit' => null
            ],
            [
                'loan_schedule_id' => 14,
                'contract_no' => 'LN25-0014',
                'acct_id' => 14,
                'date_issue' => '2025-02-20',
                'frequency_id' => 4, // Monthly
                'last_pay_date' => null,
                'next_pay_date' => '2025-03-20',
                'tenor' => 18,
                'amount' => 8000.00,
                'os_balance' => 8000.00,
                'int_rate' => 16.00,
                'extra_rate' => null,
                'interest_mode' => 0,
                'payment_mode' => 0,
                'savings' => 300.00,
                'credit_to_acct' => null,
                'auto_pay_from_acct' => null,
                'user_id' => 1,
                'approved_date' => '2025-02-15',
                'approved_by' => 1,
                'purpose_id' => 2,
                'remark' => 'Shop inventory financing',
                'end_pay_date' => '2026-08-20',
                'next_date' => null,
                'gl_credit' => null
            ],
            [
                'loan_schedule_id' => 15,
                'contract_no' => 'LN25-0015',
                'acct_id' => 15,
                'date_issue' => '2025-02-25',
                'frequency_id' => 4, // Monthly
                'last_pay_date' => null,
                'next_pay_date' => '2025-03-25',
                'tenor' => 30,
                'amount' => 20000.00,
                'os_balance' => 20000.00,
                'int_rate' => 13.50,
                'extra_rate' => null,
                'interest_mode' => 0,
                'payment_mode' => 0,
                'savings' => 800.00,
                'credit_to_acct' => null,
                'auto_pay_from_acct' => null,
                'user_id' => 1,
                'approved_date' => '2025-02-20',
                'approved_by' => 1,
                'purpose_id' => 1,
                'remark' => 'Personal loan for education',
                'end_pay_date' => '2027-08-25',
                'next_date' => null,
                'gl_credit' => null
            ]
        ];

        foreach ($loanSchedules as $loan) {
            DB::table('loan_schedules')->insert($loan);
        }
    }
}
