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
        Schema::table('tep_tin', function (Blueprint $table) {
            $table->unsignedBigInteger('hop_dong_id')->nullable()->after('nhan_vien_id');
            $table->foreign('hop_dong_id')->references('id')->on('hop_dong_lao_dong')->onDelete('set null');
            $table->dropColumn('kieu_mime');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tep_tin', function (Blueprint $table) {
            $table->string('kieu_mime')->nullable();
            $table->dropForeign(['hop_dong_id']);
            $table->dropColumn('hop_dong_id');
        });
    }
};
