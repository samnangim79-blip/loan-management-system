<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accountTypes = [
            // Deposit Accounts (category = 0)
            [
                'acct_type' => 'Savings Account',
                'ccy_id' => 1, // USD - from CurrencySeeder
                'resident' => 0, // Resident
                'withhold_tax' => 0.00,
                'gl_id' => null, // To be set when GL accounts are created
                'withhold_gl' => null,
                'accrued_int_gl' => null,
                'interest_gl' => null,
                'category' => 0 // deposit
            ],
            [
                'acct_type' => 'Current Account',
                'ccy_id' => 1, // USD
                'resident' => 0, // Resident
                'withhold_tax' => 0.00,
                'gl_id' => null,
                'withhold_gl' => null,
                'accrued_int_gl' => null,
                'interest_gl' => null,
                'category' => 0 // deposit
            ],
            [
                'acct_type' => 'Children Savings',
                'ccy_id' => 1, // USD
                'resident' => 0, // Resident
                'withhold_tax' => 0.00,
                'gl_id' => null,
                'withhold_gl' => null,
                'accrued_int_gl' => null,
                'interest_gl' => null,
                'category' => 0 // deposit
            ],
            [
                'acct_type' => 'KHR Savings Account',
                'ccy_id' => 2, // KHR - from CurrencySeeder
                'resident' => 0, // Resident
                'withhold_tax' => 0.00,
                'gl_id' => null,
                'withhold_gl' => null,
                'accrued_int_gl' => null,
                'interest_gl' => null,
                'category' => 0 // deposit
            ],

            // Term Deposit Accounts (category = 1)
            [
                'acct_type' => 'Fixed Deposit 3 Months',
                'ccy_id' => 1, // USD
                'resident' => 0, // Resident
                'withhold_tax' => 6.00, // 6% withholding tax on interest
                'gl_id' => null,
                'withhold_gl' => null,
                'accrued_int_gl' => null,
                'interest_gl' => null,
                'category' => 1 // term deposit
            ],
            [
                'acct_type' => 'Fixed Deposit 6 Months',
                'ccy_id' => 1, // USD
                'resident' => 0, // Resident
                'withhold_tax' => 6.00,
                'gl_id' => null,
                'withhold_gl' => null,
                'accrued_int_gl' => null,
                'interest_gl' => null,
                'category' => 1 // term deposit
            ],
            [
                'acct_type' => 'Fixed Deposit 12 Months',
                'ccy_id' => 1, // USD
                'resident' => 0, // Resident
                'withhold_tax' => 6.00,
                'gl_id' => null,
                'withhold_gl' => null,
                'accrued_int_gl' => null,
                'interest_gl' => null,
                'category' => 1 // term deposit
            ],

            // Loan Accounts (category = 2)
            [
                'acct_type' => 'Personal Loan',
                'ccy_id' => 1, // USD
                'resident' => 0, // Resident
                'withhold_tax' => 0.00,
                'gl_id' => null,
                'withhold_gl' => null,
                'accrued_int_gl' => null,
                'interest_gl' => null,
                'category' => 2 // loan
            ],
            [
                'acct_type' => 'Business Loan',
                'ccy_id' => 1, // USD
                'resident' => 0, // Resident
                'withhold_tax' => 0.00,
                'gl_id' => null,
                'withhold_gl' => null,
                'accrued_int_gl' => null,
                'interest_gl' => null,
                'category' => 2 // loan
            ],
            [
                'acct_type' => 'Agriculture Loan',
                'ccy_id' => 1, // USD
                'resident' => 0, // Resident
                'withhold_tax' => 0.00,
                'gl_id' => null,
                'withhold_gl' => null,
                'accrued_int_gl' => null,
                'interest_gl' => null,
                'category' => 2 // loan
            ],
            [
                'acct_type' => 'Micro Finance Loan',
                'ccy_id' => 1, // USD
                'resident' => 0, // Resident
                'withhold_tax' => 0.00,
                'gl_id' => null,
                'withhold_gl' => null,
                'accrued_int_gl' => null,
                'interest_gl' => null,
                'category' => 2 // loan
            ],
            [
                'acct_type' => 'Emergency Loan',
                'ccy_id' => 1, // USD
                'resident' => 0, // Resident
                'withhold_tax' => 0.00,
                'gl_id' => null,
                'withhold_gl' => null,
                'accrued_int_gl' => null,
                'interest_gl' => null,
                'category' => 2 // loan
            ],
            [
                'acct_type' => 'Group Loan',
                'ccy_id' => 1, // USD
                'resident' => 0, // Resident
                'withhold_tax' => 0.00,
                'gl_id' => null,
                'withhold_gl' => null,
                'accrued_int_gl' => null,
                'interest_gl' => null,
                'category' => 2 // loan
            ],

            // Non-Resident Accounts
            [
                'acct_type' => 'Non-Resident Savings',
                'ccy_id' => 1, // USD
                'resident' => 1, // Non-Resident
                'withhold_tax' => 10.00, // Higher withholding tax for non-residents
                'gl_id' => null,
                'withhold_gl' => null,
                'accrued_int_gl' => null,
                'interest_gl' => null,
                'category' => 0 // deposit
            ]
        ];

        // Insert account types
        foreach ($accountTypes as $accountType) {
            DB::table('account_types')->insert($accountType);
        }
    }
}
