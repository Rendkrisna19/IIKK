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
        Schema::create('permit_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permit_id')->constrained('permits')->cascadeOnDelete();
            $table->foreignId('approver_id')->constrained('users');
            $table->enum('status', ['approved', 'rejected']);
            $table->string('hod_message', 500)->nullable();
            $table->timestamp('approved_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permit_approvals');
    }
};
