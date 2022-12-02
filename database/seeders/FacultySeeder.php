<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $data = [
            [
                'lname' => 'SANTARITA',
                'fname' => 'JUNREY',
                'mname' => 'MAPA',
                'sex' => 'MALE'
            ],
            [
                'lname' => 'FLORIZA',
                'fname' => 'JADE ANN',
                'mname' => 'ESPELLOGO',
                'sex' => 'FEMALE'
            ],
            [
                'lname' => 'CABATINGAN',
                'fname' => 'CARIN',
                'mname' => 'Z',
                'sex' => 'FEMALE'
            ],
            [
                'lname' => 'CABIDO',
                'fname' => 'MANUEL',
                'mname' => 'C',
                'sex' => 'MALE'
            ],
            [
                'lname' => 'HILOT',
                'fname' => 'GENEVIEVE',
                'mname' => 'B',
                'sex' => 'FEMALE'
            ],
            [
                'lname' => 'SELATONA',
                'fname' => 'JANZIEL',
                'mname' => 'B',
                'sex' => 'FEMALE'
            ],
            [
                'lname' => 'TIA',
                'fname' => 'JENIEFFER',
                'mname' => 'T',
                'sex' => 'FEMALE'
            ],
            [
                'lname' => 'TAGAAN',
                'fname' => 'CHARY MAE',
                'mname' => '',
                'sex' => 'FEMALE'
            ],


        ];

        \App\Models\Faculty::insertOrIgnore($data);
    }
}
