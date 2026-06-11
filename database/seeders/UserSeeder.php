<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // ADMIN
        User::create([
            'name' => 'admin',
            'role' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

        // KASIR
        User::create([
            'name' => 'kasir',
            'role' => 'kasir',
            'email' => 'kasir@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

        // CUSTOMER
        User::create([
            'name' => 'customer',
            'role' => 'customer',
            'email' => 'customer@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

    }
}