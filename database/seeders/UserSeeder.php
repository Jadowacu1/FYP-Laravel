<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'reg_number' => null,
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'),
                'role' => 'department',
                'created_at' => now(),
                'updated_at' => now(),
            ],
           
        ]);
    }
}
