<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaiDatItem extends Model
{
    use HasFactory;

    protected $table = 'cai_dat_item';

    protected $fillable = [
        'danh_muc_id',
        'ten_item',
        'mo_ta'
    ];

    public function danhMuc()
    {
        return $this->belongsTo(CaiDatHeThong::class, 'danh_muc_id');
    }
}
