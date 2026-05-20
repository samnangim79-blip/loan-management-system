<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert country data
        DB::table('countries')->insert([
            [
                'country_id' => 1,
                'country' => 'Cambodia',
                'country_kh' => 'កម្ពុជា',
            ],
            [
                'country_id' => 2,
                'country' => 'Thailand',
                'country_kh' => 'ថៃ',
            ],
            [
                'country_id' => 3,
                'country' => 'Vietnam',
                'country_kh' => 'វៀតណាម',
            ],
            [
                'country_id' => 4,
                'country' => 'Laos',
                'country_kh' => 'ឡាវ',
            ],
            [
                'country_id' => 5,
                'country' => 'Myanmar',
                'country_kh' => 'មីយ៉ាន់ម៉ា',
            ],
            [
                'country_id' => 6,
                'country' => 'Malaysia',
                'country_kh' => 'ម៉ាឡេស៊ី',
            ],
            [
                'country_id' => 7,
                'country' => 'Singapore',
                'country_kh' => 'សិង្ហបុរី',
            ],
            [
                'country_id' => 8,
                'country' => 'Philippines',
                'country_kh' => 'ហ្វីលីពីន',
            ],
            [
                'country_id' => 9,
                'country' => 'Indonesia',
                'country_kh' => 'ឥណ្ឌូណេស៊ី',
            ],
            [
                'country_id' => 10,
                'country' => 'China',
                'country_kh' => 'ចិន',
            ],
        ]);
    }
}
