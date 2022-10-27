<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
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
                'username' => 'admin',
                'lname' => 'CALIBO',
                'fname' => 'ALEXANDER',
                'mname' => 'P',
                'suffix' => '',
                'program_id' => 1,
                'sex' => 'MALE',
                'province' => 'MISAMIS OCCIDENTAL',
                'city' => 'TANGUB CITY',
                'barangay' => 'CANIANGAN',
                'street' => 'P-6',
                'email' => 'angel@dev.com',
                'contact_no' => '09167789585',
                'role' => 'ADMINISTRATOR',
                'password' => Hash::make('a')
            ],
            [
                'username' => 'faculty',
                'lname' => 'NOVA',
                'fname' => 'MAE',
                'mname' => 'P',
                'suffix' => '',
                'program_id' => 1,
                'sex' => 'MALE',
                'province' => 'MISAMIS OCCIDENTAL',
                'city' => 'TANGUB CITY',
                'barangay' => 'CANIANGAN',
                'street' => 'P-6',
                'email' => 'faculty@dev.com',
                'contact_no' => '091166554878',
                'role' => 'FACULTY',
                'password' => Hash::make('a')
            ],

        ];

        \App\Models\User::insertOrIgnore($data);
    }
}
