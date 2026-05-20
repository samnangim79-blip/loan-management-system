<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NationalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nationalities = [
            ['nationality' => 'Cambodian', 'nationality_kh' => 'ខ្មែរ'],
            ['nationality' => 'American', 'nationality_kh' => 'អាមេរិកាំង'],
            ['nationality' => 'British', 'nationality_kh' => 'អង់គ្លេស'],
            ['nationality' => 'Chinese', 'nationality_kh' => 'ចិន'],
            ['nationality' => 'Japanese', 'nationality_kh' => 'ជប៉ុន'],
            ['nationality' => 'Korean', 'nationality_kh' => 'កូរ៉េ'],
            ['nationality' => 'Thai', 'nationality_kh' => 'ថៃ'],
            ['nationality' => 'Vietnamese', 'nationality_kh' => 'វៀតណាម'],
            ['nationality' => 'Lao', 'nationality_kh' => 'ឡាវ'],
            ['nationality' => 'Myanmar', 'nationality_kh' => 'មីយ៉ាន់ម៉ា'],
            ['nationality' => 'Malaysian', 'nationality_kh' => 'ម៉ាឡេស៊ី'],
            ['nationality' => 'Singaporean', 'nationality_kh' => 'សិង្ហបុរី'],
            ['nationality' => 'Indonesian', 'nationality_kh' => 'ឥណ្ឌូនេស៊ី'],
            ['nationality' => 'Filipino', 'nationality_kh' => 'ហ្វីលីពីន'],
            ['nationality' => 'Indian', 'nationality_kh' => 'ឥណ្ឌា'],
            ['nationality' => 'Pakistani', 'nationality_kh' => 'ប៉ាគីស្ថាន'],
            ['nationality' => 'Bangladeshi', 'nationality_kh' => 'បង់ក្លាដែស'],
            ['nationality' => 'Australian', 'nationality_kh' => 'អូស្ត្រាលី'],
            ['nationality' => 'Canadian', 'nationality_kh' => 'កាណាដា'],
            ['nationality' => 'French', 'nationality_kh' => 'បារាំង'],
            ['nationality' => 'German', 'nationality_kh' => 'អាល្លឺម៉ង់'],
            ['nationality' => 'Italian', 'nationality_kh' => 'អ៊ីតាលី'],
            ['nationality' => 'Spanish', 'nationality_kh' => 'អេស្ប៉ាញ'],
            ['nationality' => 'Russian', 'nationality_kh' => 'រុស្ស៊ី'],
            ['nationality' => 'Dutch', 'nationality_kh' => 'ហូឡង់'],
            ['nationality' => 'Belgian', 'nationality_kh' => 'បែលហ្ស៊ីក'],
            ['nationality' => 'Swiss', 'nationality_kh' => 'ស្វីស'],
            ['nationality' => 'Austrian', 'nationality_kh' => 'អូទ្រីស'],
            ['nationality' => 'Swedish', 'nationality_kh' => 'ស៊ុយអែត'],
            ['nationality' => 'Norwegian', 'nationality_kh' => 'ន័រវេស'],
            ['nationality' => 'Danish', 'nationality_kh' => 'ដាណឺម៉ាក'],
            ['nationality' => 'Finnish', 'nationality_kh' => 'ហ្វាំងឡង់'],
            ['nationality' => 'Polish', 'nationality_kh' => 'ប៉ូឡូញ'],
            ['nationality' => 'Brazilian', 'nationality_kh' => 'ប្រេស៊ីល'],
            ['nationality' => 'Mexican', 'nationality_kh' => 'ម៉ិកស៊ិក'],
            ['nationality' => 'Argentinian', 'nationality_kh' => 'អាហ្សង់ទីន'],
            ['nationality' => 'South African', 'nationality_kh' => 'អាហ្វ្រិកខាងត្បូង'],
            ['nationality' => 'Egyptian', 'nationality_kh' => 'អេហ្ស៊ីប'],
            ['nationality' => 'Nigerian', 'nationality_kh' => 'នីហ្សេរីយ៉ា'],
            ['nationality' => 'Other', 'nationality_kh' => 'ផ្សេងៗ'],
        ];

        DB::table('nationalitys')->insert($nationalities);
    }
}
