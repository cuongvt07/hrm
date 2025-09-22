<?php

namespace App\Http\Controllers;

use App\Models\KhenThuongKyLuat;
use App\Models\KhenThuongKyLuatDoiTuong;
use App\Models\NhanVien;
use App\Models\PhongBan;
use Illuminate\Http\Request;

class CheDoController extends Controller
{
    // Quản lý khen thưởng kỷ luật
    public function khenThuongKyLuatIndex(Request $request)
    {
        $query = KhenThuongKyLuat::with('doiTuongApDung');

        // Lọc theo loại
        if ($request->filled('loai')) {
            $query->where('loai', $request->loai);
        }

        // Tìm kiếm
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('tieu_de', 'like', "%{$search}%")
                  ->orWhere('so_quyet_dinh', 'like', "%{$search}%");
            });
        }

        $khenThuongKyLuats = $query->orderBy('ngay_quyet_dinh', 'desc')->paginate(20);

        return view('che-do.khen-thuong-ky-luat.index', compact('khenThuongKyLuats'));
    }

    public function khenThuongKyLuatCreate()
    {
        $nhanViens = NhanVien::dangLamViec()->get();
        $phongBans = PhongBan::all();
        
        return view('che-do.khen-thuong-ky-luat.create', compact('nhanViens', 'phongBans'));
    }

    public function khenThuongKyLuatStore(Request $request)
    {
        $validated = $request->validate([
            'loai' => 'required|in:khen_thuong,ky_luat',
            'so_quyet_dinh' => 'nullable|string|max:100',
            'ngay_quyet_dinh' => 'required|date',
            'tieu_de' => 'required|string|max:200',
            'mo_ta' => 'nullable|string',
            'gia_tri' => 'nullable|numeric|min:0',
            'nguoi_quyet_dinh' => 'nullable|string|max:200',
            'doi_tuong_ap_dung' => 'required|array|min:1',
            'doi_tuong_ap_dung.*.loai_doi_tuong' => 'required|in:nhan_vien,phong_ban',
            'doi_tuong_ap_dung.*.doi_tuong_id' => 'required|integer'
        ]);

        $khenThuongKyLuat = KhenThuongKyLuat::create($validated);

        // Tạo đối tượng áp dụng
        foreach ($validated['doi_tuong_ap_dung'] as $doiTuong) {
            KhenThuongKyLuatDoiTuong::create([
                'khen_thuong_ky_luat_id' => $khenThuongKyLuat->id,
                'loai_doi_tuong' => $doiTuong['loai_doi_tuong'],
                'doi_tuong_id' => $doiTuong['doi_tuong_id']
            ]);
        }

        return redirect()->route('che-do.khen-thuong-ky-luat.index')
            ->with('success', 'Tạo quyết định thành công!');
    }
}
