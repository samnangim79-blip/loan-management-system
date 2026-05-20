<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccessProfileDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define access profile details (which modules each profile can access)
        $profileModules = [
            // Manager (profile_id = 1) - Full access
            1 => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15],

            // Supervisor (profile_id = 2) - Most access except system config
            2 => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 15],

            // Loan Officer (profile_id = 3) - Loan and customer focused
            3 => [1, 2, 3, 6, 7, 11, 15],

            // Teller (profile_id = 4) - Basic transaction access
            4 => [1, 2, 4, 5, 9, 10, 15],

            // Cashier (profile_id = 5) - Cash management focused
            5 => [4, 5, 9, 10, 15],

            // Credit Analyst (profile_id = 6) - Analysis and reporting
            6 => [1, 3, 6, 7, 11, 12, 15],

            // Admin Officer (profile_id = 7) - Administrative access
            7 => [1, 2, 9, 10, 11, 13, 14, 15],
        ];

        $insertData = [];
        $profileDetailId = 1;

        foreach ($profileModules as $profileId => $modules) {
            foreach ($modules as $moduleId) {
                $insertData[] = [
                    'profile_detail_id' => $profileDetailId++,
                    'profile_id' => $profileId,
                    'module_id' => $moduleId,
                ];
            }
        }

        // Insert access profile details
        DB::table('access_profile_details')->insert($insertData);
    }
}
