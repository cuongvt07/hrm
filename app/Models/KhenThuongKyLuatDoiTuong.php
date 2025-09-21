<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KhenThuongKyLuatDoiTuong extends Model
{
    use HasFactory;

    protected $table = 'khen_thuong_ky_luat_doi_tuong';
    
    protected $fillable = [
        'khen_thuong_ky_luat_id',
        'loai_doi_tuong',
        'doi_tuong_id'
    ];

    // Quan hệ với khen thưởng/kỷ luật
    public function khenThuongKyLuat(): BelongsTo
    {
        return $this->belongsTo(KhenThuongKyLuat::class, 'khen_thuong_ky_luat_id');
    }

    // Quan hệ với nhân viên (nếu loại_đối_tượng = 'nhan_vien')
    public function nhanVien(): BelongsTo
    {
        return $this->belongsTo(NhanVien::class, 'doi_tuong_id');
    }

    // Quan hệ với phòng ban (nếu loại_đối_tượng = 'phong_ban')
    public function phongBan(): BelongsTo
    {
        return $this->belongsTo(PhongBan::class, 'doi_tuong_id');
    }
}
