<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccessProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert sample access profile data
        DB::table('access_profiles')->insert([
            [
                'profile_id' => 1,
                'profile' => 'Manager',
                'deposit_limit' => 1000000.00000,
                'withdrawal_limit' => 500000.00000,
                'loan_limit' => 10000000.00000,
                'non_cash_limit' => 200000.00000,
            ],
            [
                'profile_id' => 2,
                'profile' => 'Supervisor',
                'deposit_limit' => 500000.00000,
                'withdrawal_limit' => 250000.00000,
                'loan_limit' => 5000000.00000,
                'non_cash_limit' => 100000.00000,
            ],
            [
                'profile_id' => 3,
                'profile' => 'Loan Officer',
                'deposit_limit' => 200000.00000,
                'withdrawal_limit' => 100000.00000,
                'loan_limit' => 2000000.00000,
                'non_cash_limit' => 50000.00000,
            ],
            [
                'profile_id' => 4,
                'profile' => 'Teller',
                'deposit_limit' => 100000.00000,
                'withdrawal_limit' => 50000.00000,
                'loan_limit' => 0.00000,
                'non_cash_limit' => 25000.00000,
            ],
            [
                'profile_id' => 5,
                'profile' => 'Cashier',
                'deposit_limit' => 50000.00000,
                'withdrawal_limit' => 25000.00000,
                'loan_limit' => 0.00000,
                'non_cash_limit' => 10000.00000,
            ],
            [
                'profile_id' => 6,
                'profile' => 'Credit Analyst',
                'deposit_limit' => 0.00000,
                'withdrawal_limit' => 0.00000,
                'loan_limit' => 3000000.00000,
                'non_cash_limit' => 0.00000,
            ],
            [
                'profile_id' => 7,
                'profile' => 'Admin Officer',
                'deposit_limit' => 75000.00000,
                'withdrawal_limit' => 37500.00000,
                'loan_limit' => 500000.00000,
                'non_cash_limit' => 15000.00000,
            ],
        ]);
    }
}
