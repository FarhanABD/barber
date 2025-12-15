<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // User::create([
        //     'name'         => 'Admin Barbershop',
        //     'email'        => 'admin@barber.com',
        //     'password'     => 'admin123',   // otomatis di-hash oleh model (casts: hashed)
        //     'phone_number' => '081234567890',
        //     'role'         => 'admin',      // isi role admin
        // ]);
        User::create([
            'name'         => 'User Barbershop',
            'email'        => 'user@barber.com',
            'password'     => 'user123',   // otomatis di-hash oleh model (casts: hashed)
            'phone_number' => '081234567891',
            'role'         => 'user',      // isi role user
        ]);
    }
}