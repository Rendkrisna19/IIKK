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
        Schema::create('permit_check_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permit_id')->constrained('permits')->cascadeOnDelete();
            $table->foreignId('security_id')->constrained('users');
            $table->enum('check_type', ['OUT', 'IN']);
            $table->timestamp('log_time')->useCurrent();
            $table->integer('late_minutes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permit_check_logs');
    }
};
