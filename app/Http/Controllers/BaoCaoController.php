<?php

namespace App\Http\Controllers;

use App\Models\NhanVien;
use App\Models\PhongBan;
use App\Models\HopDongLaoDong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BaoCaoController extends Controller
{
    public function index()
    {
        return view('bao-cao.index');
    }

    public function nhanSu(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // Filter nhân viên theo ngày thử việc nếu có
        $nhanVienQuery = NhanVien::query();
        if ($fromDate) {
            $nhanVienQuery->whereDate('ngay_thu_viec', '>=', $fromDate);
        }
        if ($toDate) {
            $nhanVienQuery->whereDate('ngay_thu_viec', '<=', $toDate);
        }

        // Thống kê nhân sự theo phòng ban (áp dụng filter)
        $departmentStats = PhongBan::withCount(['nhanViens' => function($q) use ($fromDate, $toDate) {
            if ($fromDate) $q->whereDate('ngay_thu_viec', '>=', $fromDate);
            if ($toDate) $q->whereDate('ngay_thu_viec', '<=', $toDate);
        }])
        ->orderBy('nhan_vien_count', 'desc')
        ->get();

        // Thống kê theo giới tính (áp dụng filter)
        $genderStats = (clone $nhanVienQuery)
            ->selectRaw('gioi_tinh, COUNT(*) as count')
            ->groupBy('gioi_tinh')
            ->get();

        // Thống kê theo trạng thái (áp dụng filter)
        $statusStats = (clone $nhanVienQuery)
            ->selectRaw('trang_thai, COUNT(*) as count')
            ->groupBy('trang_thai')
            ->get();

        // Thống kê theo độ tuổi (áp dụng filter)
        $ageStats = (clone $nhanVienQuery)
            ->selectRaw('
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
            'ageStats',
            'fromDate',
            'toDate'
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
}
