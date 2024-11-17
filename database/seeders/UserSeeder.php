<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
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
                'address' => '123 Main Street',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
                'phone_number' => '123456789',
                'is_agent' => 0,
                'created_at' => now(),
            ],
            [
                'name' => 'User1',
                'address' => '123 Main Street',
                'email' => 'user1@gmail.com',
                'password' => bcrypt('user123'),
                'role' => 'user',
                'phone_number' => '123456789',
                'is_agent' => 0,
                'created_at' => now(),
            ],

        ]);
    }
}
