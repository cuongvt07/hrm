<?php

namespace App\Http\Controllers;

use App\Models\NhanVien;
use App\Models\HopDongLaoDong;
use App\Models\NghiPhep;
use App\Models\PhongBan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Thống kê tổng quan
        $stats = [
            'total_employees' => NhanVien::count(),
            'active_employees' => NhanVien::dangLamViec()->count(),
            'total_departments' => PhongBan::count(),
            'pending_leaves' => NghiPhep::choDuyet()->count(),
            'expiring_contracts' => HopDongLaoDong::sapHetHan(30)->count(),
        ];

        // Thống kê theo phòng ban
        $departmentStats = PhongBan::withCount('nhanViens')
            ->orderBy('nhan_viens_count', 'desc')
            ->limit(5)
            ->get();

        // Thống kê nghỉ phép theo tháng
        $monthlyLeaveStats = NghiPhep::selectRaw('MONTH(ngay_bat_dau) as month, COUNT(*) as count')
            ->whereYear('ngay_bat_dau', now()->year)
            ->whereNotNull('ngay_bat_dau')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Hợp đồng sắp hết hạn
        $expiringContracts = HopDongLaoDong::with('nhanVien')
            ->sapHetHan(30)
            ->orderBy('ngay_ket_thuc')
            ->limit(10)
            ->get();

        // Đơn nghỉ chờ duyệt
        $pendingLeaves = NghiPhep::with('nhanVien')
            ->choDuyet()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact(
            'stats',
            'departmentStats',
            'monthlyLeaveStats',
            'expiringContracts',
            'pendingLeaves'
        ));
    }
}
