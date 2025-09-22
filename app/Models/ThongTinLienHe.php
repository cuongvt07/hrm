<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThongTinLienHe extends Model
{
    use HasFactory;

    protected $table = 'thong_tin_lien_he';

    protected $fillable = [
        'nhan_vien_id',
        'dien_thoai_di_dong',
        'dien_thoai_co_quan',
        'dien_thoai_nha_rieng',
        'dien_thoai_khac',
        'email_co_quan',
        'email_ca_nhan',
        'dia_chi_thuong_tru',
        'dia_chi_hien_tai',
        'lien_he_khan_cap_ten',
        'lien_he_khan_cap_quan_he',
        'lien_he_khan_cap_dien_thoai'
    ];

    // Quan hệ với nhân viên
    public function nhanVien(): BelongsTo
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_id');
    }
}
