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
        // Trước khi tính toán dashboard, cập nhật trạng thái các hợp đồng đã hết hạn
        try {
            \App\Models\HopDongLaoDong::whereNotNull('ngay_ket_thuc')
                ->whereDate('ngay_ket_thuc', '<', now())
                ->where('trang_thai', '!=', 'het_hieu_luc')
                ->update(['trang_thai' => 'het_hieu_luc']);
        } catch (\Exception $e) {
            // Nếu có lỗi, ghi log và tiếp tục (không nên phá vỡ giao diện dashboard)
            \Log::error('Error updating expired contracts status on dashboard load: ' . $e->getMessage());
        }

        // Thống kê tổng quan
        // Lọc chỉ lấy những hợp đồng đang có trạng thái 'hieu_luc' (đang hiệu lực)
        $expiringContractsQuery = HopDongLaoDong::with(['nhanVien.phongBan', 'nhanVien.chucVu'])
            ->sapHetHan(30)
            ->where('trang_thai', 'hieu_luc');
        $stats = [
            'total_employees' => NhanVien::whereIn('trang_thai', ['dang_lam_viec', 'thu_viec'])->count(),
            'active_employees' => NhanVien::dangLamViec()->count(),
            'resigned_employees' => NhanVien::where('trang_thai', 'nghi_viec')->count(),
            'probation_employees' => NhanVien::where('trang_thai', 'thu_viec')->count(),
            'maternity_employees' => NhanVien::where('trang_thai', 'thai_san')->count(),
            'total_departments' => PhongBan::count(),
            'expiring_contracts' => $expiringContractsQuery->count(),
        ];

        // Thống kê theo phòng ban
        $departmentStats = PhongBan::withCount(['nhanViens', 'nhanViens as nhan_vien_active_count' => function ($query) {
                $query->where('trang_thai', 'dang_lam_viec');
            }])
            ->with(['nhanViens.chucVu', 'phongBanCon'])
            ->orderBy('ten_phong_ban')
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
                if (in_array($type, ['thu_viec', 'thai_san', 'nghi_viec'])) {
                    $pendingLeaves[$type]->push($leave);
                } else {
                    $pendingLeaves['khac']->push($leave);
                }
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
