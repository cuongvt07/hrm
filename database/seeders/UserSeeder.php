<?php

namespace Database\Seeders;

use App\Models\TaiKhoan;
use App\Models\NhanVien;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo admin user
        TaiKhoan::create([
            'ten_dang_nhap' => 'admin',
            'email' => 'admin@hrm.com',
            'mat_khau' => Hash::make('password'),
            'vai_tro' => 'quan_tri',
            'trang_thai' => 'hoat_dong',
        ]);

        // Tạo một số tài khoản khác
        $nhanViens = NhanVien::limit(10)->get();
        
        foreach ($nhanViens as $nhanVien) {
            TaiKhoan::create([
                'nhan_vien_id' => $nhanVien->id,
                'ten_dang_nhap' => strtolower(str_replace(' ', '', $nhanVien->ho_ten)),
                'email' => $nhanVien->email,
                'mat_khau' => Hash::make('password'),
                'vai_tro' => 'nhan_vien',
                'trang_thai' => 'hoat_dong',
            ]);
        }
    }
}
