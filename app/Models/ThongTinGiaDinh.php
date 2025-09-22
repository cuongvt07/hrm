<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThongTinGiaDinh extends Model
{
    use HasFactory;

    protected $table = 'thong_tin_gia_dinh';

    protected $fillable = [
        'nhan_vien_id',
        'quan_he',
        'ho_ten',
        'ngay_sinh',
        'nghe_nghiep',
        'dia_chi_lien_he',
        'dien_thoai',
        'la_nguoi_phu_thuoc',
        'ghi_chu'
    ];

    protected $casts = [
        'ngay_sinh' => 'date',
        'la_nguoi_phu_thuoc' => 'boolean'
    ];

    // Quan hệ với nhân viên
    public function nhanVien(): BelongsTo
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_id');
    }
}
