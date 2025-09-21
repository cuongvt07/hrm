<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NhanVien extends Model
{
    use HasFactory;

    protected $table = 'nhanvien';
    
    protected $fillable = [
        'ma_nhanvien',
        'ten',
        'ho',
        'ngay_sinh',
        'gioi_tinh',
        'tinh_trang_hon_nhan',
        'quoc_tich',
        'dan_toc',
        'ton_giao',
        'so_dien_thoai',
        'email',
        'dia_chi',
        'phong_ban_id',
        'chuc_vu_id',
        'ngay_vao_lam',
        'trang_thai',
        'anh_dai_dien'
    ];

    protected $casts = [
        'ngay_sinh' => 'date',
        'ngay_vao_lam' => 'date'
    ];

    // Quan hệ với phòng ban
    public function phongBan(): BelongsTo
    {
        return $this->belongsTo(PhongBan::class, 'phong_ban_id');
    }

    // Quan hệ với chức vụ
    public function chucVu(): BelongsTo
    {
        return $this->belongsTo(ChucVu::class, 'chuc_vu_id');
    }

    // Quan hệ với tài khoản
    public function taiKhoan(): HasOne
    {
        return $this->hasOne(TaiKhoan::class, 'nhan_vien_id');
    }

    // Quan hệ với hợp đồng lao động
    public function hopDongLaoDong(): HasMany
    {
        return $this->hasMany(HopDongLaoDong::class, 'nhan_vien_id');
    }

    // Quan hệ với nghỉ phép
    public function nghiPhep(): HasMany
    {
        return $this->hasMany(NghiPhep::class, 'nhan_vien_id');
    }

    // Accessor cho họ tên đầy đủ
    public function getHoTenAttribute(): string
    {
        return $this->ho . ' ' . $this->ten;
    }

    // Scope cho nhân viên đang làm việc
    public function scopeDangLamViec($query)
    {
        return $query->where('trang_thai', 'nhan_vien_chinh_thuc');
    }
}
