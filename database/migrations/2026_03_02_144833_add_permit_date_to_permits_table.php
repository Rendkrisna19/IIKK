<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('permits', function (Blueprint $table) {
            // Menambahkan kolom permit_date bertipe date
            $table->date('permit_date')->nullable()->after('user_id');
        });
    }

    public function down()
    {
        Schema::table('permits', function (Blueprint $table) {
            // Menghapus kolom jika di-rollback
            $table->dropColumn('permit_date');
        });
    }
};