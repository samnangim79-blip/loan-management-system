<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CashMgtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert sample cash management data for testing
        $currentDate = now();
        $cashBalance = 0;

        $transactions = [
            [
                'tran_date' => $currentDate->copy()->subDays(30)->toDateString(),
                'amount' => 100000.00000,
                'in_out' => 'i', // in
                'ccy_id' => 1, // USD
                'user_id' => 1,
                'remark' => 'Opening cash balance',
            ],
            [
                'tran_date' => $currentDate->copy()->subDays(25)->toDateString(),
                'amount' => 25000.00000,
                'in_out' => 'o', // out
                'ccy_id' => 1,
                'user_id' => 1,
                'remark' => 'Loan disbursement',
            ],
            [
                'tran_date' => $currentDate->copy()->subDays(20)->toDateString(),
                'amount' => 15000.00000,
                'in_out' => 'i',
                'ccy_id' => 1,
                'user_id' => 2,
                'remark' => 'Customer deposit',
            ],
            [
                'tran_date' => $currentDate->copy()->subDays(18)->toDateString(),
                'amount' => 50000.00000,
                'in_out' => 'i',
                'ccy_id' => 2, // KHR
                'user_id' => 1,
                'remark' => 'Currency exchange',
            ],
            [
                'tran_date' => $currentDate->copy()->subDays(15)->toDateString(),
                'amount' => 8000.00000,
                'in_out' => 'o',
                'ccy_id' => 1,
                'user_id' => 3,
                'remark' => 'Customer withdrawal',
            ],
            [
                'tran_date' => $currentDate->copy()->subDays(10)->toDateString(),
                'amount' => 12000.00000,
                'in_out' => 'i',
                'ccy_id' => 1,
                'user_id' => 2,
                'remark' => 'Loan payment received',
            ],
            [
                'tran_date' => $currentDate->copy()->subDays(8)->toDateString(),
                'amount' => 30000.00000,
                'in_out' => 'i',
                'ccy_id' => 2,
                'user_id' => 1,
                'remark' => 'Bank transfer in',
            ],
            [
                'tran_date' => $currentDate->copy()->subDays(5)->toDateString(),
                'amount' => 20000.00000,
                'in_out' => 'o',
                'ccy_id' => 1,
                'user_id' => 4,
                'remark' => 'ATM replenishment',
            ],
            [
                'tran_date' => $currentDate->copy()->subDays(3)->toDateString(),
                'amount' => 5000.00000,
                'in_out' => 'i',
                'ccy_id' => 1,
                'user_id' => 3,
                'remark' => 'Fee collection',
            ],
            [
                'tran_date' => $currentDate->copy()->subDays(1)->toDateString(),
                'amount' => 7500.00000,
                'in_out' => 'o',
                'ccy_id' => 1,
                'user_id' => 2,
                'remark' => 'Operational expenses',
            ],
        ];

        $insertData = [];
        $cashMgtId = 1;

        foreach ($transactions as $transaction) {
            if ($transaction['in_out'] === 'i') {
                $cashBalance += $transaction['amount'];
            } else {
                $cashBalance -= $transaction['amount'];
            }

            $insertData[] = [
                'cash_mgt_id' => $cashMgtId++,
                'tran_date' => $transaction['tran_date'],
                'amount' => $transaction['amount'],
                'in_out' => $transaction['in_out'],
                'balance' => $cashBalance,
                'ccy_id' => $transaction['ccy_id'],
                'user_id' => $transaction['user_id'],
                'date_done' => now(),
                'remark' => $transaction['remark'],
            ];
        }

        DB::table('cash_mgts')->insert($insertData);
    }
}
