<?php

namespace Database\Seeders;

use App\Models\PhongBan;
use Illuminate\Database\Seeder;

class PhongBanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $phongBans = [
            ['ten_phong_ban' => 'Ban Giám đốc', 'phong_ban_cha_id' => null],
            ['ten_phong_ban' => 'Phòng Nhân sự', 'phong_ban_cha_id' => null],
            ['ten_phong_ban' => 'Phòng Kế toán', 'phong_ban_cha_id' => null],
            ['ten_phong_ban' => 'Phòng Kỹ thuật', 'phong_ban_cha_id' => null],
            ['ten_phong_ban' => 'Phòng Kinh doanh', 'phong_ban_cha_id' => null],
            ['ten_phong_ban' => 'Phòng Marketing', 'phong_ban_cha_id' => null],
            ['ten_phong_ban' => 'Phòng IT', 'phong_ban_cha_id' => null],
            ['ten_phong_ban' => 'Phòng Hành chính', 'phong_ban_cha_id' => null],
        ];

        foreach ($phongBans as $phongBan) {
            PhongBan::create($phongBan);
        }
    }
}
