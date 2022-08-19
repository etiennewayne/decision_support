<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
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
                'course_code' => 'PSC 211',
                'course_desc' => 'OBJECT ORIENTED PROGRAMMING',
                'course_type' => 'LEC',
                'course_unit' => 3,
            ],
            [
                'course_code' => 'PSC 212',
                'course_desc' => 'WEB PROGRAMMING',
                'course_type' => 'LEC',
                'course_unit' => 3,
            ],
        ];

        \App\Models\Course::insertOrIgnore($data);


    }
}
