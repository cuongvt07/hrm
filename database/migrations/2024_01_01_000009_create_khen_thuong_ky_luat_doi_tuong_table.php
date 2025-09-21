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
        Schema::create('khen_thuong_ky_luat_doi_tuong', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('khen_thuong_ky_luat_id');
            $table->enum('loai_doi_tuong', ['nhan_vien', 'phong_ban']);
            $table->unsignedBigInteger('doi_tuong_id');
            $table->timestamps();
            
            $table->foreign('khen_thuong_ky_luat_id')->references('id')->on('khen_thuong_ky_luat')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khen_thuong_ky_luat_doi_tuong');
    }
};
