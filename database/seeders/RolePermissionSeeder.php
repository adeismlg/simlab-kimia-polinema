<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['mahasiswa', 'dosen', 'eksternal', 'laboran', 'kepala_lab', 'admin'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Contoh: buat akun admin default
        $admin = \App\Models\User::firstOrCreate(
            ['email' => 'admin@polinema.ac.id'],
            [
                'name' => 'Admin Lab Kimia',
                'password' => bcrypt('password'), // GANTI setelah seeding pertama
                'tipe' => 'internal',
            ]
        );
        $admin->assignRole('admin');
    }
}
