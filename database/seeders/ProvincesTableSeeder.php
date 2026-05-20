<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvincesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('provinces')->delete();

        DB::table('provinces')->insert(array (
            0 =>
            array (
                'id' => 1,
                'type' => 'ខេត្ត​',
                'code' => '1',
                'name_kh' => 'បន្ទាយមានជ័យ',
                'name_en' => 'Banteay Meanchey',
                'country_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
                'type' => 'ខេត្ត​',
                'code' => '2',
                'name_kh' => 'បាត់ដំបង',
                'name_en' => 'Battambang',
                'country_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 =>
            array (
                'id' => 3,
                'type' => 'ខេត្ត​',
                'code' => '3',
                'name_kh' => 'កំពង់ចាម',
                'name_en' => 'Kampong Cham',
                'country_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 =>
            array (
                'id' => 4,
                'type' => 'ខេត្ត​',
                'code' => '4',
                'name_kh' => 'កំពង់ឆ្នាំង',
                'name_en' => 'Kampong Chhnang',
                'country_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 =>
            array (
                'id' => 5,
                'type' => 'ខេត្ត​',
                'code' => '5',
                'name_kh' => 'កំពង់ស្ពឺ',
                'name_en' => 'Kampong Speu',
                'country_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 =>
            array (
                'id' => 6,
                'type' => 'ខេត្ត​',
                'code' => '6',
                'name_kh' => 'កំពង់ធំ',
                'name_en' => 'Kampong Thom',
                'country_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 =>
            array (
                'id' => 7,
                'type' => 'ខេត្ត​',
                'code' => '7',
                'name_kh' => 'កំពត',
                'name_en' => 'Kampot',
                'country_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 =>
            array (
                'id' => 8,
                'type' => 'ខេត្ត​',
                'code' => '8',
                'name_kh' => 'កណ្ដាល',
                'country_id' => 1,
                'name_en' => 'Kandal',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 =>
            array (
                'id' => 9,
                'type' => 'ខេត្ត​',
                'code' => '9',
                'name_kh' => 'កោះកុង',
                'country_id' => 1,
                'name_en' => 'Koh Kong',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 =>
            array (
                'id' => 10,
                'type' => 'ខេត្ត​',
                'code' => '10',
                'name_kh' => 'ក្រចេះ',
                'name_en' => 'Kratie',
                'country_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 =>
            array (
                'id' => 11,
                'type' => 'ខេត្ត​',
                'code' => '11',
                'name_kh' => 'មណ្ឌលគិរី',
                'name_en' => 'Mondul Kiri',
                'country_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 =>
            array (
                'id' => 12,
                'type' => 'ខេត្ត​',
                'code' => '12',
                'name_kh' => 'រាជធានីភ្នំពេញ',
                'name_en' => 'Phnom Penh',
                'country_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 =>
            array (
                'id' => 13,
                'type' => 'ខេត្ត​',
                'code' => '13',
                'name_kh' => 'ព្រះវិហារ',
                'name_en' => 'Preah Vihear',
                'country_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 =>
            array (
                'id' => 14,
                'type' => 'ខេត្ត​',
                'code' => '14',
                'name_kh' => 'ព្រៃវែង	',
                'name_en' => 'Prey Veng',
                'country_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            14 =>
            array (
                'id' => 15,
                'type' => 'ខេត្ត​',
                'code' => '15',
                'name_kh' => 'ពោធិ៍សាត់	',
                'name_en' => 'Pursat',
                'country_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            15 =>
            array (
                'id' => 16,
                'type' => 'ខេត្ត​',
                'code' => '16',
                'name_kh' => 'រតនគិរី	',
                'name_en' => 'Ratanak Kiri',
                'country_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            16 =>
            array (
                'id' => 17,
                'type' => 'ខេត្ត​',
                'code' => '17',
                'name_kh' => 'សៀមរាប	',
                'name_en' => 'Siemreap',
                'country_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            17 =>
            array (
                'id' => 18,
                'type' => 'ខេត្ត​',
                'code' => '18',
                'name_kh' => 'ព្រះសីហនុ	',
                'name_en' => 'Preah Sihanouk',
                'country_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            18 =>
            array (
                'id' => 19,
                'type' => 'ខេត្ត​',
                'code' => '19',
                'name_kh' => 'ស្ទឹងត្រែង	',
                'name_en' => 'Stung Treng',
                'country_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            19 =>
            array (
                'id' => 20,
                'type' => 'ខេត្ត​',
                'code' => '20',
                'name_kh' => 'ស្វាយរៀង	',
                'name_en' => 'Svay Rieng',
                'country_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            20 =>
            array (
                'id' => 21,
                'type' => 'ខេត្ត​',
                'code' => '21',
                'name_kh' => 'តាកែវ	',
                'name_en' => 'Takeo',
                'country_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            21 =>
            array (
                'id' => 22,
                'type' => 'ខេត្ត​',
                'code' => '22',
                'name_kh' => 'ឧត្ដរមានជ័យ	',
                'name_en' => 'Oddar Meanchey',
                'country_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            22 =>
            array (
                'id' => 23,
                'type' => 'ខេត្ត​',
                'code' => '23',
                'name_kh' => 'កែប	',
                'name_en' => 'Kep',
                'country_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            23 =>
            array (
                'id' => 24,
                'type' => 'ខេត្ត​',
                'code' => '24',
                'name_kh' => 'ប៉ៃលិន	',
                'name_en' => 'Pailin',
                'country_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            24 =>
            array (
                'id' => 25,
                'type' => 'ខេត្ត​',
                'code' => '25',
                'name_kh' => 'ត្បូងឃ្មុំ	',
                'name_en' => 'Tboung Khmum',
                'country_id' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));


    }
}
