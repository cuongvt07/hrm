<?php

namespace App\Http\Controllers\BaoCao;

use App\Http\Controllers\Controller;
use App\Models\PhongBan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Exports\BaoCaoNhanSuExport;
use Maatwebsite\Excel\Facades\Excel;

class NhanSuController extends Controller
{
    private function getPhongBanData($fromDate, $toDate)
    {
        $phongBans = PhongBan::with([
            'nhanViens' => function ($query) use ($fromDate, $toDate) {
                if ($fromDate && $toDate) {
                    $query->whereBetween('created_at', [$fromDate, $toDate]);
                }
            },
            'nhanViens.hopDongLaoDong'
        ])->get();

        $phongBanStats = $phongBans->map(function ($phongBan) {
            return [
                'ten_phong_ban' => $phongBan->ten_phong_ban,
                'tong_nhan_vien' => $phongBan->nhanViens->count(),
                'nhan_vien_chinh_thuc' => $phongBan->nhanViens->where('trang_thai', 'nhan_vien_chinh_thuc')->count(),
                'thu_viec' => $phongBan->nhanViens->where('trang_thai', 'thu_viec')->count(),
                'nghi_viec' => $phongBan->nhanViens->where('trang_thai', 'nghi_viec')->count(),
                'thai_san' => $phongBan->nhanViens->where('trang_thai', 'thai_san')->count(),
                'khac' => $phongBan->nhanViens->where('trang_thai', 'khac')->count(),
            ];
        });

        $tableData = $phongBans->map(function ($phongBan) {
            $so_tai_ki = 0;
            foreach ($phongBan->nhanViens as $nv) {
                if (!empty($nv->hopDongLaoDong)) {
                    foreach ($nv->hopDongLaoDong as $hd) {
                        if (
                            $hd->trang_thai_ky === 'tai_ki' ||
                            str_contains($hd->so_hop_dong, '_')
                        ) {
                            $so_tai_ki++;
                        }
                    }
                }
            }
            return [
                'phong_ban' => $phongBan->ten_phong_ban,
                'khong_hop_dong' => $phongBan->nhanViens->filter(function ($nv) {
                    return $nv->hopDongLaoDong->isEmpty();
                })->count(),
                'hop_dong_thu_viec' => $phongBan->nhanViens->sum(function ($nv) {
                    return $nv->hopDongLaoDong->where('loai_hop_dong', 'Thử việc')->count();
                }),
                'hop_dong_xac_dinh' => $phongBan->nhanViens->sum(function ($nv) {
                    return $nv->hopDongLaoDong->where('loai_hop_dong', 'Hợp đồng xác định thời hạn')->count();
                }),
                'hop_dong_khong_xac_dinh' => $phongBan->nhanViens->sum(function ($nv) {
                    return $nv->hopDongLaoDong->where('loai_hop_dong', 'Hợp đồng không xác định thời hạn')->count();
                }),
                'hop_dong_tai_ki' => $so_tai_ki,
                'tong_cong' => $phongBan->nhanViens->count(),
            ];
        });

        // Tính tổng các cột
        $totalRow = [
            'phong_ban' => 'Tổng cộng',
            'khong_hop_dong' => $tableData->sum('khong_hop_dong'),
            'hop_dong_thu_viec' => $tableData->sum('hop_dong_thu_viec'),
            'hop_dong_xac_dinh' => $tableData->sum('hop_dong_xac_dinh'),
            'hop_dong_khong_xac_dinh' => $tableData->sum('hop_dong_khong_xac_dinh'),
            'hop_dong_tai_ki' => $tableData->sum('hop_dong_tai_ki'),
            'tong_cong' => $tableData->sum('tong_cong'),
        ];

        return [
            'phongBanStats' => $phongBanStats,
            'tableData' => $tableData,
            'totalRow' => $totalRow
        ];
    }

    public function index(Request $request)
    {
        $fromDate = $request->input('from_date') ? Carbon::parse($request->input('from_date')) : null;
        $toDate = $request->input('to_date') ? Carbon::parse($request->input('to_date')) : null;

        $data = $this->getPhongBanData($fromDate, $toDate);

        return view('bao-cao.nhan-su.index', array_merge(
            $data,
            compact('fromDate', 'toDate')
        ));
    }

    public function export(Request $request)
    {
        $fromDate = $request->input('from_date') ? Carbon::parse($request->input('from_date')) : null;
        $toDate = $request->input('to_date') ? Carbon::parse($request->input('to_date')) : null;

        $data = $this->getPhongBanData($fromDate, $toDate);

        return Excel::download(
            new BaoCaoNhanSuExport($data['tableData'], $fromDate, $toDate, $data['totalRow']),
            'bao_cao_nhan_su_' . now()->format('Y_m_d_H_i_s') . '.xlsx'
        );
    }
}
