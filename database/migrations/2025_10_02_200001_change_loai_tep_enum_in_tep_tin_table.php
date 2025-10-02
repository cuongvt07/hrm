<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('tep_tin', function (Blueprint $table) {
            $table->enum('loai_tep', ['avatar', 'cv', 'chung_chi', 'hop_dong', 'khen_thuong', 'ky_luat', 'khac'])->default('khac')->change();
        });
    }

    public function down()
    {
        Schema::table('tep_tin', function (Blueprint $table) {
            $table->string('loai_tep', 20)->default('khac')->change();
        });
    }
};
