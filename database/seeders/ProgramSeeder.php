<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;


class ProgramSeeder extends Seeder
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
                'program_code' => 'ADMIN',
                'program_desc' => 'ADMINISTRATOR ONLY',
                'active' => 1,
            ],
            [
                'program_code' => 'BSCS',
                'program_desc' => 'BACHELOR OF SCIENCE IN COMPUTER SCIENCE',
                'active' => 1,
            ],
            [
                'program_code' => 'BSED-MATH',
                'program_desc' => 'BACHELOR OF SCIENCE IN EDUCATION MAJOR IN MATH',
                'active' => 1,
            ],
            [
                'program_code' => 'BSED-FIL',
                'program_desc' => 'BACHELOR OF SCIENCE IN EDUCATION MAJOR IN FILIPINO',
                'active' => 1,
            ],
            [
                'program_code' => 'BSED-ENGL',
                'program_desc' => 'BACHELOR OF SCIENCE IN EDUCATION MAJOR IN ENGLISH',
                'active' => 1,
            ],
            [
                'program_code' => 'BS CRIM',
                'program_desc' => 'BACHELOR OF SCIENCE IN CRIMINOLOGY',
                'active' => 1,
            ],
            [
                'program_code' => 'BSBA-HRM',
                'program_desc' => 'BACHELOR OF SCIENCE IN BUSINESS ADMINISTRATION MAJOR IN HUMAN RESOURCE MANAGEMENT',
                'active' => 1,
            ],
            [
                'program_code' => 'BSBA-MM',
                'program_desc' => 'BACHELOR OF SCIENCE IN BUSINESS ADMINISTRATION MAJOR IN MARKETING MANAGEMENT',
                'active' => 1,
            ],

        ];

        \App\Models\Program::insertOrIgnore($data);
    }
}
