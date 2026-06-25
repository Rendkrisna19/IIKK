<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('permits', function (Blueprint $table) {
            $table->text('cancel_message')->nullable()->after('hod_message');
        });
        
        DB::statement("ALTER TABLE permits MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'out', 'returned', 'cancelled') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permits', function (Blueprint $table) {
            $table->dropColumn('cancel_message');
        });
        
        DB::statement("ALTER TABLE permits MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'out', 'returned') DEFAULT 'pending'");
    }
};
