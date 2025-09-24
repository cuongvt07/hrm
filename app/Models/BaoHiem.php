<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaoHiem extends Model
{
    use HasFactory;

    protected $table = 'bao_hiem';

    protected $fillable = [
        'nhan_vien_id',
        'ngay_tham_gia_bh',
        'ty_le_dong_bh',
        'ty_le_bhxh',
        'ty_le_bhyt',
        'ty_le_bhtn',
        'so_so_bhxh',
        'ma_so_bhxh',
        'tham_gia_bao_hiem',
        'tinh_cap',
        'ma_tinh_cap',
        'so_the_bhyt',
    ];

    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_id');
    }
}
