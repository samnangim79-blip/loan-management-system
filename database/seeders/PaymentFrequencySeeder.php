<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PaymentFrequencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();

        // Clear existing data
        DB::table('payment_frequencys')->truncate();

        // Insert payment frequencies
        $frequencies = [
            ['frequency_id' => 1, 'frequency' => 'Daily', 'num_days' => 1],
            ['frequency_id' => 2, 'frequency' => 'Weekly', 'num_days' => 7],
            ['frequency_id' => 3, 'frequency' => 'Bi-Weekly', 'num_days' => 14],
            ['frequency_id' => 4, 'frequency' => 'Monthly', 'num_days' => 30],
            ['frequency_id' => 5, 'frequency' => 'Bi-Monthly', 'num_days' => 60],
            ['frequency_id' => 6, 'frequency' => 'Quarterly', 'num_days' => 90],
            ['frequency_id' => 7, 'frequency' => 'Semi-Annual', 'num_days' => 180],
            ['frequency_id' => 8, 'frequency' => 'Annual', 'num_days' => 365],
            ['frequency_id' => 9, 'frequency' => 'Fortnightly', 'num_days' => 15],
            ['frequency_id' => 10, 'frequency' => 'Every 10 Days', 'num_days' => 10],
        ];

        DB::table('payment_frequencys')->insert($frequencies);

        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();

        $this->command->info('Payment frequencies seeded successfully.');
    }
}
