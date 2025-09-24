<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('bao_hiem', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nhan_vien_id');
            $table->date('ngay_tham_gia_bh')->nullable();
            $table->decimal('ty_le_dong_bh', 5, 2)->nullable();
            $table->decimal('ty_le_bhxh', 5, 2)->nullable();
            $table->decimal('ty_le_bhyt', 5, 2)->nullable();
            $table->decimal('ty_le_bhtn', 5, 2)->nullable();
            $table->string('so_so_bhxh', 50)->nullable();
            $table->string('ma_so_bhxh', 50)->nullable();
            $table->boolean('tham_gia_bao_hiem')->default(true);
            $table->string('tinh_cap', 100)->nullable();
            $table->string('ma_tinh_cap', 20)->nullable();
            $table->string('so_the_bhyt', 50)->nullable();
            $table->timestamps();
            $table->foreign('nhan_vien_id')->references('id')->on('nhanvien')->onDelete('cascade');
        });
    }
    public function down() {
        Schema::dropIfExists('bao_hiem');
    }
};
