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
        $user = [
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('admin123'),
                'phone_number' => '1234567890',
                'role_id' => 1,
            ],
            [
                'name' => 'Employee',
                'email' => 'employee@example.com',
                'password' => bcrypt('employee123'),
                'phone_number' => '1234567891',
                'role_id' => 2,
            ],
        ];

        foreach ($user as $user) {
            DB::table('users')->updateOrInsert(
                ['name' => $user['name']],
                [
                    'email' => $user['email'],
                    'password' => $user['password'],
                    'role_id' => $user['role_id'],
                    'phone_number' => $user['phone_number'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
