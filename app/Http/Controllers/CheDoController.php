<?php

namespace App\Http\Controllers;

use App\Models\KhenThuongKyLuat;
use App\Models\KhenThuongKyLuatDoiTuong;
use App\Models\NhanVien;
use App\Models\PhongBan;
use Illuminate\Http\Request;

class CheDoController extends Controller
{
    // Chi tiết quyết định khen thưởng/kỷ luật
    public function khenThuongKyLuatShow($id)
    {
        $item = KhenThuongKyLuat::with('doiTuongApDung.nhanVien', 'doiTuongApDung.phongBan')->findOrFail($id);
        return view('che-do.khen-thuong-ky-luat.show', compact('item'));
    }
    // Quản lý khen thưởng kỷ luật

    // Danh sách khen thưởng
    public function khenThuongIndex(Request $request)
    {
        $query = KhenThuongKyLuat::with('doiTuongApDung')->where('loai', 'khen_thuong');
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('tieu_de', 'like', "%{$search}%")
                  ->orWhere('so_quyet_dinh', 'like', "%{$search}%");
            });
        }
        if ($request->filled('from')) {
            $query->whereDate('ngay_quyet_dinh', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('ngay_quyet_dinh', '<=', $request->to);
        }
        $khenThuongKyLuats = $query->orderBy('ngay_quyet_dinh', 'desc')->paginate(20);
        $loai = 'khen_thuong';
        return view('che-do.khen-thuong-ky-luat.index', compact('khenThuongKyLuats', 'loai'));
    }

    // Danh sách kỷ luật
    public function kyLuatIndex(Request $request)
    {
        $query = KhenThuongKyLuat::with('doiTuongApDung')->where('loai', 'ky_luat');
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('tieu_de', 'like', "%{$search}%")
                  ->orWhere('so_quyet_dinh', 'like', "%{$search}%");
            });
        }
        if ($request->filled('from')) {
            $query->whereDate('ngay_quyet_dinh', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('ngay_quyet_dinh', '<=', $request->to);
        }
        $khenThuongKyLuats = $query->orderBy('ngay_quyet_dinh', 'desc')->paginate(20);
        $loai = 'ky_luat';
        return view('che-do.khen-thuong-ky-luat.index', compact('khenThuongKyLuats', 'loai'));
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
            'loai_doi_tuong' => 'required|in:nhan_vien,phong_ban',
            'doi_tuong_ap_dung' => 'required|array|min:1',
            'doi_tuong_ap_dung.*' => 'required|integer',
        ]);

        // Lưu quyết định
        $khenThuongKyLuat = KhenThuongKyLuat::create([
            'loai' => $validated['loai'],
            'so_quyet_dinh' => $validated['so_quyet_dinh'] ?? null,
            'ngay_quyet_dinh' => $validated['ngay_quyet_dinh'],
            'tieu_de' => $validated['tieu_de'],
            'mo_ta' => $validated['mo_ta'] ?? null,
            'gia_tri' => $validated['gia_tri'] ?? null,
            'nguoi_quyet_dinh' => $validated['nguoi_quyet_dinh'] ?? null,
        ]);

        // Lưu đối tượng áp dụng
        foreach ($validated['doi_tuong_ap_dung'] as $id) {
            KhenThuongKyLuatDoiTuong::create([
                'khen_thuong_ky_luat_id' => $khenThuongKyLuat->id,
                'loai_doi_tuong' => $validated['loai_doi_tuong'],
                'doi_tuong_id' => $id,
            ]);
        }

        if ($khenThuongKyLuat->loai === 'ky_luat') {
            return redirect()->route('che-do.ky-luat.index')
                ->with('success', 'Tạo quyết định thành công!');
        } else {
            return redirect()->route('che-do.khen-thuong.index')
                ->with('success', 'Tạo quyết định thành công!');
        }
    }
}
