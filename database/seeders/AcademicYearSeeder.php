<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AcademicYearSeeder extends Seeder
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
                'code' => '2021-2022-1',
                'semester' => '1ST SEMESTER',
                'acadyear_desc' => 'ACADEMIC YEAR 2021 - 2022 1ST SEMESTER',
                'active' => 1,
            ],
            [
                'code' => '2021-2022-2',
                'semester' => '2ND SEMESTER',
                'acadyear_desc' => 'ACADEMIC YEAR 2021 - 2022 2ND SEMESTER',
                'active' => 1,
            ],

        ];

        \App\Models\AcademicYear::insertOrIgnore($data);

    }
}
