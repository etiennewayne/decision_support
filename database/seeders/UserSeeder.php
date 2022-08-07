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
                'lname' => 'DOMINGUEZ',
                'fname' => 'JUNARD',
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
                'username' => 'a',
                'lname' => 'NERI',
                'fname' => 'MAIRAMAE',
                'mname' => 'P',
                'suffix' => '',
                'program_id' => 2,
                'sex' => 'MALE',
                'province' => 'MISAMIS OCCIDENTAL',
                'city' => 'TANGUB CITY',
                'barangay' => 'CANIANGAN',
                'street' => 'P-6',
                'email' => 'neri@dev.com',
                'contact_no' => '09167789585',
                'role' => 'STUDENT',
                'password' => Hash::make('a')
            ],
        ];

        \App\Models\User::insertOrIgnore($data);
    }
}
