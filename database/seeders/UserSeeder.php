<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@mbmgroup.com',
            'password' => Hash::make('Mbm@1234'),
            'role' => 'admin'
        ]);

        DB::table('users')->insert([
            'name' => 'Employee',
            'email' => 'employee@mbmgroup.com',
            'password' => Hash::make('Mbm@1234'),
            'role' => 'employee',
        ]);

        DB::table('users')->insert([
            'name' => 'Store Executive',
            'email' => 'executive@mbmgroup.com',
            'password' => Hash::make('Mbm@1234'),
            'role' => 'executive',
        ]);
    }
}
