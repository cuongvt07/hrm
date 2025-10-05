<?php

namespace App\Http\Controllers\BaoCao;

use App\Http\Controllers\Controller;
use App\Models\KhenThuongKyLuat;
use App\Models\KhenThuongKyLuatDoiTuong;
use App\Models\NhanVien;
use App\Models\PhongBan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exports\BaoCaoKyLuatCaNhanExport;
use App\Exports\BaoCaoKyLuatPhongBanExport;
use Maatwebsite\Excel\Facades\Excel;

class BaoCaoKyLuatController extends Controller
{
    private function getBaseQuery(Request $request)
    {
        $baseQuery = KhenThuongKyLuat::with([
            'doiTuongApDung.nhanVien.phongBan',
            'doiTuongApDung.nhanVien.chucVu',
            'doiTuongApDung.phongBan'
        ])->where('loai', 'ky_luat');

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

        // Kỷ luật cá nhân
        $kyLuatCaNhan = (clone $baseQuery)->whereHas('doiTuongApDung', function ($q) {
            $q->where('loai_doi_tuong', 'nhan_vien');
        })->get();

        // Kỷ luật phòng ban
        $kyLuatPhongBan = (clone $baseQuery)->whereHas('doiTuongApDung', function ($q) {
            $q->where('loai_doi_tuong', 'phong_ban');
        })->get();

        return view('bao-cao.ky-luat.index', compact('kyLuatCaNhan', 'kyLuatPhongBan'));
    }

    public function exportCaNhan(Request $request)
    {
        $baseQuery = $this->getBaseQuery($request);
        
        $kyLuatCaNhan = $baseQuery->whereHas('doiTuongApDung', function ($q) {
            $q->where('loai_doi_tuong', 'nhan_vien');
        })->get();

        $filename = 'bao-cao-ky-luat-ca-nhan_' . Carbon::now()->format('d-m-Y') . '.xlsx';
        
        // Set active_tab cho export view
        $request->merge(['active_tab' => 'ca-nhan']);
        
        return Excel::download(new BaoCaoKyLuatCaNhanExport($kyLuatCaNhan), $filename);
    }

    public function exportPhongBan(Request $request)
    {
        $baseQuery = $this->getBaseQuery($request);
        
        $kyLuatPhongBan = $baseQuery->whereHas('doiTuongApDung', function ($q) {
            $q->where('loai_doi_tuong', 'phong_ban');
        })->get();

        $filename = 'bao-cao-ky-luat-phong-ban_' . Carbon::now()->format('d-m-Y') . '.xlsx';
        
        // Set active_tab cho export view
        $request->merge(['active_tab' => 'phong-ban']);
        
        return Excel::download(new BaoCaoKyLuatPhongBanExport($kyLuatPhongBan), $filename);
    }
}