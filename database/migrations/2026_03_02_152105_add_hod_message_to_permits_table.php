<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('permits', function (Blueprint $table) {
            // Kita tambahkan kolom hod_message bertipe text yang boleh kosong (nullable)
            // diletakkan setelah kolom status agar rapi
            $table->text('hod_message')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('permits', function (Blueprint $table) {
            $table->dropColumn('hod_message');
        });
    }
};