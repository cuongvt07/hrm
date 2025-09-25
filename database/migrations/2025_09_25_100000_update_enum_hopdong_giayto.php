<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEnumHopdongGiayto extends Migration
{

    public function up()
    {
        DB::table('hop_dong_lao_dong')
            ->where('trang_thai', 'hoat_dong')
            ->orWhere('trang_thai', 'cham_dut')
            ->update(['trang_thai' => 'hieu_luc']);

        DB::table('hop_dong_lao_dong')
            ->where('trang_thai', 'het_han')
            ->update(['trang_thai' => 'het_hieu_luc']);

        Schema::table('hop_dong_lao_dong', function (Blueprint $table) {
            $table->enum('trang_thai', ['hieu_luc', 'het_hieu_luc'])
                ->default('hieu_luc')
                ->change();
        });

        Schema::table('giay_to_tuy_than', function (Blueprint $table) {
            $table->enum('loai_giay_to', ['giay_to_tuy_than', 'chung_chi', 'bang_cap', 'khac'])
                ->default('giay_to_tuy_than')
                ->change();
        });
    }

    public function down()
    {
        // Khôi phục lại kiểu string nếu cần
        Schema::table('hop_dong_lao_dong', function (Blueprint $table) {
            $table->string('trang_thai', 30)->default('hoat_dong')->change();
        });
        Schema::table('giay_to_tuy_than', function (Blueprint $table) {
            $table->string('loai_giay_to', 50)->nullable()->change();
            $table->string('trang_thai', 30)->default('hieu_luc')->change();
        });
    }
}
