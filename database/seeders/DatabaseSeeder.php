<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@mcares.test'],
            [
                'first_name' => 'M. Cares',
                'last_name' => 'Administrator',
                'phone' => '09170000000',
                'password' => Hash::make('Admin@12345'),
                'role' => 'admin',
            ]
        );

        $staffUser = User::updateOrCreate(
            ['email' => 'staff@mcares.test'],
            [
                'first_name' => 'Clinic',
                'last_name' => 'Staff',
                'phone' => '09171111111',
                'password' => Hash::make('Staff@12345'),
                'role' => 'staff',
            ]
        );

        Staff::updateOrCreate(
            ['user_id' => $staffUser->id],
            [
                'position' => 'Beauty Specialist',
                'specialization' => 'Facial and Skin Care',
                'is_available' => true,
            ]
        );

        $services = [
            [
                'name' => 'Signature Facial',
                'description' => 'A relaxing facial treatment designed to refresh and cleanse the skin.',
                'price' => 850,
                'duration_minutes' => 60,
            ],
            [
                'name' => 'Lash Enhancement',
                'description' => 'A beauty service focused on creating a clean and elegant lash look.',
                'price' => 700,
                'duration_minutes' => 60,
            ],
            [
                'name' => 'Skin Care Treatment',
                'description' => 'A personalized treatment for clients looking for a refreshed skin-care routine.',
                'price' => 1200,
                'duration_minutes' => 90,
            ],
            [
                'name' => 'Beauty Consultation',
                'description' => 'A consultation to help identify suitable beauty and aesthetic services.',
                'price' => 500,
                'duration_minutes' => 30,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['name' => $service['name']],
                $service + ['is_available' => true]
            );
        }
    }
}
