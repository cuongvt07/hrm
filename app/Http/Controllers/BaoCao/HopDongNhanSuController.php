<?php

namespace App\Http\Controllers\BaoCao;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PhongBan;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BaoCaoHopDongNhanSuExport;

class HopDongNhanSuController extends Controller
{
    public function export(Request $request)
    {
        $fromDate = $request->input('from_date') ? Carbon::parse($request->input('from_date')) : null;
        $toDate = $request->input('to_date') ? Carbon::parse($request->input('to_date')) : null;
        $trangThai = $request->input('trang_thai');

        // Lấy toàn bộ phòng ban và nhân viên, áp dụng filter giống như index
        $phongBans = PhongBan::with([
            'nhanViens' => function ($query) use ($fromDate, $toDate) {
                if ($fromDate && $toDate) {
                    $query->whereBetween('ngay_thu_viec', [$fromDate, $toDate]);
                }
            },
            'nhanViens.hopDongLaoDong' => function ($query) use ($fromDate, $toDate, $trangThai) {
                if ($fromDate && $toDate) {
                    $query->whereBetween('ngay_thu_viec', [$fromDate, $toDate]);
                }
                if ($trangThai) {
                    $query->where('trang_thai', $trangThai);
                }
            }
        ])->get();

        $loaiHopDong = [
            'Thử việc',
            'Hợp đồng xác định thời hạn',
            'Hợp đồng không xác định thời hạn',
            'Tái ký',
            'Không có hợp đồng',
        ];

        $tableData = $phongBans->map(function ($phongBan) use ($loaiHopDong, $fromDate, $toDate, $trangThai) {
            $row = [
                'phong_ban' => $phongBan->ten_phong_ban,
            ];
            $so_tai_ki = 0;
            $so_thu_viec = 0;
            $so_xac_dinh = 0;
            $so_khong_xac_dinh = 0;
            $so_khong_co_hop_dong = 0;

            foreach ($phongBan->nhanViens as $nv) {
                $hopDongs = isset($nv->hopDongLaoDong) ? $nv->hopDongLaoDong : collect();
                $filtered = $hopDongs->filter(function ($hd) use ($fromDate, $toDate, $trangThai) {
                    $match = true;
                    if ($fromDate && $toDate) {
                        $match = $match && ($hd->created_at >= $fromDate && $hd->created_at <= $toDate);
                    }
                    if ($trangThai) {
                        $match = $match && ($hd->trang_thai == $trangThai);
                    }
                    return $match;
                });

                if ($filtered->isEmpty()) {
                    $so_khong_co_hop_dong++;
                } else {
                    $so_thu_viec += $filtered->where('loai_hop_dong', 'Thử việc')->count();
                    $so_xac_dinh += $filtered->where('loai_hop_dong', 'Hợp đồng xác định thời hạn')->count();
                    $so_khong_xac_dinh += $filtered->where('loai_hop_dong', 'Hợp đồng không xác định thời hạn')->count();
                    foreach ($filtered as $hd) {
                        if (
                            (isset($hd->trang_thai_ky) && $hd->trang_thai_ky === 'tai_ki') ||
                            (isset($hd->so_hop_dong) && str_contains($hd->so_hop_dong, '_'))
                        ) {
                            $so_tai_ki++;
                        }
                    }
                }
            }

            $row['Thử việc'] = $so_thu_viec;
            $row['Hợp đồng xác định thời hạn'] = $so_xac_dinh;
            $row['Hợp đồng không xác định thời hạn'] = $so_khong_xac_dinh;
            $row['Tái ký'] = $so_tai_ki;
            $row['Không có hợp đồng'] = $so_khong_co_hop_dong;
            $row['tong_cong'] = $so_thu_viec + $so_xac_dinh + $so_khong_xac_dinh + $so_tai_ki;
            return $row;
        });

        // Tổng cộng
        $totalRow = ['phong_ban' => 'Tổng cộng'];
        foreach ($loaiHopDong as $loai) {
            $totalRow[$loai] = $tableData->sum($loai);
        }
        $totalRow['tong_cong'] = collect($loaiHopDong)->sum(fn($loai) => $totalRow[$loai]);

        return Excel::download(
            new BaoCaoHopDongNhanSuExport($tableData, $fromDate, $toDate, $trangThai, $loaiHopDong, $totalRow),
            'bao_cao_hop_dong_nhan_su_' . now()->format('Y_m_d_H_i_s') . '.xlsx'
        );
    }

