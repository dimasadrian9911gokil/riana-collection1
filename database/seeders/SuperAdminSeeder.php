<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            [
                'email' => 'admin@riana.com'
            ],
            [
                'name' => 'Super Admin',
                'phone' => '08123456789',
                'birth_date' => '2000-01-01',
                'gender' => 'Perempuan',
                'agree_integrity' => true,
                'password' => Hash::make('password123')
            ]
        );

        $user->assignRole('Super Admin');
    }
}