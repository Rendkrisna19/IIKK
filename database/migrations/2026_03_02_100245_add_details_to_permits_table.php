<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permits', function (Blueprint $table) {
            // Nomor Surat Unik
            $table->string('unique_code')->nullable()->after('uuid');
            
            // Jam Rencana Keluar & Masuk
            $table->time('target_time_out')->nullable()->after('reason');
            $table->time('target_time_in')->nullable()->after('target_time_out');
            
            // Kolom mencatat berapa menit karyawan telat
            $table->integer('late_minutes')->nullable()->after('time_in');
        });
    }

    public function down(): void
    {
        Schema::table('permits', function (Blueprint $table) {
            $table->dropColumn(['unique_code', 'target_time_out', 'target_time_in', 'late_minutes']);
        });
    }
};