    public function index(Request $request)
    {
        $fromDate = $request->input('from_date') ? Carbon::parse($request->input('from_date')) : null;
        $toDate = $request->input('to_date') ? Carbon::parse($request->input('to_date')) : null;
        $trangThai = $request->input('trang_thai');

        $phongBans = PhongBan::with(['nhanViens.hopDongLaoDong' => function ($query) use ($fromDate, $toDate, $trangThai) {
            if ($fromDate && $toDate) {
                $query->whereBetween('created_at', [$fromDate, $toDate]);
            }
            if ($trangThai) {
                $query->where('trang_thai', $trangThai);
            }
        }])->get();

        $loaiHopDong = [
            'Thử việc',
            'Hợp đồng xác định thời hạn',
            'Hợp đồng không xác định thời hạn',
            'Tái ký',
            'Không có hợp đồng',
        ];

        $tableData = $phongBans->map(function ($phongBan) use ($loaiHopDong) {
            $row = [
                'phong_ban' => $phongBan->ten_phong_ban,
            ];
            $so_tai_ki = 0;
            $so_thu_viec = 0;
            $so_xac_dinh = 0;
            $so_khong_xac_dinh = 0;
            $so_khong_co_hop_dong = 0;

            foreach ($phongBan->nhanViens as $nv) {
                $hopDongs = isset($nv->hopDongLaoDong) ? $nv->hopDongLaoDong : collect();
                if ($hopDongs->isEmpty()) {
                    $so_khong_co_hop_dong++;
                } else {
                    $so_thu_viec += $hopDongs->where('loai_hop_dong', 'Thử việc')->count();
                    $so_xac_dinh += $hopDongs->where('loai_hop_dong', 'Hợp đồng xác định thời hạn')->count();
                    $so_khong_xac_dinh += $hopDongs->where('loai_hop_dong', 'Hợp đồng không xác định thời hạn')->count();
                    foreach ($hopDongs as $hd) {
                        if (
                            (isset($hd->trang_thai_ky) && $hd->trang_thai_ky === 'tai_ki') ||
                            (isset($hd->so_hop_dong) && str_contains($hd->so_hop_dong, '_'))
                        ) {
                            $so_tai_ki++;
                        }
                    }
                }
            }

            $row['Thử việc'] = $so_thu_viec;
            $row['Hợp đồng xác định thời hạn'] = $so_xac_dinh;
            $row['Hợp đồng không xác định thời hạn'] = $so_khong_xac_dinh;
            $row['Tái ký'] = $so_tai_ki;
            $row['Không có hợp đồng'] = $so_khong_co_hop_dong;
            $row['tong_cong'] = $so_thu_viec + $so_xac_dinh + $so_khong_xac_dinh + $so_tai_ki + $so_khong_co_hop_dong;
            return $row;
        });

        $totalRow = ['phong_ban' => 'Tổng cộng'];
        foreach ($loaiHopDong as $loai) {
            $totalRow[$loai] = $tableData->sum($loai);
        }
        $totalRow['tong_cong'] = collect($loaiHopDong)->sum(fn($loai) => $totalRow[$loai]);

        return view('bao-cao.hop-dong-nhan-su.index', compact(
            'tableData',
            'fromDate',
            'toDate',
            'trangThai',
            'loaiHopDong',
            'totalRow'
        ));
    }
}
