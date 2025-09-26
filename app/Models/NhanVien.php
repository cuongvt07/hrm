<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ThongTinLuong;

class NhanVien extends Model
{
    use HasFactory;

    // Quan hệ thông tin lương
    public function thongTinLuong(): HasOne
    {
        return $this->hasOne(ThongTinLuong::class, 'nhan_vien_id');
    }

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
        'quan_ly_truc_tiep_id',
        'ngay_vao_lam',
        'ngay_thu_viec',
        'trang_thai',
        'anh_dai_dien'
    ];
    // Quan hệ quản lý trực tiếp (mentor/cấp trên)
    public function quanLyTrucTiep(): BelongsTo
    {
        return $this->belongsTo(NhanVien::class, 'quan_ly_truc_tiep_id');
    }

    // Danh sách nhân viên cấp dưới (nếu là quản lý)
    public function capDuoi(): HasMany
    {
        return $this->hasMany(NhanVien::class, 'quan_ly_truc_tiep_id');
    }

    protected $casts = [
        'ngay_sinh' => 'date',
        'ngay_vao_lam' => 'date',
        'ngay_thu_viec' => 'date'
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

    // Quan hệ với giấy tờ tùy thân
    public function giayToTuyThan()
    {
        return $this->hasMany(GiayToTuyThan::class, 'nhan_vien_id');
    }

    // Quan hệ với thông tin liên hệ
    public function thongTinLienHe(): HasOne
    {
        return $this->hasOne(ThongTinLienHe::class, 'nhan_vien_id');
    }

    // Quan hệ với thông tin gia đình
    public function thongTinGiaDinh(): HasMany
    {
        return $this->hasMany(ThongTinGiaDinh::class, 'nhan_vien_id');
    }

    // Quan hệ với tệp tin
    public function tepTin(): HasMany
    {
        return $this->hasMany(TepTin::class, 'nhan_vien_id');
    }

    // Accessor cho họ tên đầy đủ
    public function getHoTenAttribute(): string
    {
        return $this->ho . ' ' . $this->ten;
    }

    // Accessor cho loại hợp đồng (từ hợp đồng lao động đang hoạt động)
    public function getLoaiHopDongAttribute()
    {
        $hopDongHoatDong = $this->hopDongLaoDong()
            ->where('trang_thai', 'hoat_dong')
            ->latest('ngay_bat_dau')
            ->first();

        return $hopDongHoatDong ? $hopDongHoatDong->loai_hop_dong : null;
    }

    // Scope cho nhân viên đang làm việc
    public function scopeDangLamViec($query)
    {
        return $query->where('trang_thai', 'nhan_vien_chinh_thuc');
    }

    public function thongTinGiayTo(): HasMany
    {
        return $this->hasMany(GiayToTuyThan::class, 'nhan_vien_id');
    }

    // Quan hệ với bảo hiểm
    public function baoHiem()
    {
        return $this->hasOne(BaoHiem::class, 'nhan_vien_id');
    }

    // Quan hệ với quá trình công tác
    public function quaTrinhCongTac()
    {
        return $this->hasMany(\App\Models\QuaTrinhCongTac::class, 'nhanvien_id');
    }
}
