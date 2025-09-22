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
        Schema::create('thong_tin_lien_he', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nhan_vien_id');
            $table->string('dien_thoai_di_dong', 20);
            $table->string('dien_thoai_co_quan', 20)->nullable();
            $table->string('dien_thoai_nha_rieng', 20)->nullable();
            $table->string('dien_thoai_khac', 20)->nullable();
            $table->string('email_co_quan', 100)->nullable();
            $table->string('email_ca_nhan', 100)->nullable();
            $table->string('dia_chi_thuong_tru', 255)->nullable();
            $table->string('dia_chi_hien_tai', 255)->nullable();
            $table->string('lien_he_khan_cap_ten', 200)->nullable();
            $table->string('lien_he_khan_cap_quan_he', 100)->nullable();
            $table->string('lien_he_khan_cap_dien_thoai', 20)->nullable();
            $table->timestamps();

            $table->foreign('nhan_vien_id')->references('id')->on('nhanvien')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thong_tin_lien_he');
    }
};
