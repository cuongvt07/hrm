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
            'trang_thai' => 'nullable|in:chua_thuc_hien,dang_thuc_hien,hoan_thanh',
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
            'trang_thai' => $validated['trang_thai'] ?? 'chua_thuc_hien',
        ]);

        // Lưu đối tượng áp dụng
        foreach ($validated['doi_tuong_ap_dung'] as $id) {
            KhenThuongKyLuatDoiTuong::create([
                'khen_thuong_ky_luat_id' => $khenThuongKyLuat->id,
                'loai_doi_tuong' => $validated['loai_doi_tuong'],
                'doi_tuong_id' => $id,
            ]);
        }

        // Xử lý lưu tệp tin cho từng nhân viên liên quan
        if ($request->hasFile('tep_tin')) {
            $file = $request->file('tep_tin');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('khen-thuong-ky-luat', $fileName, 'public');
            if ($validated['loai_doi_tuong'] === 'nhan_vien') {
                foreach ($validated['doi_tuong_ap_dung'] as $nvId) {
                    if (class_exists('App\\Models\\TepTin')) {
                        \App\Models\TepTin::create([
                            'ten_tep' => $file->getClientOriginalName(),
                            'duong_dan_tep' => $filePath,
                            'loai_tep' => $validated['loai'],
                            'nguoi_tai_len' => auth()->id(),
                            'nhan_vien_id' => $nvId,
                        ]);
                    }
                }
            } elseif ($validated['loai_doi_tuong'] === 'phong_ban') {
                foreach ($validated['doi_tuong_ap_dung'] as $phongBanId) {
                    $nhanViens = \App\Models\NhanVien::where('phong_ban_id', $phongBanId)
                        ->whereNotIn('trang_thai', ['nghi_viec', 'khac'])
                        ->pluck('id');
                        dd($nhanViens);
                    foreach ($nhanViens as $nvId) {
                        if (class_exists('App\\Models\\TepTin')) {
                            \App\Models\TepTin::create([
                                'ten_tep' => $file->getClientOriginalName(),
                                'duong_dan_tep' => $filePath,
                                'loai_tep' => $validated['loai'],
                                'nguoi_tai_len' => auth()->id(),
                                'nhan_vien_id' => $nvId,
                            ]);
                        }
                    }
                }
            }
        }

        if ($khenThuongKyLuat->loai === 'ky_luat') {
            return redirect()->route('che-do.ky-luat.index')
                ->with('success', 'Tạo quyết định thành công!');
        } else {
            return redirect()->route('che-do.khen-thuong.index')
                ->with('success', 'Tạo quyết định thành công!');
        }
    }

    public function khenThuongKyLuatUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'loai' => 'required|in:khen_thuong,ky_luat',
            'so_quyet_dinh' => 'nullable|string|max:100',
            'ngay_quyet_dinh' => 'required|date',
            'tieu_de' => 'required|string|max:200',
            'mo_ta' => 'nullable|string',
            'gia_tri' => 'nullable|numeric|min:0',
            'nguoi_quyet_dinh' => 'nullable|string|max:200',
            'trang_thai' => 'nullable|in:chua_thuc_hien,dang_thuc_hien,hoan_thanh',
        ]);

        $khenThuongKyLuat = KhenThuongKyLuat::findOrFail($id);
        $khenThuongKyLuat->update($validated);

        if ($khenThuongKyLuat->loai === 'ky_luat') {
            return redirect()->route('che-do.ky-luat.index')
                ->with('success', 'Cập nhật quyết định thành công!');
        } else {
            return redirect()->route('che-do.khen-thuong.index')
                ->with('success', 'Cập nhật quyết định thành công!');
        }
    }
}
