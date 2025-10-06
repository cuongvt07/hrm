<?php

namespace App\Exports;

use App\Models\NhanVien;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NhanVienExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $query;

    public function __construct($query = null)
    {
        $this->query = $query;
    }

    public function collection()
    {
        if ($this->query) {
            return $this->query->get();
        }
        return NhanVien::with(['phongBan', 'chucVu', 'giayToTuyThan', 'thongTinLienHe', 'thongTinGiaDinh'])->get();
    }

    public function headings(): array
    {
        return [
            'Mã nhân viên',
            'Họ',
            'Tên',
            'Giới tính',
            'Ngày sinh',
            'Phòng ban',
            'Chức vụ',
            'Ngày vào làm',
            'Ngày thử việc',
            'Trạng thái',
            'Email',
            'Số điện thoại',
            'Địa chỉ'
        ];
    }

    public function map($nhanVien): array
    {
        $gioiTinh = [
            'nam' => 'Nam',
            'nu' => 'Nữ',
            'khac' => 'Khác'
        ];

        $trangThai = [
            'nhan_vien_chinh_thuc' => 'Nhân viên chính thức',
            'thu_viec' => 'Thử việc',
            'thai_san' => 'Thai sản',
            'nghi_viec' => 'Nghỉ việc',
            'khac' => 'Khác'
        ];

        return [
            $nhanVien->ma_nhanvien,
            $nhanVien->ho,
            $nhanVien->ten,
            $gioiTinh[$nhanVien->gioi_tinh] ?? '',
            $nhanVien->ngay_sinh ? $nhanVien->ngay_sinh->format('d/m/Y') : '',
            $nhanVien->phongBan ? $nhanVien->phongBan->ten_phong_ban : '',
            $nhanVien->chucVu ? $nhanVien->chucVu->ten_chuc_vu : '',
            $nhanVien->ngay_vao_lam ? $nhanVien->ngay_vao_lam->format('d/m/Y') : '',
            $nhanVien->ngay_thu_viec ? $nhanVien->ngay_thu_viec->format('d/m/Y') : '',
            $trangThai[$nhanVien->trang_thai] ?? '',
            $nhanVien->email,
            $nhanVien->so_dien_thoai,
            $nhanVien->dia_chi
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}