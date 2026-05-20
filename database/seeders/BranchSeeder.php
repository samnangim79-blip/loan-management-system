<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert sample branch data
        DB::table('branchs')->insert([
            [
                'branch_id' => 1,
                'branch_name' => 'Main Branch',
                'phone' => '+855-23-123456',
                'email' => 'main@loanmgt.com',
                'website' => 'www.loanmgt.com',
            ],
            [
                'branch_id' => 2,
                'branch_name' => 'Downtown Branch',
                'phone' => '+855-23-234567',
                'email' => 'downtown@loanmgt.com',
                'website' => 'www.loanmgt.com',
            ],
            [
                'branch_id' => 3,
                'branch_name' => 'Airport Branch',
                'phone' => '+855-23-345678',
                'email' => 'airport@loanmgt.com',
                'website' => 'www.loanmgt.com',
            ],
            [
                'branch_id' => 4,
                'branch_name' => 'Central Market Branch',
                'phone' => '+855-23-456789',
                'email' => 'market@loanmgt.com',
                'website' => 'www.loanmgt.com',
            ],
            [
                'branch_id' => 5,
                'branch_name' => 'University Branch',
                'phone' => '+855-23-567890',
                'email' => 'university@loanmgt.com',
                'website' => 'www.loanmgt.com',
            ],
        ]);
    }
}
