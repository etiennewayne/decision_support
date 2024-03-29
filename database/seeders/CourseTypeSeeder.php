<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CourseTypeSeeder extends Seeder
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
                'course_type' => 'LEC',
            ],

            [
                'course_type' => 'LAB',
            ],

        ];

        \App\Models\CourseType::insertOrIgnore($data);

    }
}
