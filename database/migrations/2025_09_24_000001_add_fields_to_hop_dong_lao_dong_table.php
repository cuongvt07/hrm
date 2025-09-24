<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('hop_dong_lao_dong', function (Blueprint $table) {
            $table->string('vi_tri_cong_viec', 100)->nullable()->after('ghi_chu');
            $table->string('don_vi_ky_hd', 100)->nullable()->after('vi_tri_cong_viec');
            $table->string('trang_thai_ky', 50)->nullable()->after('don_vi_ky_hd');
            $table->integer('thoi_han')->nullable()->after('trang_thai_ky'); // số tháng hoặc ngày
        });
    }
    public function down(): void
    {
        Schema::table('hop_dong_lao_dong', function (Blueprint $table) {
            $table->dropColumn(['vi_tri_cong_viec', 'don_vi_ky_hd', 'trang_thai_ky', 'thoi_han']);
        });
    }
};
