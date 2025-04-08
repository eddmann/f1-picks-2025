<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'User 1',
            'email' => 'user1@example.com',
            'password' => 'password',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'User 2',
            'email' => 'user2@example.com',
            'password' => 'password',
            'role' => 'user',
        ]);
    }
}
