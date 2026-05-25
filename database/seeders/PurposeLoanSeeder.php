<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PurposeLoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();

        // Clear existing data
        DB::table('purpose_loans')->truncate();

        // Insert loan purposes
        $purposes = [
            ['purpose_id' => 1, 'purpose_type' => 'Business/Trade'],
            ['purpose_id' => 2, 'purpose_type' => 'Agriculture'],
            ['purpose_id' => 3, 'purpose_type' => 'Education'],
            ['purpose_id' => 4, 'purpose_type' => 'Medical/Health'],
            ['purpose_id' => 5, 'purpose_type' => 'Housing/Construction'],
            ['purpose_id' => 6, 'purpose_type' => 'Personal/Consumption'],
            ['purpose_id' => 7, 'purpose_type' => 'Equipment Purchase'],
            ['purpose_id' => 8, 'purpose_type' => 'Working Capital'],
            ['purpose_id' => 9, 'purpose_type' => 'Emergency'],
            ['purpose_id' => 10, 'purpose_type' => 'Vehicle Purchase'],
            ['purpose_id' => 11, 'purpose_type' => 'Wedding/Ceremony'],
            ['purpose_id' => 12, 'purpose_type' => 'Home Improvement'],
            ['purpose_id' => 13, 'purpose_type' => 'Livestock'],
            ['purpose_id' => 14, 'purpose_type' => 'Small Enterprise'],
            ['purpose_id' => 15, 'purpose_type' => 'Debt Consolidation'],
        ];

        DB::table('purpose_loans')->insert($purposes);

        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();

        $this->command->info('Purpose loans seeded successfully.');
    }
}
