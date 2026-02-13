<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('permits', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        
        // Data Form
        $table->enum('permit_type', ['tugas', 'pribadi']); // Code 1 atau 2 di Form
        $table->text('reason'); // Alasan Meninggalkan Pekerjaan
        
        // Approval Flow
        $table->enum('status', ['pending', 'approved', 'rejected', 'out', 'returned'])->default('pending');
        $table->foreignId('approved_by')->nullable()->constrained('users'); // HOD yang approve
        $table->timestamp('approved_at')->nullable();
        
        // Security Check (Diisi security)
        $table->timestamp('time_out')->nullable(); // Jam Keluar
        $table->timestamp('time_in')->nullable();  // Jam Masuk
        $table->foreignId('security_out_id')->nullable()->constrained('users'); // Security yg jaga saat keluar
        $table->foreignId('security_in_id')->nullable()->constrained('users');  // Security yg jaga saat masuk
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permits');
    }
};
