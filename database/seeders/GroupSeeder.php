<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('group_details')->delete();
        DB::table('groups')->delete();

        // Create groups data
        $groups = [
            [
                'group_id' => 1,
                'group_name' => 'Small Business Group A',
                'date_issue' => '2025-01-15',
                'added_by' => 1,
                'added_date' => '2025-01-15',
                'updated_by' => null,
                'updated_date' => null,
            ],
            [
                'group_id' => 2,
                'group_name' => 'Agriculture Cooperative Group',
                'date_issue' => '2025-01-20',
                'added_by' => 1,
                'added_date' => '2025-01-20',
                'updated_by' => null,
                'updated_date' => null,
            ],
            [
                'group_id' => 3,
                'group_name' => 'Women Empowerment Group',
                'date_issue' => '2025-02-01',
                'added_by' => 1,
                'added_date' => '2025-02-01',
                'updated_by' => null,
                'updated_date' => null,
            ],
            [
                'group_id' => 4,
                'group_name' => 'Youth Entrepreneur Group',
                'date_issue' => '2025-02-10',
                'added_by' => 1,
                'added_date' => '2025-02-10',
                'updated_by' => null,
                'updated_date' => null,
            ],
            [
                'group_id' => 5,
                'group_name' => 'Rural Development Group B',
                'date_issue' => '2025-02-15',
                'added_by' => 1,
                'added_date' => '2025-02-15',
                'updated_by' => null,
                'updated_date' => null,
            ],
            [
                'group_id' => 6,
                'group_name' => 'Market Vendor Association',
                'date_issue' => '2025-03-01',
                'added_by' => 1,
                'added_date' => '2025-03-01',
                'updated_by' => null,
                'updated_date' => null,
            ],
            [
                'group_id' => 7,
                'group_name' => 'Handicraft Producer Group',
                'date_issue' => '2025-03-10',
                'added_by' => 1,
                'added_date' => '2025-03-10',
                'updated_by' => null,
                'updated_date' => null,
            ],
            [
                'group_id' => 8,
                'group_name' => 'Rice Farmer Collective',
                'date_issue' => '2025-03-15',
                'added_by' => 1,
                'added_date' => '2025-03-15',
                'updated_by' => null,
                'updated_date' => null,
            ],
        ];

        // Insert groups
        DB::table('groups')->insert($groups);

        // Create group details (linking groups with loan contracts)
        $groupDetails = [
            // Small Business Group A (Group ID: 1)
            [
                'group_detail_id' => 1,
                'group_id' => 1,
                'contract_no' => 'LN25-0010',
            ],
            [
                'group_detail_id' => 2,
                'group_id' => 1,
                'contract_no' => 'LN25-0011',
            ],
            [
                'group_detail_id' => 3,
                'group_id' => 1,
                'contract_no' => 'LN25-0012',
            ],

            // Agriculture Cooperative Group (Group ID: 2)
            [
                'group_detail_id' => 4,
                'group_id' => 2,
                'contract_no' => 'LN25-0013',
            ],
            [
                'group_detail_id' => 5,
                'group_id' => 2,
                'contract_no' => 'LN25-0014',
            ],
            [
                'group_detail_id' => 6,
                'group_id' => 2,
                'contract_no' => 'LN25-0015',
            ],
            [
                'group_detail_id' => 7,
                'group_id' => 2,
                'contract_no' => 'LN25-0016',
            ],

            // Women Empowerment Group (Group ID: 3)
            [
                'group_detail_id' => 8,
                'group_id' => 3,
                'contract_no' => 'LN25-0017',
            ],
            [
                'group_detail_id' => 9,
                'group_id' => 3,
                'contract_no' => 'LN25-0018',
            ],
            [
                'group_detail_id' => 10,
                'group_id' => 3,
                'contract_no' => 'LN25-0019',
            ],
            [
                'group_detail_id' => 11,
                'group_id' => 3,
                'contract_no' => 'LN25-0020',
            ],
            [
                'group_detail_id' => 12,
                'group_id' => 3,
                'contract_no' => 'LN25-0021',
            ],

            // Youth Entrepreneur Group (Group ID: 4)
            [
                'group_detail_id' => 13,
                'group_id' => 4,
                'contract_no' => 'LN25-0022',
            ],
            [
                'group_detail_id' => 14,
                'group_id' => 4,
                'contract_no' => 'LN25-0023',
            ],

            // Rural Development Group B (Group ID: 5)
            [
                'group_detail_id' => 15,
                'group_id' => 5,
                'contract_no' => 'LN25-0024',
            ],
            [
                'group_detail_id' => 16,
                'group_id' => 5,
                'contract_no' => 'LN25-0025',
            ],
            [
                'group_detail_id' => 17,
                'group_id' => 5,
                'contract_no' => 'LN25-0026',
            ],

            // Market Vendor Association (Group ID: 6)
            [
                'group_detail_id' => 18,
                'group_id' => 6,
                'contract_no' => 'LN25-0027',
            ],
            [
                'group_detail_id' => 19,
                'group_id' => 6,
                'contract_no' => 'LN25-0028',
            ],
            [
                'group_detail_id' => 20,
                'group_id' => 6,
                'contract_no' => 'LN25-0029',
            ],
            [
                'group_detail_id' => 21,
                'group_id' => 6,
                'contract_no' => 'LN25-0030',
            ],

            // Handicraft Producer Group (Group ID: 7)
            [
                'group_detail_id' => 22,
                'group_id' => 7,
                'contract_no' => 'LN25-0031',
            ],
            [
                'group_detail_id' => 23,
                'group_id' => 7,
                'contract_no' => 'LN25-0032',
            ],

            // Rice Farmer Collective (Group ID: 8)
            [
                'group_detail_id' => 24,
                'group_id' => 8,
                'contract_no' => 'LN25-0033',
            ],
            [
                'group_detail_id' => 25,
                'group_id' => 8,
                'contract_no' => 'LN25-0034',
            ],
            [
                'group_detail_id' => 26,
                'group_id' => 8,
                'contract_no' => 'LN25-0035',
            ],
        ];

        // Insert group details
        DB::table('group_details')->insert($groupDetails);

        $this->command->info('Groups and Group Details seeded successfully!');
        $this->command->info('Created 8 loan groups with ' . count($groupDetails) . ' associated loan contracts');
    }
}
