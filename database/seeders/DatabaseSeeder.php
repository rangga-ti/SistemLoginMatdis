<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // Mengaktifkan fitur ini agar event model tidak berjalan saat seeding (opsional tapi baik untuk performa)
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin
        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'role' => 'Admin',
            'badge_id' => 'ADM-001',
            'password' => bcrypt('password'), // Password default: password
        ]);

        // 2. Akun Manager
        User::factory()->create([
            'name' => 'Manager Operasional',
            'email' => 'manager@example.com',
            'role' => 'Manager',
            'badge_id' => 'MGR-001',
            'password' => bcrypt('password'),
        ]);

        // 3. Akun Staff
        User::factory()->create([
            'name' => 'Staff Lapangan',
            'email' => 'staff@example.com',
            'role' => 'Staff',
            'badge_id' => 'STF-001',
            'password' => bcrypt('password'),
        ]);

        // 4. Akun User
        User::factory()->create([
            'name' => 'User Standar',
            'email' => 'user@example.com',
            'role' => 'User',
            'badge_id' => 'USR-001',
            'password' => bcrypt('password'),
        ]);
    }
}