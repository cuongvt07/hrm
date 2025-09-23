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
        Schema::create('thong_tin_luong', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nhan_vien_id');
            $table->decimal('luong_co_ban', 15, 2)->nullable();
            $table->string('so_tai_khoan', 50)->nullable();
            $table->string('ten_ngan_hang', 100)->nullable();
            $table->string('chi_nhanh_ngan_hang', 100)->nullable();
            $table->timestamps();

            $table->foreign('nhan_vien_id')->references('id')->on('nhanvien')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thong_tin_luong');
    }
};
