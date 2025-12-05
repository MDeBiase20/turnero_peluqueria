<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // SUPER ADMIN
        User::create([
            'name' => 'Super Admin',
            'email' => 'mylton@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'super_admin',
            'estado' => 'activo',
        ]);

        // ADMIN
        User::create([
            'name' => 'Administrador',
            'email' => 'gabipeluqueria@turnos.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'estado' => 'activo',
        ]);
    }
}
