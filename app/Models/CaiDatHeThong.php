<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaiDatHeThong extends Model
{
    use HasFactory;

    protected $table = 'cai_dat_he_thong';

    protected $fillable = [
        'ten_cai_dat',
        'gia_tri_cai_dat',
        'mo_ta'
    ];

    // Scope cho cài đặt hoạt động
    public function scopeHoatDong($query)
    {
        return $query->where('ten_cai_dat', 'hoat_dong');
    }
}
