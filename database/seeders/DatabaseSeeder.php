<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\AccessProfileSeeder;
use Database\Seeders\AccessProfileDetailSeeder;
use Database\Seeders\AccountInfoSeeder;
use Database\Seeders\AccountTypesSeeder;
use Database\Seeders\BranchSeeder;
use Database\Seeders\CashMgtSeeder;
use Database\Seeders\CollateralSeeder;
use Database\Seeders\CollateralTypeSeeder;
use Database\Seeders\CommunesTableSeeder;
use Database\Seeders\ConfigSeeder;
use Database\Seeders\CountrySeeder;
use Database\Seeders\CurrencySeeder;
use Database\Seeders\CustomerInfoSeeder;
use Database\Seeders\DistrictsTableSeeder;
use Database\Seeders\FdSeeder;
use Database\Seeders\GlSeeder;
use Database\Seeders\GroupSeeder;
use Database\Seeders\LoanScheduleSeeder;
use Database\Seeders\ModuleSeeder;
use Database\Seeders\NationalitySeeder;
use Database\Seeders\PaymentFrequencySeeder;
use Database\Seeders\PenddingCashTransferSeeder;
use Database\Seeders\ProvincesTableSeeder;
use Database\Seeders\PurposeLoanSeeder;
use Database\Seeders\StaffSeeder;
use Database\Seeders\TransSeeder;
use Database\Seeders\UserLoginSeeder;
use Database\Seeders\UserRolePermissionSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\VillagesTableSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call seeders in order (dependencies first)
        $this->call([
            // Core system data (no dependencies)
            UserSeeder::class,              // Laravel users for authentication
            CountrySeeder::class,           // Country data
            ProvincesTableSeeder::class,    // Province data
            DistrictsTableSeeder::class,    // District data
            CommunesTableSeeder::class,     // Commune data
            VillagesTableSeeder::class,     // Village data

            // Branch and staff data (depends on locations)
            BranchSeeder::class,            // Branch data
            StaffSeeder::class,             // Staff data (depends on branches)

            // Access control data (depends on staff)
            AccessProfileSeeder::class,     // Access profiles
            ModuleSeeder::class,            // System modules
            AccessProfileDetailSeeder::class, // Profile-module mappings
            UserLoginSeeder::class,         // User login credentials (depends on staff + profiles)

            // User roles and permissions
            UserRolePermissionSeeder::class,

            // System configuration
            ConfigSeeder::class,

            // Reference data
            NationalitySeeder::class,       // Nationality data
            CurrencySeeder::class,          // Currency data

            // Financial system setup
            GlSeeder::class,                // General Ledger accounts
            AccountTypesSeeder::class,      // Account types (depends on GL + currency)

            // Loan system setup
            PurposeLoanSeeder::class,       // Loan purposes
            PaymentFrequencySeeder::class,  // Payment frequencies
            CollateralTypeSeeder::class,    // Collateral types

            // Fixed deposit setup
            FdSeeder::class,                // Fixed deposit terms and options

            // Sample transactional data (depends on above setup)
            CustomerInfoSeeder::class,      // Customer data
            AccountInfoSeeder::class,       // Account data (depends on customers + types)
            LoanScheduleSeeder::class,      // Loan data (depends on accounts + frequencies)
            GroupSeeder::class,             // Group data (depends on loan schedules)
            CollateralSeeder::class,        // Collateral data (depends on loans + types)
            CashMgtSeeder::class,           // Cash management data
            PenddingCashTransferSeeder::class, // Pending cash transfers
            TransSeeder::class,             // Transaction data
        ]);
    }
}
