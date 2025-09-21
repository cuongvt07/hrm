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
        Schema::create('nghi_phep', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nhan_vien_id');
            $table->enum('loai_nghi', ['nghi_phep_nam', 'nghi_benh', 'nghi_khong_luong', 'nghi_thai_san', 'khac'])->default('khac');
            $table->date('ngay_bat_dau');
            $table->date('ngay_ket_thuc');
            $table->text('ly_do')->nullable();
            $table->enum('trang_thai', ['cho_duyet', 'da_duyet', 'tu_choi'])->default('cho_duyet');
            $table->timestamps();
            
            $table->foreign('nhan_vien_id')->references('id')->on('nhanvien')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nghi_phep');
    }
};
