<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuaTrinhCongTac extends Model
{
    use HasFactory;
    protected $table = 'qua_trinh_cong_tac';
    protected $fillable = [
        'nhanvien_id',
        'chucvu_id',
        'phongban_id',
        'mo_ta',
        'ngay_bat_dau',
        'ngay_ket_thuc',
    ];

    public function nhanVien()
    {
        return $this->belongsTo(NhanVien::class, 'nhanvien_id');
    }
    public function chucVu()
    {
        return $this->belongsTo(ChucVu::class, 'chucvu_id');
    }
    public function phongBan()
    {
        return $this->belongsTo(PhongBan::class, 'phongban_id');
    }
}
