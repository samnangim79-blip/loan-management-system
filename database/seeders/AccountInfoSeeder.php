<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = [
            [
                'acct_id' => 10,
                'cust_id' => 10,
                'acct_name' => 'Michael Johnson Savings',
                'acct_no' => '0000010',
                'acct_type_id' => 2, // Assuming 2 is savings account
                'joint_flag' => 0,
                'mandatory' => 'Mandatory Field',
                'account_status' => 1, // Active
                'branch_id' => 1,
                'opened_date' => '2025-01-15',
                'opened_by' => 1,
                'last_withdraw_date' => null,
                'close_date' => null,
                'close_by' => null
            ],
            [
                'acct_id' => 11,
                'cust_id' => 11,
                'acct_name' => 'Sarah Wilson Business',
                'acct_no' => '0000011',
                'acct_type_id' => 3, // Business account
                'joint_flag' => 1,
                'mandatory' => 'Business Account',
                'account_status' => 1, // Active
                'branch_id' => 1,
                'opened_date' => '2025-01-20',
                'opened_by' => 1,
                'last_withdraw_date' => '2025-02-10',
                'close_date' => null,
                'close_by' => null
            ],
            [
                'acct_id' => 12,
                'cust_id' => 12,
                'acct_name' => 'Sophea Chan Micro Finance',
                'acct_no' => '0000012',
                'acct_type_id' => 4, // Micro finance account
                'joint_flag' => 0,
                'mandatory' => 'Micro Finance',
                'account_status' => 1, // Active
                'branch_id' => 2,
                'opened_date' => '2025-02-01',
                'opened_by' => 1,
                'last_withdraw_date' => null,
                'close_date' => null,
                'close_by' => null
            ],
            [
                'acct_id' => 13,
                'cust_id' => 13,
                'acct_name' => 'David Wong Loan Account',
                'acct_no' => '0000013',
                'acct_type_id' => 2, // Savings account
                'joint_flag' => 1,
                'mandatory' => 'Joint Account',
                'account_status' => 1, // Active
                'branch_id' => 1,
                'opened_date' => '2025-02-10',
                'opened_by' => 1,
                'last_withdraw_date' => '2025-02-20',
                'close_date' => null,
                'close_by' => null
            ],
            [
                'acct_id' => 14,
                'cust_id' => 14,
                'acct_name' => 'Kimly Pheach Shop',
                'acct_no' => '0000014',
                'acct_type_id' => 3, // Business account
                'joint_flag' => 0,
                'mandatory' => 'Business Account',
                'account_status' => 1, // Active
                'branch_id' => 2,
                'opened_date' => '2025-02-15',
                'opened_by' => 1,
                'last_withdraw_date' => null,
                'close_date' => null,
                'close_by' => null
            ],
            [
                'acct_id' => 15,
                'cust_id' => 10,
                'acct_name' => 'Michael Johnson Loan Account',
                'acct_no' => '0000015',
                'acct_type_id' => 5, // Loan account
                'joint_flag' => 0,
                'mandatory' => 'Loan Account',
                'account_status' => 1, // Active
                'branch_id' => 1,
                'opened_date' => '2025-02-20',
                'opened_by' => 1,
                'last_withdraw_date' => null,
                'close_date' => null,
                'close_by' => null
            ]
        ];

        foreach ($accounts as $account) {
            DB::table('account_infos')->insert($account);
        }
    }
}
