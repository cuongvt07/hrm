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
        Schema::create('giay_to_tuy_than', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nhan_vien_id');
            $table->enum('loai_giay_to', ['CMND', 'CCCD', 'Ho_chieu', 'Ma_so_thue', 'Khac'])->default('CCCD');
            $table->string('so_giay_to', 50);
            $table->date('ngay_cap')->nullable();
            $table->string('noi_cap', 255)->nullable();
            $table->date('ngay_het_han')->nullable();
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
        Schema::dropIfExists('giay_to_tuy_than');
    }
};
