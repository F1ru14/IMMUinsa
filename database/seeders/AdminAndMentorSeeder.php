<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminAndMentorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Default Administrator Account
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'System Administrator',
                'email' => 'admin@immuinsa.org',
                'password' => Hash::make('password'),
                'role' => Role::Admin,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 2. Default Mentor Account
        User::updateOrCreate(
            ['username' => 'mentor'],
            [
                'name' => 'Senior Mentor',
                'email' => 'mentor@immuinsa.org',
                'password' => Hash::make('password'),
                'role' => Role::Mentor,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
