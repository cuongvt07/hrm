<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HopDongLaoDong extends Model
{
    use HasFactory;

    protected $table = 'hop_dong_lao_dong';
    
    protected $fillable = [
        'nhan_vien_id',
        'so_hop_dong',
        'loai_hop_dong',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'trang_thai',
        'ngay_ky',
        'luong_co_ban',
        'luong_bao_hiem',
        'ghi_chu',
        // Bổ sung các trường mới
        'vi_tri_cong_viec', // Vị trí công việc
        'trang_thai_ky',    // Trạng thái ký
        'thoi_han',         // Thời hạn hợp đồng
        'phu_cap_ids'       // Danh sách phụ cấp (JSON array)
    ];

    protected $casts = [
        'ngay_bat_dau' => 'date',
        'ngay_ket_thuc' => 'date',
        'ngay_ky' => 'date',
        'luong_co_ban' => 'decimal:2',
        'luong_bao_hiem' => 'decimal:2',
        'thoi_han' => 'integer', // Thời hạn hợp đồng (số tháng hoặc ngày)
        'phu_cap_ids' => 'array', // Danh sách phụ cấp (JSON array)
    ];

    // Quan hệ với nhân viên
    public function nhanVien(): BelongsTo
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_id');
    }

    // Scope cho hợp đồng đang hoạt động
    public function scopeHoatDong($query)
    {
        return $query->where('trang_thai', 'hoat_dong');
    }

    // Scope cho hợp đồng sắp hết hạn
    // Trả về hợp đồng có `ngay_ket_thuc` không rỗng và nhỏ hơn hoặc bằng (<=) giới hạn ngày (now + $days)
    // Bao gồm cả hợp đồng đã hết hạn (để thống nhất hiển thị giữa các màn hình).
    public function scopeSapHetHan($query, $days = 30)
    {
        $limit = now()->addDays($days);
        return $query->whereNotNull('ngay_ket_thuc')
                     ->whereDate('ngay_ket_thuc', '<=', $limit);
    }

    // Quan hệ với tệp tin hợp đồng
    public function tepTin()
    {
        return $this->hasMany(\App\Models\TepTin::class, 'hop_dong_id', 'id')->where('loai_tep', 'hop_dong');
    }
}
