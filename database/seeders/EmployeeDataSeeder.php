<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class EmployeeDataSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            ['Departemen' => 'PLANNING & OPERATION', 'Jabatan' => 'Head of Department (HOD)', 'NIK' => '41163638', 'Nama Karyawan' => 'Freddy Liong', 'Email' => 'freddy.liong@wilmar.co.id'],
            ['Departemen' => 'PLANNING & OPERATION', 'Jabatan' => 'Staff', 'NIK' => '24215184', 'Nama Karyawan' => 'Dedi Chaniago', 'Email' => 'dedi.chaniago@wilmar.co.id'],
            ['Departemen' => 'PLANNING & OPERATION', 'Jabatan' => 'Staff', 'NIK' => '94255632', 'Nama Karyawan' => 'Annisa Siregar', 'Email' => 'annisa.siregar@wilmar.co.id'],
            ['Departemen' => 'PLANNING & OPERATION', 'Jabatan' => 'Staff', 'NIK' => '12263073', 'Nama Karyawan' => 'Surya Nugroho', 'Email' => 'surya.nugroho@wilmar.co.id'],
            ['Departemen' => 'PLANNING & OPERATION', 'Jabatan' => 'Staff', 'NIK' => '81231689', 'Nama Karyawan' => 'Wahyu Wijaya', 'Email' => 'wahyu.wijaya@wilmar.co.id'],
            ['Departemen' => 'PRODUCTION & TECHNICAL', 'Jabatan' => 'Head of Department (HOD)', 'NIK' => '24132400', 'Nama Karyawan' => 'Avadian R. Perdana', 'Email' => 'avadian.r@wilmar.co.id'],
            ['Departemen' => 'PRODUCTION & TECHNICAL', 'Jabatan' => 'Staff', 'NIK' => '78245214', 'Nama Karyawan' => 'Fitri Salim', 'Email' => 'fitri.salim@wilmar.co.id'],
            ['Departemen' => 'PRODUCTION & TECHNICAL', 'Jabatan' => 'Staff', 'NIK' => '35262993', 'Nama Karyawan' => 'Reza Setiawan', 'Email' => 'reza.setiawan@wilmar.co.id'],
            ['Departemen' => 'PRODUCTION & TECHNICAL', 'Jabatan' => 'Staff', 'NIK' => '12244666', 'Nama Karyawan' => 'Yanti Chaniago', 'Email' => 'yanti.chaniago@wilmar.co.id'],
            ['Departemen' => 'PRODUCTION & TECHNICAL', 'Jabatan' => 'Staff', 'NIK' => '62213339', 'Nama Karyawan' => 'Fajar Kurniawan', 'Email' => 'fajar.kurniawan@wilmar.co.id'],
            ['Departemen' => 'Production Planning and Inventory Control (PPIC)', 'Jabatan' => 'Head of Department (HOD)', 'NIK' => '62137996', 'Nama Karyawan' => 'Gatot Purnomo', 'Email' => 'gatot.purnomo@wilmar.co.id'],
            ['Departemen' => 'Production Planning and Inventory Control (PPIC)', 'Jabatan' => 'Staff', 'NIK' => '78263595', 'Nama Karyawan' => 'Hendra Utami', 'Email' => 'hendra.utami@wilmar.co.id'],
            ['Departemen' => 'Production Planning and Inventory Control (PPIC)', 'Jabatan' => 'Staff', 'NIK' => '24227847', 'Nama Karyawan' => 'Lina Salim', 'Email' => 'lina.salim@wilmar.co.id'],
            ['Departemen' => 'Production Planning and Inventory Control (PPIC)', 'Jabatan' => 'Staff', 'NIK' => '94252643', 'Nama Karyawan' => 'Nadia Sugiarto', 'Email' => 'nadia.sugiarto@wilmar.co.id'],
            ['Departemen' => 'Production Planning and Inventory Control (PPIC)', 'Jabatan' => 'Staff', 'NIK' => '12224161', 'Nama Karyawan' => 'Andi Setiawan', 'Email' => 'andi.setiawan@wilmar.co.id'],
            ['Departemen' => 'LOGISTIC & STORE', 'Jabatan' => 'Head of Department (HOD)', 'NIK' => '78148816', 'Nama Karyawan' => 'Ehud A. Butarbutar', 'Email' => 'ehud.a@wilmar.co.id'],
            ['Departemen' => 'LOGISTIC & STORE', 'Jabatan' => 'Staff', 'NIK' => '62261499', 'Nama Karyawan' => 'Agus Setiawan', 'Email' => 'agus.setiawan@wilmar.co.id'],
            ['Departemen' => 'LOGISTIC & STORE', 'Jabatan' => 'Staff', 'NIK' => '24241692', 'Nama Karyawan' => 'Fajar Santoso', 'Email' => 'fajar.santoso@wilmar.co.id'],
            ['Departemen' => 'LOGISTIC & STORE', 'Jabatan' => 'Staff', 'NIK' => '24265582', 'Nama Karyawan' => 'Eka Siregar', 'Email' => 'eka.siregar@wilmar.co.id'],
            ['Departemen' => 'LOGISTIC & STORE', 'Jabatan' => 'Staff', 'NIK' => '55243171', 'Nama Karyawan' => 'Indra Tanjung', 'Email' => 'indra.tanjung@wilmar.co.id'],
            ['Departemen' => 'OPERATION', 'Jabatan' => 'Head of Department (HOD)', 'NIK' => '12173167', 'Nama Karyawan' => 'Roganda Silaban', 'Email' => 'roganda.silaban@wilmar.co.id'],
            ['Departemen' => 'OPERATION', 'Jabatan' => 'Staff', 'NIK' => '24244576', 'Nama Karyawan' => 'Hendra Lestari', 'Email' => 'hendra.lestari@wilmar.co.id'],
            ['Departemen' => 'OPERATION', 'Jabatan' => 'Staff', 'NIK' => '94236307', 'Nama Karyawan' => 'Tri Wijaya', 'Email' => 'tri.wijaya@wilmar.co.id'],
            ['Departemen' => 'OPERATION', 'Jabatan' => 'Staff', 'NIK' => '62241680', 'Nama Karyawan' => 'Eka Hidayat', 'Email' => 'eka.hidayat@wilmar.co.id'],
            ['Departemen' => 'OPERATION', 'Jabatan' => 'Staff', 'NIK' => '24209581', 'Nama Karyawan' => 'Agus Prasetyo', 'Email' => 'agus.prasetyo@wilmar.co.id'],
            ['Departemen' => 'WAREHOUSE', 'Jabatan' => 'Head of Department (HOD)', 'NIK' => '81165431', 'Nama Karyawan' => 'Jeprin Turnip', 'Email' => 'jeprin.turnip@wilmar.co.id'],
            ['Departemen' => 'WAREHOUSE', 'Jabatan' => 'Staff', 'NIK' => '35212586', 'Nama Karyawan' => 'Sari Saputra', 'Email' => 'sari.saputra@wilmar.co.id'],
            ['Departemen' => 'WAREHOUSE', 'Jabatan' => 'Staff', 'NIK' => '12239474', 'Nama Karyawan' => 'Rizki Wibowo', 'Email' => 'rizki.wibowo@wilmar.co.id'],
            ['Departemen' => 'WAREHOUSE', 'Jabatan' => 'Staff', 'NIK' => '81258529', 'Nama Karyawan' => 'Aditya Saputra', 'Email' => 'aditya.saputra@wilmar.co.id'],
            ['Departemen' => 'WAREHOUSE', 'Jabatan' => 'Staff', 'NIK' => '78263591', 'Nama Karyawan' => 'Reza Halim', 'Email' => 'reza.halim@wilmar.co.id'],
            ['Departemen' => 'TANK FARM', 'Jabatan' => 'Head of Department (HOD)', 'NIK' => '41147576', 'Nama Karyawan' => 'Dedi Gunawan', 'Email' => 'dedi.gunawan@wilmar.co.id'],
            ['Departemen' => 'TANK FARM', 'Jabatan' => 'Staff', 'NIK' => '35265691', 'Nama Karyawan' => 'Fitri Setiawan', 'Email' => 'fitri.setiawan@wilmar.co.id'],
            ['Departemen' => 'TANK FARM', 'Jabatan' => 'Staff', 'NIK' => '35221977', 'Nama Karyawan' => 'Dedi Tanjung', 'Email' => 'dedi.tanjung@wilmar.co.id'],
            ['Departemen' => 'TANK FARM', 'Jabatan' => 'Staff', 'NIK' => '41258079', 'Nama Karyawan' => 'Dimas Saputra', 'Email' => 'dimas.saputra@wilmar.co.id'],
            ['Departemen' => 'TANK FARM', 'Jabatan' => 'Staff', 'NIK' => '81232822', 'Nama Karyawan' => 'Rian Lestari', 'Email' => 'rian.lestari@wilmar.co.id'],
            ['Departemen' => 'Heavy Equipment & Shredder (HEQ & SHREDDER)', 'Jabatan' => 'Head of Department (HOD)', 'NIK' => '35165609', 'Nama Karyawan' => 'Sahat Marpaung', 'Email' => 'sahat.marpaung@wilmar.co.id'],
            ['Departemen' => 'Heavy Equipment & Shredder (HEQ & SHREDDER)', 'Jabatan' => 'Staff', 'NIK' => '94247858', 'Nama Karyawan' => 'Faisal Kurniawan', 'Email' => 'faisal.kurniawan@wilmar.co.id'],
            ['Departemen' => 'Heavy Equipment & Shredder (HEQ & SHREDDER)', 'Jabatan' => 'Staff', 'NIK' => '24249028', 'Nama Karyawan' => 'Putri Wijaya', 'Email' => 'putri.wijaya@wilmar.co.id'],
            ['Departemen' => 'Heavy Equipment & Shredder (HEQ & SHREDDER)', 'Jabatan' => 'Staff', 'NIK' => '24244596', 'Nama Karyawan' => 'Taufik Tanjung', 'Email' => 'taufik.tanjung@wilmar.co.id'],
            ['Departemen' => 'Heavy Equipment & Shredder (HEQ & SHREDDER)', 'Jabatan' => 'Staff', 'NIK' => '78252702', 'Nama Karyawan' => 'Annisa Saputra', 'Email' => 'annisa.saputra@wilmar.co.id'],
            ['Departemen' => 'ADMINISTRATION', 'Jabatan' => 'Head of Department (HOD)', 'NIK' => '55146522', 'Nama Karyawan' => 'Rusanna P. Sinaga', 'Email' => 'rusanna.p@wilmar.co.id'],
            ['Departemen' => 'ADMINISTRATION', 'Jabatan' => 'Staff', 'NIK' => '94218659', 'Nama Karyawan' => 'Andi Saputra', 'Email' => 'andi.saputra@wilmar.co.id'],
            ['Departemen' => 'ADMINISTRATION', 'Jabatan' => 'Staff', 'NIK' => '35235882', 'Nama Karyawan' => 'Sari Sugiarto', 'Email' => 'sari.sugiarto@wilmar.co.id'],
            ['Departemen' => 'ADMINISTRATION', 'Jabatan' => 'Staff', 'NIK' => '41209384', 'Nama Karyawan' => 'Johan Sinaga', 'Email' => 'johan.sinaga@wilmar.co.id'],
            ['Departemen' => 'ADMINISTRATION', 'Jabatan' => 'Staff', 'NIK' => '81261356', 'Nama Karyawan' => 'Taufik Halim', 'Email' => 'taufik.halim@wilmar.co.id'],
        ];

        foreach ($employees as $employee) {
            // Ensure department exists
            $department = Department::firstOrCreate(
                ['name' => $employee['Departemen']],
                ['created_at' => now(), 'updated_at' => now()]
            );

            // Determine role
            $role = 'employee';
            if ($employee['Jabatan'] === 'Head of Department (HOD)') {
                $role = 'hod';
            }

            // Create or update user
            User::updateOrCreate(
                ['email' => $employee['Email']],
                [
                    'name' => $employee['Nama Karyawan'],
                    'nik' => $employee['NIK'],
                    'password' => Hash::make('12345678'),
                    'position' => $employee['Jabatan'],
                    'role' => $role,
                    'department_id' => $department->id,
                ]
            );
        }
    }
}
