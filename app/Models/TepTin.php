<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TepTin extends Model
{
    use HasFactory;

    protected $table = 'tep_tin';

    protected $fillable = [
        'nhan_vien_id',
        'module_id',
        'loai_tep',
        'ten_tep',
        'duong_dan_tep',
        'kieu_mime',
        'nguoi_tai_len'
    ];

    // Quan hệ với nhân viên
    public function nhanVien(): BelongsTo
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_id');
    }

    // Quan hệ với người tải lên
    public function nguoiTaiLen(): BelongsTo
    {
        return $this->belongsTo(TaiKhoan::class, 'nguoi_tai_len');
    }
}
