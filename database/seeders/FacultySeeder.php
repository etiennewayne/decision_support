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


        ];

        \App\Models\Faculty::insertOrIgnore($data);
    }
}
