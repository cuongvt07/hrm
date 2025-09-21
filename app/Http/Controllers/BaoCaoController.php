<?php

namespace App\Http\Controllers;

use App\Models\NhanVien;
use App\Models\PhongBan;
use App\Models\HopDongLaoDong;
use App\Models\NghiPhep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BaoCaoController extends Controller
{
    public function index()
    {
        return view('bao-cao.index');
    }

    public function nhanSu()
    {
        // Thống kê nhân sự theo phòng ban
        $departmentStats = PhongBan::withCount('nhanViens')
            ->orderBy('nhan_vien_count', 'desc')
            ->get();

        // Thống kê theo giới tính
        $genderStats = NhanVien::selectRaw('gioi_tinh, COUNT(*) as count')
            ->groupBy('gioi_tinh')
            ->get();

        // Thống kê theo trạng thái
        $statusStats = NhanVien::selectRaw('trang_thai, COUNT(*) as count')
            ->groupBy('trang_thai')
            ->get();

        // Thống kê theo độ tuổi
        $ageStats = NhanVien::selectRaw('
            CASE 
                WHEN TIMESTAMPDIFF(YEAR, ngay_sinh, CURDATE()) < 25 THEN "Dưới 25"
                WHEN TIMESTAMPDIFF(YEAR, ngay_sinh, CURDATE()) BETWEEN 25 AND 35 THEN "25-35"
                WHEN TIMESTAMPDIFF(YEAR, ngay_sinh, CURDATE()) BETWEEN 36 AND 45 THEN "36-45"
                WHEN TIMESTAMPDIFF(YEAR, ngay_sinh, CURDATE()) BETWEEN 46 AND 55 THEN "46-55"
                ELSE "Trên 55"
            END as age_group,
            COUNT(*) as count
        ')
        ->whereNotNull('ngay_sinh')
        ->groupBy('age_group')
        ->get();

        return view('bao-cao.nhan-su', compact(
            'departmentStats',
            'genderStats',
            'statusStats',
            'ageStats'
        ));
    }

    public function hopDong()
    {
        // Thống kê hợp đồng theo trạng thái
        $contractStatusStats = HopDongLaoDong::selectRaw('trang_thai, COUNT(*) as count')
            ->groupBy('trang_thai')
            ->get();

        // Hợp đồng sắp hết hạn
        $expiringContracts = HopDongLaoDong::with('nhanVien')
            ->sapHetHan(30)
            ->orderBy('ngay_ket_thuc')
            ->get();

        // Thống kê hợp đồng theo tháng
        $monthlyContractStats = HopDongLaoDong::selectRaw('MONTH(ngay_ky) as month, COUNT(*) as count')
            ->whereYear('ngay_ky', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('bao-cao.hop-dong', compact(
            'contractStatusStats',
            'expiringContracts',
            'monthlyContractStats'
        ));
    }

    public function nghiPhep()
    {
        // Thống kê nghỉ phép theo loại
        $leaveTypeStats = NghiPhep::selectRaw('loai_nghi, COUNT(*) as count')
            ->groupBy('loai_nghi')
            ->get();

        // Thống kê nghỉ phép theo trạng thái
        $leaveStatusStats = NghiPhep::selectRaw('trang_thai, COUNT(*) as count')
            ->groupBy('trang_thai')
            ->get();

        // Thống kê nghỉ phép theo tháng
        $monthlyLeaveStats = NghiPhep::selectRaw('MONTH(ngay_bat_dau) as month, COUNT(*) as count')
            ->whereYear('ngay_bat_dau', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top nhân viên nghỉ nhiều nhất
        $topLeaveEmployees = NghiPhep::selectRaw('nhan_vien_id, COUNT(*) as total_leaves')
            ->with('nhanVien')
            ->groupBy('nhan_vien_id')
            ->orderBy('total_leaves', 'desc')
            ->limit(10)
            ->get();

        return view('bao-cao.nghi-phep', compact(
            'leaveTypeStats',
            'leaveStatusStats',
            'monthlyLeaveStats',
            'topLeaveEmployees'
        ));
    }

    public function exportNhanSu()
    {
        // Logic xuất báo cáo nhân sự
        return response()->download(/* file path */);
    }

    public function exportHopDong()
    {
        // Logic xuất báo cáo hợp đồng
        return response()->download(/* file path */);
    }

    public function exportNghiPhep()
    {
        // Logic xuất báo cáo nghỉ phép
        return response()->download(/* file path */);
    }
}
