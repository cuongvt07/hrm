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
        Schema::create('hop_dong_lao_dong', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nhan_vien_id');
            $table->string('so_hop_dong', 100)->unique();
            $table->string('loai_hop_dong', 100)->nullable();
            $table->date('ngay_bat_dau')->nullable();
            $table->date('ngay_ket_thuc')->nullable();
            $table->enum('trang_thai', ['hoat_dong', 'het_han', 'cham_dut'])->default('hoat_dong');
            $table->date('ngay_ky')->nullable();
            $table->decimal('luong_co_ban', 15, 2)->nullable();
            $table->decimal('luong_bao_hiem', 15, 2)->nullable();
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
            
            $table->foreign('nhan_vien_id')->references('id')->on('nhanvien')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hop_dong_lao_dong');
    }
};
