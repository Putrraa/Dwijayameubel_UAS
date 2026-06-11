<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // ADMIN
        User::create([
            'name' => 'Admin',
            'role' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

        // KASIR
        User::create([
            'name' => 'Kasir',
            'role' => 'kasir',
            'email' => 'kasir@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

        // CUSTOMER
        User::create([
            'name' => 'Customer',
            'role' => 'customer',
            'email' => 'customer@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

    }
}