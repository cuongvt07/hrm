<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChucVu extends Model
{
    use HasFactory;

    protected $table = 'chuc_vu';
    
    protected $fillable = [
        'ten_chuc_vu'
    ];

    // Quan hệ với nhân viên
    public function nhanViens(): HasMany
    {
        return $this->hasMany(NhanVien::class, 'chuc_vu_id');
    }
}
