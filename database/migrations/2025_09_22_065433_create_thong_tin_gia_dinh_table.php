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
        Schema::create('thong_tin_gia_dinh', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nhan_vien_id');
            $table->string('quan_he', 100);
            $table->string('ho_ten', 200);
            $table->date('ngay_sinh')->nullable();
            $table->string('nghe_nghiep', 100)->nullable();
            $table->string('dia_chi_lien_he', 255)->nullable();
            $table->string('dien_thoai', 20)->nullable();
            $table->boolean('la_nguoi_phu_thuoc')->default(false);
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
        Schema::dropIfExists('thong_tin_gia_dinh');
    }
};
