<?php

namespace App\Http\Controllers\BaoCao;

use App\Http\Controllers\Controller;
use App\Models\KhenThuongKyLuat;
use App\Models\KhenThuongKyLuatDoiTuong;
use App\Models\NhanVien;
use App\Models\PhongBan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exports\BaoCaoKhenThuongCaNhanExport;
use App\Exports\BaoCaoKhenThuongPhongBanExport;
use Maatwebsite\Excel\Facades\Excel;

class BaoCaoKhenThuongController extends Controller
{
    private function getBaseQuery(Request $request)
    {
        $baseQuery = KhenThuongKyLuat::with([
            'doiTuongApDung.nhanVien.phongBan',
            'doiTuongApDung.nhanVien.chucVu',
            'doiTuongApDung.phongBan'
        ])->where('loai', 'khen_thuong');

        if ($request->filled(['tu_ngay', 'den_ngay'])) {
            $tuNgay = Carbon::parse($request->tu_ngay)->startOfDay();
            $denNgay = Carbon::parse($request->den_ngay)->endOfDay();
            $baseQuery->whereBetween('ngay_quyet_dinh', [$tuNgay, $denNgay]);
        }

        return $baseQuery;
    }

    public function index(Request $request)
    {
        $baseQuery = $this->getBaseQuery($request);

        // Khen thưởng cá nhân
        $khenThuongCaNhan = (clone $baseQuery)->whereHas('doiTuongApDung', function ($q) {
            $q->where('loai_doi_tuong', 'nhan_vien');
        })->get();

        // Khen thưởng phòng ban
        $khenThuongPhongBan = (clone $baseQuery)->whereHas('doiTuongApDung', function ($q) {
            $q->where('loai_doi_tuong', 'phong_ban');
        })->get();

        return view('bao-cao.khen-thuong.index', compact('khenThuongCaNhan', 'khenThuongPhongBan'));
    }

    public function exportCaNhan(Request $request)
    {
        $baseQuery = $this->getBaseQuery($request);

        $khenThuongCaNhan = $baseQuery->whereHas('doiTuongApDung', function ($q) {
            $q->where('loai_doi_tuong', 'nhan_vien');
        })->get();

        $filename = 'bao-cao-khen-thuong-ca-nhan_' . Carbon::now()->format('d-m-Y') . '.xlsx';
        return Excel::download(new BaoCaoKhenThuongCaNhanExport($khenThuongCaNhan), $filename);
    }

    public function exportPhongBan(Request $request)
    {
        $baseQuery = $this->getBaseQuery($request);

        $khenThuongPhongBan = $baseQuery->whereHas('doiTuongApDung', function ($q) {
            $q->where('loai_doi_tuong', 'phong_ban');
        })->get();

        $filename = 'bao-cao-khen-thuong-phong-ban_' . Carbon::now()->format('d-m-Y') . '.xlsx';
        return Excel::download(new BaoCaoKhenThuongPhongBanExport($khenThuongPhongBan), $filename);
    }
}