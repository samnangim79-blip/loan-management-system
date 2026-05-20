<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This seeder creates sample customers, accounts, and loans for testing.
     */
    public function run(): void
    {
        $this->command->info('Creating sample customers, accounts, and loans...');

        // Run the seeders in dependency order
        $this->call([
            CustomerInfoSeeder::class,
            AccountInfoSeeder::class,
            LoanScheduleSeeder::class,
        ]);

        $this->command->info('Sample data created successfully!');
        $this->command->info('');
        $this->command->info('Summary:');
        $this->command->info('- Added 5 customers (IDs: 10-14)');
        $this->command->info('- Added 6 accounts (IDs: 10-15)');
        $this->command->info('- Added 6 loans (IDs: 10-15)');
        $this->command->info('');
        $this->command->info('You can now test the collateral management system with this data.');
    }
}
