<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('khen_thuong_ky_luat', function (Blueprint $table) {
            $table->enum('trang_thai', ['chua_thuc_hien', 'dang_thuc_hien', 'hoan_thanh'])->default('chua_thuc_hien')->after('nguoi_quyet_dinh');
        });
    }

    public function down()
    {
        Schema::table('khen_thuong_ky_luat', function (Blueprint $table) {
            $table->dropColumn('trang_thai');
        });
    }
};
