<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat akun contoh (opsional)
        $superAdminEmail = 'superadmin@samsat.local';

        if (!User::where('email', $superAdminEmail)->exists()) {
            User::create([
                'name' => 'Super Admin',
                'email' => $superAdminEmail,
                /** @phpstan-ignore-next-line */
                'password' => 'password123',
                'role' => 'super_admin',
            ]);
        }

        $petugasEmail = 'petugas@samsat.local';

        if (!User::where('email', $petugasEmail)->exists()) {
            User::create([
                'name' => 'Petugas Gudang',
                'email' => $petugasEmail,
                /** @phpstan-ignore-next-line */
                'password' => 'password123',
                'role' => 'petugas_gudang',
            ]);
        }
    }
}