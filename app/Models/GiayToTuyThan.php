<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GiayToTuyThan extends Model
{
    use HasFactory;

    protected $table = 'giay_to_tuy_than';

    protected $fillable = [
        'nhan_vien_id',
        'loai_giay_to',
        'so_giay_to',
        'ngay_cap',
        'noi_cap',
        'ngay_het_han',
        'ghi_chu'
    ];

    protected $casts = [
        'ngay_cap' => 'date',
        'ngay_het_han' => 'date'
    ];

    // Quan hệ với nhân viên
    public function nhanVien(): BelongsTo
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_id');
    }
}
