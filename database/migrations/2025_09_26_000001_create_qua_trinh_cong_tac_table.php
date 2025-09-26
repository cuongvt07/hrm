<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('qua_trinh_cong_tac', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nhanvien_id');
            $table->unsignedBigInteger('chucvu_id');
            $table->unsignedBigInteger('phongban_id');
            $table->string('mo_ta', 255)->nullable();
            $table->date('ngay_bat_dau');
            $table->date('ngay_ket_thuc')->nullable();
            $table->timestamps();

            $table->foreign('nhanvien_id')->references('id')->on('nhanvien')->onDelete('cascade');
            $table->foreign('chucvu_id')->references('id')->on('chuc_vu')->onDelete('restrict');
            $table->foreign('phongban_id')->references('id')->on('phong_ban')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qua_trinh_cong_tac');
    }
};
