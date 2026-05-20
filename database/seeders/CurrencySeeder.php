<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            [
                'currency' => 'USD',
                'ccy_rate' => 1.00000, // Base currency
                'round_value' => 0,
                'decimal_place' => 2,
                'compare_value' => 0,
                'value_format' => 0
            ],
            [
                'currency' => 'KHR',
                'ccy_rate' => 4100.00000, // 1 USD = 4100 KHR
                'round_value' => 100, // Round to nearest 100 for KHR
                'decimal_place' => 0, // No decimal places for KHR
                'compare_value' => 0,
                'value_format' => 0
            ],
            [
                'currency' => 'THB',
                'ccy_rate' => 33.50000, // 1 USD = 33.5 THB
                'round_value' => 0,
                'decimal_place' => 2,
                'compare_value' => 0,
                'value_format' => 0
            ]
        ];

        // Insert currencies
        foreach ($currencies as $currency) {
            DB::table('currencys')->insert($currency);
        }
    }
}
