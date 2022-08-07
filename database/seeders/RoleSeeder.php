<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
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
                'role' => 'ADMINISTRATOR',
            ],
            [
                'role' => 'FACULTY',
            ],


        ];

        \App\Models\Role::insertOrIgnore($data);

    }
}
