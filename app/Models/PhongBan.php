<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PhongBan extends Model
{
    use HasFactory;

    protected $table = 'phong_ban';
    
    protected $fillable = [
        'ten_phong_ban',
        'phong_ban_cha_id'
    ];

    // Quan hệ với phòng ban cha
    public function phongBanCha(): BelongsTo
    {
        return $this->belongsTo(PhongBan::class, 'phong_ban_cha_id');
    }

    // Quan hệ với phòng ban con
    public function phongBanCon(): HasMany
    {
        return $this->hasMany(PhongBan::class, 'phong_ban_cha_id');
    }

    // Quan hệ với nhân viên
    public function nhanViens(): HasMany
    {
        return $this->hasMany(NhanVien::class, 'phong_ban_id');
    }
}
