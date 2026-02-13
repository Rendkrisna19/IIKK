<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pastikan Department HR/GA ada untuk Admin
        $dept = Department::firstOrCreate(
            ['name' => 'HRD & GA'],
            ['created_at' => now(), 'updated_at' => now()]
        );

        // 2. Buat Super Admin
        User::updateOrCreate(
            ['email' => 'admin@wilmar.co.id'], // Cek email ini
            [
                'name' => 'Super Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'nik' => '999999',
                'position' => 'HR Manager',
                'department_id' => $dept->id,
            ]
        );
    }
}