<?php

namespace App\Http\Controllers;

use App\Models\NhanVien;
use App\Models\HopDongLaoDong;
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
            'expiring_contracts' => HopDongLaoDong::sapHetHan(30)->count(),
        ];

        // Thống kê theo phòng ban
        $departmentStats = PhongBan::withCount('nhanViens')
            ->orderBy('nhan_viens_count', 'desc')
            ->limit(5)
            ->get();

        // Hợp đồng sắp hết hạn
        $expiringContracts = HopDongLaoDong::with('nhanVien')
            ->sapHetHan(30)
            ->orderBy('ngay_ket_thuc')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact(
            'stats',
            'departmentStats',
            'expiringContracts'
        ));
    }
}
