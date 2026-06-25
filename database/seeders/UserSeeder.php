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
        // 1. Pastikan Department ada
        $deptHrd = Department::firstOrCreate(
            ['name' => 'HRD & GA'],
            ['created_at' => now(), 'updated_at' => now()]
        );

        $deptMis = Department::firstOrCreate(
            ['name' => 'Manajemen Information System'],
            ['created_at' => now(), 'updated_at' => now()]
        );

        $deptSec = Department::firstOrCreate(
            ['name' => 'Security'],
            ['created_at' => now(), 'updated_at' => now()]
        );

        // 2. Buat Super Admin
        User::updateOrCreate(
            ['email' => 'admin@wilmar.co.id'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'nik' => '999999',
                'position' => 'HR Manager',
                'department_id' => $deptHrd->id,
            ]
        );

        // 3. Buat HOD (Sebastian Suhandinata)
        User::updateOrCreate(
            ['email' => 'Sebastian@gmail.com'],
            [
                'name' => 'Sebastian Suhandinata',
                'password' => Hash::make('password123'),
                'role' => 'hod',
                'nik' => '62221267',
                'position' => 'Head Of Department',
                'department_id' => $deptMis->id,
            ]
        );

        // 4. Buat Employee (Rangga Damanik)
        User::updateOrCreate(
            ['email' => 'Ranggadamanik@gmail.com'],
            [
                'name' => 'Rangga Damanik',
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'nik' => '62231267',
                'position' => 'Staff',
                'department_id' => $deptMis->id,
            ]
        );

        // 5. Buat Security (Supardi)
        User::updateOrCreate(
            ['email' => 'Supardi@gmail.com'],
            [
                'name' => 'Supardi',
                'password' => Hash::make('password123'),
                'role' => 'security',
                'nik' => '62251267',
                'position' => 'Staff',
                'department_id' => $deptSec->id,
            ]
        );
    }
}