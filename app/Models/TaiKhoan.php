<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TaiKhoan extends Authenticatable
{
    use HasFactory;
    
    public function getRememberTokenName()
    {
        return 'remember_token';
    }
    
    public function getAuthIdentifierName()
    {
        return 'id';
    }
    

    protected $table = 'tai_khoan';
    
    protected $fillable = [
        'nhan_vien_id',
        'ten_dang_nhap',
        'mat_khau',
        'email',
        'vai_tro',
        'trang_thai',
        'lan_dang_nhap_cuoi'
    ];

    protected $hidden = [
        'mat_khau'
    ];
    
    // Override method cho authentication
    public function getAuthPassword()
    {
        return $this->mat_khau;
    }


    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }

    protected $casts = [
        'lan_dang_nhap_cuoi' => 'datetime'
    ];

    // Quan hệ với nhân viên
    public function nhanVien(): BelongsTo
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_id');
    }

    // Scope cho tài khoản hoạt động
    public function scopeHoatDong($query)
    {
        return $query->where('trang_thai', 'hoat_dong');
    }
}
