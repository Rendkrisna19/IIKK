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
       Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name', 25);
        $table->string('nik', 10)->unique()->nullable(); // Dari gambar form
        $table->string('email', 30)->unique();
        $table->string('password', 60);
        // Role: admin, hod, employee, security
        $table->enum('role', ['admin', 'hod', 'employee', 'security'])->default('employee');
        $table->string('position', 100)->nullable(); // Jabatan
        $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
        $table->rememberToken();
        $table->timestamps();
    });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
