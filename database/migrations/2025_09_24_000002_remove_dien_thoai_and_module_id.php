<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('thong_tin_lien_he', function (Blueprint $table) {
            $table->dropColumn(['dien_thoai_co_quan', 'dien_thoai_nha_rieng']);
        });
        Schema::table('tep_tin', function (Blueprint $table) {
            $table->dropColumn('module_id');
        });
    }

    public function down()
    {
        Schema::table('thong_tin_lien_he', function (Blueprint $table) {
            $table->string('dien_thoai_co_quan')->nullable();
            $table->string('dien_thoai_nha_rieng')->nullable();
        });
        Schema::table('tep_tin', function (Blueprint $table) {
            $table->unsignedBigInteger('module_id')->nullable();
        });
    }
};
