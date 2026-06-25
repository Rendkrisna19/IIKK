<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \App\Models\Department::truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $departments = [
            'PLANNING & OPERATION',
            'PRODUCTION & TECHNICAL',
            'Production Planning and Inventory Control (PPIC)',
            'LOGISTIC & STORE',
            'OPERATION',
            'WAREHOUSE',
            'TANK FARM',
            'Heavy Equipment & Shredder (HEQ & SHREDDER)',
            'ADMINISTRATION',
            'Quality Control & Research and Development (QC & RnD)',
            'ENGINEERING & PROJECT',
            'FINANCE',
            'Quality Assurance (QA)',
            'Environment, Health, and Safety (E H S)',
            'SECURITY',
            'PALM OIL MILL',
            'Quality, Process & Engineering & Cost Control (QPE & COST CONTROL)',
            'Electrical & Instrumentation (E & I)',
            'TEXTURIZING',
            'UTILITY',
            'CONSUMER PACK',
            'Water Treatment Plant & Effluent Treatment Plant (WTP & ETP)',
            'KERNEL CRUSHING',
            'Bulk Refinery & Fractionation (BULK REF & FRACT)',
            'Specialty Fats Refinery & Hydrogenation, Interesterification, Granulation (SF REF & H I G)',
            'Specialty Fats Fractionation (SF FRACTIONATION)'
        ];

        foreach ($departments as $dept) {
            \App\Models\Department::create(['name' => $dept]);
        }
    }
}
