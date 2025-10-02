<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KhenThuongKyLuat extends Model
{
    public $timestamps = false;
    use HasFactory;

    protected $table = 'khen_thuong_ky_luat';
    
    protected $fillable = [
        'loai',
        'so_quyet_dinh',
        'ngay_quyet_dinh',
        'tieu_de',
        'mo_ta',
        'gia_tri',
        'nguoi_quyet_dinh',
        'trang_thai',
    ];

    protected $casts = [
        'ngay_quyet_dinh' => 'date',
        'gia_tri' => 'decimal:2',
        'trang_thai' => 'string',
    ];

    // Quan hệ với đối tượng áp dụng
    public function doiTuongApDung(): HasMany
    {
        return $this->hasMany(KhenThuongKyLuatDoiTuong::class, 'khen_thuong_ky_luat_id');
    }

    // Scope cho khen thưởng
    public function scopeKhenThuong($query)
    {
        return $query->where('loai', 'khen_thuong');
    }

    // Scope cho kỷ luật
    public function scopeKyLuat($query)
    {
        return $query->where('loai', 'ky_luat');
    }
}
