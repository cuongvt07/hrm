<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThongTinLuong extends Model
{
    use HasFactory;

    protected $table = 'thong_tin_luong';

    protected $fillable = [
        'nhan_vien_id',
        'luong_co_ban',
        'so_tai_khoan',
        'ten_ngan_hang',
        'chi_nhanh_ngan_hang'
    ];

    public function nhanVien(): BelongsTo
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_id');
    }
}
