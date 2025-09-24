<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLoaiGiayToInGiayToTuyThanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('giay_to_tuy_than', function (Blueprint $table) {
            $table->string('loai_giay_to', 50)->nullable()->change();
        });

        // Update values
        DB::table('giay_to_tuy_than')
            ->whereIn('loai_giay_to', ['CMND', 'CCCD', 'Ho_chieu'])
            ->update(['loai_giay_to' => 'giay_to_tuy_than']);
        DB::table('giay_to_tuy_than')
            ->where('loai_giay_to', 'Ma_so_thue')
            ->update(['loai_giay_to' => 'chung_chi']);
        DB::table('giay_to_tuy_than')
            ->where('loai_giay_to', 'Khac')
            ->update(['loai_giay_to' => 'khac']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No way to revert to original values without backup
    }
}
