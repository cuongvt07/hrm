<?php

namespace Database\Seeders;

use App\Models\NhanVien;
use App\Models\TaiKhoan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo nhân viên admin
        $nhanVien = NhanVien::create([
            'ma_nhan_vien' => 'ADMIN001',
            'ho' => 'Admin',
            'ten' => 'System',
            'gioi_tinh' => 'nam',
            'ngay_sinh' => '1990-01-01',
            'email' => 'admin@hrm.com',
            'so_dien_thoai' => '0123456789',
            'trang_thai' => 'dang_lam_viec',
            'phong_ban_id' => null,
            'chuc_vu_id' => null,
        ]);

        // Tạo tài khoản admin
        TaiKhoan::create([
            'nhan_vien_id' => $nhanVien->id,
            'ten_dang_nhap' => 'admin',
            'mat_khau' => Hash::make('12345678'),
            'email' => 'admin@hrm.com',
            'vai_tro' => 'quan_tri',
            'trang_thai' => 'hoat_dong',
        ]);
    }
}