<?php

namespace App\Http\Controllers;

use App\Models\KhenThuongKyLuat;
use App\Models\KhenThuongKyLuatDoiTuong;
use App\Models\NghiPhep;
use App\Models\NhanVien;
use App\Models\PhongBan;
use Illuminate\Http\Request;

class CheDoController extends Controller
{
    // Quản lý nghỉ phép
    public function nghiPhepIndex(Request $request)
    {
        $query = NghiPhep::with('nhanVien');

        // Tìm kiếm
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('nhanVien', function($q) use ($search) {
                $q->where('ten', 'like', "%{$search}%")
                  ->orWhere('ho', 'like', "%{$search}%");
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Lọc theo loại nghỉ
        if ($request->filled('loai_nghi')) {
            $query->where('loai_nghi', $request->loai_nghi);
        }

        $nghiPheps = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('che-do.nghi-phep.index', compact('nghiPheps'));
    }

    public function nghiPhepShow(NghiPhep $nghiPhep)
    {
        $nghiPhep->load('nhanVien');
        
        return view('che-do.nghi-phep.show', compact('nghiPhep'));
    }

    public function nghiPhepApprove(NghiPhep $nghiPhep)
    {
        $nghiPhep->update(['trang_thai' => 'da_duyet']);

        return redirect()->back()
            ->with('success', 'Duyệt đơn nghỉ phép thành công!');
    }

    public function nghiPhepReject(Request $request, NghiPhep $nghiPhep)
    {
        $request->validate([
            'ly_do_tu_choi' => 'required|string'
        ]);

        $nghiPhep->update([
            'trang_thai' => 'tu_choi',
            'ghi_chu' => $request->ly_do_tu_choi
        ]);

        return redirect()->back()
            ->with('success', 'Từ chối đơn nghỉ phép thành công!');
    }

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
