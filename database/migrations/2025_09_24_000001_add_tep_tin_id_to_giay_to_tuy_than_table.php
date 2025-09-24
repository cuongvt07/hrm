<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('giay_to_tuy_than', function (Blueprint $table) {
            $table->unsignedBigInteger('tep_tin_id')->nullable()->after('id');
            $table->foreign('tep_tin_id')->references('id')->on('tep_tin')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('giay_to_tuy_than', function (Blueprint $table) {
            $table->dropForeign(['tep_tin_id']);
            $table->dropColumn('tep_tin_id');
        });
    }
};
