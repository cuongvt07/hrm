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
        Schema::table('nhanvien', function (Blueprint $table) {
            $table->unsignedBigInteger('quan_ly_truc_tiep_id')->nullable()->after('chuc_vu_id');
            $table->foreign('quan_ly_truc_tiep_id')->references('id')->on('nhanvien')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nhanvien', function (Blueprint $table) {
            $table->dropForeign(['quan_ly_truc_tiep_id']);
            $table->dropColumn('quan_ly_truc_tiep_id');
        });
    }
};
