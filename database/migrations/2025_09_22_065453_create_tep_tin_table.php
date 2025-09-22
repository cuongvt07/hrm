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
        Schema::create('tep_tin', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nhan_vien_id');
            $table->unsignedBigInteger('module_id')->nullable();
            $table->enum('loai_tep', ['giay_to_tuy_than', 'hop_dong', 'chung_chi', 'khen_thuong', 'ky_luat', 'bao_hiem', 'khac'])->default('khac');
            $table->string('ten_tep', 255)->nullable();
            $table->string('duong_dan_tep', 255);
            $table->string('kieu_mime', 100)->nullable();
            $table->unsignedBigInteger('nguoi_tai_len')->nullable();
            $table->timestamps();

            $table->foreign('nhan_vien_id')->references('id')->on('nhanvien')->onDelete('cascade');
            $table->foreign('nguoi_tai_len')->references('id')->on('tai_khoan')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tep_tin');
    }
};
