<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CollateralTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * បង្កើតទិន្នន័យគំរូសម្រាប់តារាងប្រភេទបញ្ចុះសំណាង
     */
    public function run(): void
    {
        $collateralTypes = [
            [
                'collateral_type_id' => 1,
                'collateral_type' => 'ដី/ផ្ទះ',
            ],
            [
                'collateral_type_id' => 2,
                'collateral_type' => 'រថយន្ត',
            ],
            [
                'collateral_type_id' => 3,
                'collateral_type' => 'មាស',
            ],
            [
                'collateral_type_id' => 4,
                'collateral_type' => 'គ្រឿងអេឡិចត្រូនិច',
            ],
            [
                'collateral_type_id' => 5,
                'collateral_type' => 'ម្ទ្រព្យផ្សេងៗ',
            ],
            [
                'collateral_type_id' => 6,
                'collateral_type' => 'គ្រឿងប្រើប្រាស់',
            ],
            [
                'collateral_type_id' => 7,
                'collateral_type' => 'ម៉ាស៊ីនកម្មវិធី',
            ],
            [
                'collateral_type_id' => 8,
                'collateral_type' => 'សត្វពាហនៈ',
            ],
            [
                'collateral_type_id' => 9,
                'collateral_type' => 'ផលិតផលកសិកម្ម',
            ],
            [
                'collateral_type_id' => 10,
                'collateral_type' => 'គ្រឿងអលង្កា',
            ],
        ];

        foreach ($collateralTypes as $type) {
            DB::table('collateral_types')->insert([
                'collateral_type_id' => $type['collateral_type_id'],
                'collateral_type' => $type['collateral_type'],
            ]);
        }
    }
}
