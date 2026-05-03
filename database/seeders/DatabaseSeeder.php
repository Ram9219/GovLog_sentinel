<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::updateOrCreate(
            ['email' => 'admin@govlog.in'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin@123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Create operator user
        User::updateOrCreate(
            ['email' => 'operator@govlog.in'],
            [
                'name' => 'Log Operator',
                'password' => Hash::make('operator@123'),
                'role' => 'operator',
                'email_verified_at' => now(),
            ]
        );

        // Create viewer user
        User::updateOrCreate(
            ['email' => 'viewer@govlog.in'],
            [
                'name' => 'Log Viewer',
                'password' => Hash::make('viewer@123'),
                'role' => 'viewer',
                'email_verified_at' => now(),
            ]
        );

        // Seed classification rules
        $this->call(ClassificationRulesSeeder::class);

        // Seed demo logs
        $this->call(SampleLogsSeeder::class);
    }
}
