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

        $ownerUser = User::create([
            'name' => 'Owner CV Wijaya',
            'username' => 'owner',
            'email' => 'owner@inventory.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        \App\Models\Owner::create([
            'user_id' => $ownerUser->id,
            'nama' => 'Owner CV Wijaya',
            'username' => 'owner',
            'email' => 'owner@inventory.com',
            'password' => Hash::make('password'),
            'no_telp' => '081234567890',
            'alamat' => 'Jl. Raya CV Wijaya Las No. 1, Jakarta',
            'jabatan' => 'Owner / Direktur Utama',
            'status' => 'aktif',
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