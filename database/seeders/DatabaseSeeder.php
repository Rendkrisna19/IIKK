<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\PermitType::insert([
            ['name' => 'tugas', 'is_private' => false, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'pribadi', 'is_private' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $this->call([
            UserSeeder::class,
        ]);
    }
}
