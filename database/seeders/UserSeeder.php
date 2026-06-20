<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@inventory.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Owner',
            'username' => 'owner',
            'email' => 'owner@inventory.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        User::create([
            'name' => 'Sekretaris',
            'username' => 'sekretaris',
            'email' => 'sekretaris@inventory.com',
            'password' => Hash::make('password'),
            'role' => 'sekretaris',
        ]);
    }
}