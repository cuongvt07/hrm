<?php

namespace Database\Seeders;

use App\Models\ChucVu;
use Illuminate\Database\Seeder;

class ChucVuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chucVus = [
            ['ten_chuc_vu' => 'Giám đốc'],
            ['ten_chuc_vu' => 'Phó Giám đốc'],
            ['ten_chuc_vu' => 'Trưởng phòng'],
            ['ten_chuc_vu' => 'Phó Trưởng phòng'],
            ['ten_chuc_vu' => 'Nhân viên'],
            ['ten_chuc_vu' => 'Thực tập sinh'],
            ['ten_chuc_vu' => 'Chuyên viên'],
            ['ten_chuc_vu' => 'Kỹ sư'],
            ['ten_chuc_vu' => 'Kế toán viên'],
            ['ten_chuc_vu' => 'Nhân viên kinh doanh'],
        ];

        foreach ($chucVus as $chucVu) {
            ChucVu::create($chucVu);
        }
    }
}
