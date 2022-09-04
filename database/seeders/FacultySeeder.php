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
                'faculty_lname' => 'SANTARITA',
                'faculty_fname' => 'JUNREY',
                'faculty_mname' => 'MAPA'
            ],

            [
                'faculty_lname' => 'FLORIZA',
                'faculty_fname' => 'JADE ANN',
                'faculty_mname' => 'ESPELLOGO'
            ],


        ];

        \App\Models\Faculty::insertOrIgnore($data);
    }
}
