<?php

namespace Database\Seeders;

use App\Models\AppointmentType;
use App\Models\CourseType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            CourseTypeSeeder::class,
            SemesterSeeder::class,
            RoomSeeder::class,
            RoleSeeder::class,
            ProgramSeeder::class,
            UserSeeder::class,
            AcademicYearSeeder::class,
            CourseSeeder::class,
            FacultySeeder::class,
            ScheduleSeeder::class,
        ]);
    }
}
