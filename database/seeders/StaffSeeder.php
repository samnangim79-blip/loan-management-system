<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert sample staff data
        DB::table('staffs')->insert([
            [
                'staff_id' => 1,
                'ic_no' => 'IC001234567',
                'full_name' => 'John Manager',
                'gender' => 'M',
                'dob' => '1980-05-15',
                'pob' => 'Phnom Penh',
                'address' => 'Street 123, Phnom Penh',
                'phone' => '+855-12-123456',
                'position' => 'Branch Manager',
                'branch_id' => 1,
            ],
            [
                'staff_id' => 2,
                'ic_no' => 'IC002345678',
                'full_name' => 'Sarah Loan Officer',
                'gender' => 'F',
                'dob' => '1985-08-22',
                'pob' => 'Siem Reap',
                'address' => 'Street 456, Phnom Penh',
                'phone' => '+855-12-234567',
                'position' => 'Loan Officer',
                'branch_id' => 1,
            ],
            [
                'staff_id' => 3,
                'ic_no' => 'IC003456789',
                'full_name' => 'Michael Teller',
                'gender' => 'M',
                'dob' => '1990-03-10',
                'pob' => 'Battambang',
                'address' => 'Street 789, Phnom Penh',
                'phone' => '+855-12-345678',
                'position' => 'Teller',
                'branch_id' => 1,
            ],
            [
                'staff_id' => 4,
                'ic_no' => 'IC004567890',
                'full_name' => 'Lisa Cashier',
                'gender' => 'F',
                'dob' => '1988-12-05',
                'pob' => 'Kampong Cham',
                'address' => 'Street 101, Phnom Penh',
                'phone' => '+855-12-456789',
                'position' => 'Cashier',
                'branch_id' => 2,
            ],
            [
                'staff_id' => 5,
                'ic_no' => 'IC005678901',
                'full_name' => 'David Supervisor',
                'gender' => 'M',
                'dob' => '1983-07-18',
                'pob' => 'Kandal',
                'address' => 'Street 202, Phnom Penh',
                'phone' => '+855-12-567890',
                'position' => 'Supervisor',
                'branch_id' => 2,
            ],
            [
                'staff_id' => 6,
                'ic_no' => 'IC006789012',
                'full_name' => 'Emma Analyst',
                'gender' => 'F',
                'dob' => '1992-01-25',
                'pob' => 'Prey Veng',
                'address' => 'Street 303, Phnom Penh',
                'phone' => '+855-12-678901',
                'position' => 'Credit Analyst',
                'branch_id' => 1,
            ],
            [
                'staff_id' => 7,
                'ic_no' => 'IC007890123',
                'full_name' => 'Robert Admin',
                'gender' => 'M',
                'dob' => '1986-09-14',
                'pob' => 'Takeo',
                'address' => 'Street 404, Phnom Penh',
                'phone' => '+855-12-789012',
                'position' => 'Admin Officer',
                'branch_id' => 3,
            ],
            [
                'staff_id' => 8,
                'ic_no' => 'IC008901234',
                'full_name' => 'Jenny Customer Service',
                'gender' => 'F',
                'dob' => '1991-11-08',
                'pob' => 'Kampot',
                'address' => 'Street 505, Phnom Penh',
                'phone' => '+855-12-890123',
                'position' => 'Customer Service',
                'branch_id' => 2,
            ],
        ]);
    }
}
