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
        Schema::create('khen_thuong_ky_luat', function (Blueprint $table) {
            $table->id();
            $table->enum('loai', ['khen_thuong', 'ky_luat']);
            $table->string('so_quyet_dinh', 100)->nullable();
            $table->date('ngay_quyet_dinh');
            $table->string('tieu_de', 200);
            $table->text('mo_ta')->nullable();
            $table->decimal('gia_tri', 15, 2)->nullable();
            $table->string('nguoi_quyet_dinh', 200)->nullable();
            $table->timestamp('ngay_tao')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khen_thuong_ky_luat');
    }
};
