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
        Schema::create('tai_khoan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nhan_vien_id')->unique()->nullable();
            $table->string('ten_dang_nhap', 100)->unique();
            $table->string('mat_khau', 255);
            $table->string('email', 100)->unique()->nullable();
            $table->enum('vai_tro', ['quan_tri', 'nhan_su', 'quan_ly', 'nhan_vien'])->default('nhan_vien');
            $table->enum('trang_thai', ['hoat_dong', 'khong_hoat_dong'])->default('hoat_dong');
            $table->timestamp('lan_dang_nhap_cuoi')->nullable();
            $table->timestamps();
            
            $table->foreign('nhan_vien_id')->references('id')->on('nhanvien')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tai_khoan');
    }
};
