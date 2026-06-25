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
        Schema::table('permits', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropForeign(['security_out_id']);
            $table->dropForeign(['security_in_id']);
            
            $table->dropColumn([
                'permit_type',
                'approved_by',
                'approved_at',
                'hod_message',
                'time_out',
                'time_in',
                'late_minutes',
                'security_out_id',
                'security_in_id'
            ]);

            $table->foreignId('permit_type_id')->nullable()->after('user_id')->constrained('permit_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permits', function (Blueprint $table) {
            $table->dropForeign(['permit_type_id']);
            $table->dropColumn('permit_type_id');
            // Reverting columns omitted for brevity
        });
    }
};
