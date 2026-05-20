<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CollateralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * បង្កើតទិន្នន័យគំរូសម្រាប់តារាងបញ្ចុះសំណាង
     */
    public function run(): void
    {
        $collaterals = [
            [
                'loan_schedule_id' => 1,
                'collateral_type_id' => 1, // ដី/ផ្ទះ
                'collateral_value' => 50000000, // 50,000 USD
                'collateral_no' => 'COL-001-2024',
                'date_issue' => '2024-01-15',
                'remarks' => 'ដីលក្ខណៈបុរីខេត្តកំពត ទំហំ 20x30 ម៉ែត្រ មានបណ្ណកម្មសិទ្ធិច្បាប់ស្រប',
            ],
            [
                'loan_schedule_id' => 1,
                'collateral_type_id' => 2, // រថយន្ត
                'collateral_value' => 15000000, // 15,000 USD
                'collateral_no' => 'COL-002-2024',
                'date_issue' => '2024-01-15',
                'remarks' => 'រថយន្ត Toyota Camry ឆ្នាំ 2020 ស្ទាតវាស់លេខ 1234567890',
            ],
            [
                'loan_schedule_id' => 2,
                'collateral_type_id' => 3, // មាស
                'collateral_value' => 8000000, // 8,000 USD
                'collateral_no' => 'COL-003-2024',
                'date_issue' => '2024-02-01',
                'remarks' => 'មាសខ្សែរកក្រវាត់ទម្ងន់ 50 ចិកម៉ាសស្អាតភាពស្អាត 99.9%',
            ],
            [
                'loan_schedule_id' => 3,
                'collateral_type_id' => 1, // ដី/ផ្ទះ
                'collateral_value' => 80000000, // 80,000 USD
                'collateral_no' => 'COL-004-2024',
                'date_issue' => '2024-02-10',
                'remarks' => 'ផ្ទះបេតុងជាន់ទី 2 ផ្លូវធំសង្កាត់ទួលកុក ខណ្ឌទួលកុក រាជធានីភ្នំពេញ',
            ],
            [
                'loan_schedule_id' => 4,
                'collateral_type_id' => 4, // គ្រឿងអេឡិចត្រូនិច
                'collateral_value' => 3000000, // 3,000 USD
                'collateral_no' => 'COL-005-2024',
                'date_issue' => '2024-02-20',
                'remarks' => 'ម៉ាស៊ីនបោកគក់ SAMSUNG ទំហំធំ និងទូទឹកកក LG ម៉ាក្រុមហ៊ុន',
            ],
            [
                'loan_schedule_id' => 5,
                'collateral_type_id' => 2, // រថយន្ត
                'collateral_value' => 25000000, // 25,000 USD
                'collateral_no' => 'COL-006-2024',
                'date_issue' => '2024-03-01',
                'remarks' => 'រថយន្តកាំុងធំ Ford Ranger ឆ្នាំ 2022 ចុះបញ្ជីគ្រប់គ្រាន់',
            ],
            [
                'loan_schedule_id' => 6,
                'collateral_type_id' => 5, // ម្ទ្រព្យផ្សេងៗ
                'collateral_value' => 12000000, // 12,000 USD
                'collateral_no' => 'COL-007-2024',
                'date_issue' => '2024-03-15',
                'remarks' => 'គ្រឿងសង្ហារិមហាងលក់ម្ហូប រួមទាំងម៉ាស៊ីនចំហុយ និងតុកែង',
            ],
            [
                'loan_schedule_id' => 7,
                'collateral_type_id' => 3, // មាស
                'collateral_value' => 5000000, // 5,000 USD
                'collateral_no' => 'COL-008-2024',
                'date_issue' => '2024-04-01',
                'remarks' => 'មាសក្រវាត់ និងកែវពេជ្រទម្ងន់ 30 ចិកធុរកិច្ចតម្លៃខ្ពស់',
            ],
            [
                'loan_schedule_id' => 8,
                'collateral_type_id' => 1, // ដី/ផ្ទះ
                'collateral_value' => 120000000, // 120,000 USD
                'collateral_no' => 'COL-009-2024',
                'date_issue' => '2024-04-10',
                'remarks' => 'ដីសាងសង់លក្ខណៈពាណិជ្ជកម្មផ្លូវជាតិលេខ 1 ទំហំ 50x80 ម៉ែត្រ',
            ],
            [
                'loan_schedule_id' => 9,
                'collateral_type_id' => 4, // គ្រឿងអេឡិចត្រូនិច
                'collateral_value' => 7000000, // 7,000 USD
                'collateral_no' => 'COL-010-2024',
                'date_issue' => '2024-04-25',
                'remarks' => 'គ្រឿងកុំព្យូទ័រ និងបរិកម្មការិយាល័យទំនើប ម៉ាកដើមថ្មី',
            ],
            [
                'loan_schedule_id' => 10,
                'collateral_type_id' => 2, // រថយន្ត
                'collateral_value' => 18000000, // 18,000 USD
                'collateral_no' => 'COL-011-2024',
                'date_issue' => '2024-05-05',
                'remarks' => 'ម៉ូតូជិះបីកង្វះ Honda Dream ចំនួន 3 គ្រឿង ស្ទាតសុទ្ធ',
            ],
            [
                'loan_schedule_id' => 11,
                'collateral_type_id' => 5, // ម្ទ្រព្យផ្សេងៗ
                'collateral_value' => 15000000, // 15,000 USD
                'collateral_no' => 'COL-012-2024',
                'date_issue' => '2024-05-20',
                'remarks' => 'ស្ទុកទំនិញក្នុងរោងចក្រ រួមទាំងម្ហូបអាហារ និងគ្រឿងទេស',
            ],
            [
                'loan_schedule_id' => 12,
                'collateral_type_id' => 1, // ដី/ផ្ទះ
                'collateral_value' => 90000000, // 90,000 USD
                'collateral_no' => 'COL-013-2024',
                'date_issue' => '2024-06-01',
                'remarks' => 'ខុនដូ 3 បន្ទប់គេង ក្នុងកម្រងផ្កាថ្មី តំបន់ពោធិ៍សែនចី',
            ],
            [
                'loan_schedule_id' => 13,
                'collateral_type_id' => 3, // មាស
                'collateral_value' => 6000000, // 6,000 USD
                'collateral_no' => 'COL-014-2024',
                'date_issue' => '2024-06-15',
                'remarks' => 'មាសម៉ាក្រុមហ៊ុនល្បីៗ ទម្ងន់ 35 ចិក មានបណ្ណសម្គាល់គុណភាព',
            ],
            [
                'loan_schedule_id' => 14,
                'collateral_type_id' => 4, // គ្រឿងអេឡិចត្រូនិច
                'collateral_value' => 4000000, // 4,000 USD
                'collateral_no' => 'COL-015-2024',
                'date_issue' => '2024-07-01',
                'remarks' => 'ទូទឹកកក មីក្រូវ៉េវ និងម៉ាស៊ីនបោកគក់ ម៉ាកល្បីតំលៃខ្ពស់',
            ]
        ];

        foreach ($collaterals as $collateral) {
            DB::table('collaterals')->insert([
                'loan_schedule_id' => $collateral['loan_schedule_id'],
                'collateral_type_id' => $collateral['collateral_type_id'],
                'collateral_value' => $collateral['collateral_value'],
                'collateral_no' => $collateral['collateral_no'],
                'date_issue' => $collateral['date_issue'],
                'remarks' => $collateral['remarks'],
            ]);
        }
    }
}
