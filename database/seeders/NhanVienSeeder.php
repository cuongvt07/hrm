<?php

namespace Database\Seeders;

use App\Models\NhanVien;
use App\Models\PhongBan;
use App\Models\ChucVu;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class NhanVienSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');
        
        $phongBans = PhongBan::all();
        $chucVus = ChucVu::all();
        
        for ($i = 1; $i <= 50; $i++) {
            NhanVien::create([
                'ma_nhanvien' => 'NV' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'ten' => $faker->firstName(),
                'ho' => $faker->lastName(),
                'ngay_sinh' => $faker->dateTimeBetween('-50 years', '-20 years'),
                'gioi_tinh' => $faker->randomElement(['nam', 'nu']),
                'tinh_trang_hon_nhan' => $faker->randomElement(['doc_than', 'da_ket_hon', 'ly_hon']),
                'quoc_tich' => 'Việt Nam',
                'dan_toc' => 'Kinh',
                'ton_giao' => $faker->randomElement(['Không', 'Phật giáo', 'Công giáo', 'Tin lành']),
                'so_dien_thoai' => $faker->phoneNumber(),
                'email' => $faker->unique()->safeEmail(),
                'dia_chi' => $faker->address(),
                'phong_ban_id' => $phongBans->random()->id,
                'chuc_vu_id' => $chucVus->random()->id,
                'ngay_vao_lam' => $faker->dateTimeBetween('-5 years', 'now'),
                'trang_thai' => $faker->randomElement(['nhan_vien_chinh_thuc', 'thu_viec', 'nghi_viec']),
            ]);
        }
    }
}
