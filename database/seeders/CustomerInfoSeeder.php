<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'cust_id' => 10,
                'id_no' => '310',
                'name_en' => 'Michael Johnson',
                'name_kh' => 'មីកែល ជនសុន',
                'gender' => 'M',
                'marital_status' => 1,
                'dob' => '1990-05-15',
                'pob' => 'Siemreap',
                'phone1' => '070393173',
                'phone2' => '010393173',
                'phone3' => null,
                'address' => '6st, Siemreap, Cambodia',
                'village_id' => 678,
                'occupation' => 'Teacher',
                'email' => 'michael.johnson@example.com',
                'spouse_id_no' => null,
                'spouse_name_en' => null,
                'spouse_name_kh' => null,
                'spouse_dob' => null,
                'staff_id' => null,
                'remark' => 'Regular customer',
                'created_by' => 1,
                'created_date' => '2025-01-15',
                'modify_by' => 1,
                'modify_date' => '2025-01-15',
                'nationality_id' => 1
            ],
            [
                'cust_id' => 11,
                'id_no' => '311',
                'name_en' => 'Sarah Wilson',
                'name_kh' => 'សារ៉ា វីលសុន',
                'gender' => 'F',
                'marital_status' => 1,
                'dob' => '1985-08-22',
                'pob' => 'Phnom Penh',
                'phone1' => '012345678',
                'phone2' => '092345678',
                'phone3' => null,
                'address' => 'Street 51, Phnom Penh, Cambodia',
                'village_id' => 679,
                'occupation' => 'Business Owner',
                'email' => 'sarah.wilson@example.com',
                'spouse_id_no' => '311S',
                'spouse_name_en' => 'David Wilson',
                'spouse_name_kh' => 'ដេវីដ វីលសុន',
                'spouse_dob' => '1987-12-10',
                'staff_id' => null,
                'remark' => 'VIP customer',
                'created_by' => 1,
                'created_date' => '2025-01-20',
                'modify_by' => 1,
                'modify_date' => '2025-01-20',
                'nationality_id' => 1
            ],
            [
                'cust_id' => 12,
                'id_no' => '312',
                'name_en' => 'Sophea Chan',
                'name_kh' => 'សុភា ច័ន',
                'gender' => 'F',
                'marital_status' => 0,
                'dob' => '1992-03-08',
                'pob' => 'Battambang',
                'phone1' => '017888999',
                'phone2' => null,
                'phone3' => null,
                'address' => 'Street 2, Battambang, Cambodia',
                'village_id' => 680,
                'occupation' => 'Farmer',
                'email' => 'sophea.chan@gmail.com',
                'spouse_id_no' => null,
                'spouse_name_en' => null,
                'spouse_name_kh' => null,
                'spouse_dob' => null,
                'staff_id' => null,
                'remark' => 'Small business loan client',
                'created_by' => 1,
                'created_date' => '2025-02-01',
                'modify_by' => 1,
                'modify_date' => '2025-02-01',
                'nationality_id' => 1
            ],
            [
                'cust_id' => 13,
                'id_no' => '313',
                'name_en' => 'David Wong',
                'name_kh' => 'ដេវីដ វ៉ុង',
                'gender' => 'M',
                'marital_status' => 1,
                'dob' => '1988-11-30',
                'pob' => 'Kampong Cham',
                'phone1' => '078123456',
                'phone2' => '098123456',
                'phone3' => null,
                'address' => 'Kampong Cham Province',
                'village_id' => 681,
                'occupation' => 'Construction Worker',
                'email' => 'david.wong@hotmail.com',
                'spouse_id_no' => '313S',
                'spouse_name_en' => 'Lisa Wong',
                'spouse_name_kh' => 'លីសា វ៉ុង',
                'spouse_dob' => '1990-06-15',
                'staff_id' => null,
                'remark' => 'Reliable borrower',
                'created_by' => 1,
                'created_date' => '2025-02-10',
                'modify_by' => 1,
                'modify_date' => '2025-02-10',
                'nationality_id' => 1
            ],
            [
                'cust_id' => 14,
                'id_no' => '314',
                'name_en' => 'Kimly Pheach',
                'name_kh' => 'គីមលី ភាច',
                'gender' => 'F',
                'marital_status' => 0,
                'dob' => '1995-07-18',
                'pob' => 'Kandal',
                'phone1' => '011777888',
                'phone2' => null,
                'phone3' => null,
                'address' => 'Kandal Province',
                'village_id' => 682,
                'occupation' => 'Shop Owner',
                'email' => 'kimly.pheach@yahoo.com',
                'spouse_id_no' => null,
                'spouse_name_en' => null,
                'spouse_name_kh' => null,
                'spouse_dob' => null,
                'staff_id' => null,
                'remark' => 'New customer',
                'created_by' => 1,
                'created_date' => '2025-02-15',
                'modify_by' => 1,
                'modify_date' => '2025-02-15',
                'nationality_id' => 1
            ]
        ];

        foreach ($customers as $customer) {
            DB::table('customer_infos')->insert($customer);
        }
    }
}
