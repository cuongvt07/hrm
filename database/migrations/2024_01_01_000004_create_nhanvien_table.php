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
        Schema::create('nhanvien', function (Blueprint $table) {
            $table->id();
            $table->string('ma_nhanvien', 50)->unique();
            $table->string('ten', 100);
            $table->string('ho', 100);
            $table->date('ngay_sinh')->nullable();
            $table->enum('gioi_tinh', ['nam', 'nu', 'khac'])->nullable();
            $table->enum('tinh_trang_hon_nhan', ['doc_than', 'da_ket_hon', 'ly_hon'])->nullable();
            $table->string('quoc_tich', 100)->default('Việt Nam');
            $table->string('dan_toc', 100)->nullable();
            $table->string('ton_giao', 100)->nullable();
            $table->string('so_dien_thoai', 20)->nullable();
            $table->string('email', 100)->unique()->nullable();
            $table->text('dia_chi')->nullable();
            $table->unsignedBigInteger('phong_ban_id')->nullable();
            $table->unsignedBigInteger('chuc_vu_id')->nullable();
            $table->date('ngay_vao_lam')->nullable();
            $table->enum('trang_thai', ['nhan_vien_chinh_thuc', 'thu_viec', 'thai_san', 'nghi_viec', 'khac'])->default('nhan_vien_chinh_thuc');
            $table->string('anh_dai_dien', 255)->nullable()->comment('Ảnh đại diện nhân viên');
            $table->timestamps();
            
            $table->foreign('phong_ban_id')->references('id')->on('phong_ban')->onDelete('set null');
            $table->foreign('chuc_vu_id')->references('id')->on('chuc_vu')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nhanvien');
    }
};
