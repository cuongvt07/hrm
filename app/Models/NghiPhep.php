<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NghiPhep extends Model
{
    use HasFactory;

    protected $table = 'nghi_phep';
    
    protected $fillable = [
        'nhan_vien_id',
        'loai_nghi',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'ly_do',
        'trang_thai'
    ];

    protected $casts = [
        'ngay_bat_dau' => 'date',
        'ngay_ket_thuc' => 'date'
    ];

    // Quan hệ với nhân viên
    public function nhanVien(): BelongsTo
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_id');
    }

    // Scope cho đơn chờ duyệt
    public function scopeChoDuyet($query)
    {
        return $query->where('trang_thai', 'cho_duyet');
    }

    // Scope cho đơn đã duyệt
    public function scopeDaDuyet($query)
    {
        return $query->where('trang_thai', 'da_duyet');
    }

    // Accessor cho số ngày nghỉ
    public function getSoNgayNghiAttribute(): int
    {
        return $this->ngay_bat_dau->diffInDays($this->ngay_ket_thuc) + 1;
    }
}
