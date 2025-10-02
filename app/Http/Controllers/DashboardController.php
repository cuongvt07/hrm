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
        $expiringContractsQuery = HopDongLaoDong::with('nhanVien')->sapHetHan(30);
        $stats = [
            'total_employees' => NhanVien::count(),
            'active_employees' => NhanVien::dangLamViec()->count(),
            'total_departments' => PhongBan::count(),
            'expiring_contracts' => $expiringContractsQuery->count(),
        ];

        // Thống kê theo phòng ban
        $departmentStats = PhongBan::withCount('nhanViens')
            ->orderBy('nhan_viens_count', 'desc')
            ->limit(5)
            ->get();

        // Hợp đồng sắp hết hạn
        $expiringContracts = $expiringContractsQuery
            ->orderBy('ngay_ket_thuc')
            ->limit(10)
            ->get();


        // Biểu đồ biến động nhân sự (tăng - giảm)
        $months = collect();
        $increase = collect();
        $decrease = collect();
        $now = now();
        for ($i = 11; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $label = $month->format('m/Y');
            $months->push($label);
            // Nhân viên tăng: created_at trong tháng
            $inc = NhanVien::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $increase->push($inc);
            // Nhân viên giảm: trạng thái nghi_viec và updated_at trong tháng
            $dec = NhanVien::where('trang_thai', 'nghi_viec')
                ->whereYear('updated_at', $month->year)
                ->whereMonth('updated_at', $month->month)
                ->count();
            $decrease->push($dec);
        }
        $staffChangeStats = [
            'months' => $months,
            'increase' => $increase,
            'decrease' => $decrease,
        ];

        // Lấy đơn nghỉ chờ duyệt theo từng loại
        $pendingLeaves = [
            'thu_viec' => collect(),
            'thai_san' => collect(),
            'nghi_viec' => collect(),
            'khac' => collect(),
        ];
        if (class_exists('App\\Models\\DonNghi')) {
            $allPendingLeaves = \App\Models\DonNghi::with('nhanVien')
                ->where('trang_thai', 'cho_duyet')
                ->get();
            foreach ($allPendingLeaves as $leave) {
                $type = $leave->loai_nghi;
                if (!in_array($type, ['thu_viec', 'thai_san', 'nghi_viec'])) {
                    $type = 'khac';
                }
                $pendingLeaves[$type]->push($leave);
            }
        }

        return view('dashboard.index', compact(
            'stats',
            'departmentStats',
            'expiringContracts',
            'staffChangeStats',
            'pendingLeaves'
        ));
    }
}
