<?php

namespace App\Http\Controllers;

use App\Models\HopDongLaoDong;
use App\Models\NhanVien;
use Illuminate\Http\Request;

class HopDongController extends Controller
{
    public function giaHanForm($id)
    {
        $hopDongCu = HopDongLaoDong::with('nhanVien')->findOrFail($id);
        return view('hop-dong.giahan', compact('hopDongCu'));
    }

    public function giaHanStore(Request $request)
    {
        $validated = $request->validate([
            'hopdong_cu_id' => 'required|exists:hop_dong_lao_dong,id',
            'nhan_vien_id' => 'nullable|exists:nhanvien,id',
            'so_hop_dong' => 'required|string|max:100|unique:hop_dong_lao_dong',
            'loai_hop_dong' => 'nullable|string|max:100',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'required|date|after:ngay_bat_dau',
            'trang_thai' => 'required|in:hoat_dong,het_han,cham_dut',
            'ngay_ky' => 'nullable|date',
            'luong_co_ban' => 'nullable|numeric|min:0',
            'luong_bao_hiem' => 'nullable|numeric|min:0',
            'ghi_chu' => 'nullable|string',
            'vi_tri_cong_viec' => 'nullable|string|max:100',
            'don_vi_ky_hd' => 'nullable|string|max:100',
            'trang_thai_ky' => 'nullable|string|max:50',
            'thoi_han' => 'nullable|integer'
        ]);
        $hopDongCu = HopDongLaoDong::findOrFail($validated['hopdong_cu_id']);
        $validated['nhan_vien_id'] = $hopDongCu->nhan_vien_id;
        // Tạo hợp đồng mới
        $hopDongMoi = HopDongLaoDong::create($validated);
        \App\Models\ThongTinLuong::updateOrCreate(
            ['nhan_vien_id' => $hopDongMoi->nhan_vien_id],
            ['luong_co_ban' => $hopDongMoi->luong_co_ban]
        );
        // Cập nhật trạng thái hợp đồng cũ
        $hopDongCu->update(['trang_thai' => 'het_han']);
        return redirect()->route('hop-dong.index')->with('success', 'Gia hạn hợp đồng thành công!');
    }
    public function sapHetHan(Request $request)
    {
        $query = HopDongLaoDong::with('nhanVien')->sapHetHan();

        // Tìm kiếm
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('so_hop_dong', 'like', "%{$search}%")
                  ->orWhere('loai_hop_dong', 'like', "%{$search}%")
                  ->orWhereHas('nhanVien', function($q) use ($search) {
                      $q->where('ten', 'like', "%{$search}%")
                        ->orWhere('ho', 'like', "%{$search}%");
                  });
            });
        }

        $hopDongs = $query->orderBy('ngay_ket_thuc', 'asc')->paginate(20);
        return view('hop-dong.saphethan', compact('hopDongs'));
    }
    public function index(Request $request)
    {
        $query = HopDongLaoDong::with('nhanVien');

        // Search: họ tên nhân viên, số hợp đồng, loại hợp đồng
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('so_hop_dong', 'like', "%{$search}%")
                  ->orWhere('loai_hop_dong', 'like', "%{$search}%")
                  ->orWhereHas('nhanVien', function($q) use ($search) {
                      $q->where('ten', 'like', "%{$search}%")
                        ->orWhere('ho', 'like', "%{$search}%")
                        ->orWhereRaw("CONCAT(ho, ' ', ten) like ?", ["%{$search}%"]);
                  });
            });
        }

        // Lọc loại hợp đồng (text)
        if ($request->filled('loai_hop_dong')) {
            $query->where('loai_hop_dong', 'like', "%{$request->loai_hop_dong}%");
        }

        // Lọc theo ngày bắt đầu
        if ($request->filled('ngay_bat_dau')) {
            $query->whereDate('ngay_bat_dau', '>=', $request->ngay_bat_dau);
        }
        // Lọc theo ngày kết thúc
        if ($request->filled('ngay_ket_thuc')) {
            $query->whereDate('ngay_ket_thuc', '<=', $request->ngay_ket_thuc);
        }

        // Lọc theo thời hạn
        if ($request->filled('thoi_han')) {
            $query->where('thoi_han', $request->thoi_han);
        }

        // Lọc theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        $hopDongs = $query->orderBy('ngay_ket_thuc', 'desc')->paginate(20);
        $nhanViens = NhanVien::dangLamViec()->get();

        return view('hop-dong.index', compact('hopDongs', 'nhanViens'));
    }

    public function show(HopDongLaoDong $hopDong)
    {
        $hopDong->load('nhanVien');
        
        return view('hop-dong.show', compact('hopDong'));
    }

    public function create()
    {
        $nhanViens = NhanVien::dangLamViec()->get();
        
        return view('hop-dong.create', compact('nhanViens'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nhan_vien_id' => 'required|exists:nhanvien,id',
            'so_hop_dong' => 'nullable|string|max:100|unique:hop_dong_lao_dong',
            'loai_hop_dong' => 'nullable|string|max:100',
            'ngay_bat_dau' => 'nullable|date',
            'ngay_ket_thuc' => 'nullable|date|after:ngay_bat_dau',
            'trang_thai' => 'nullable|in:hoat_dong,het_han,cham_dut',
            'ngay_ky' => 'nullable|date',
            'luong_co_ban' => 'nullable|numeric|min:0',
            'luong_bao_hiem' => 'nullable|numeric|min:0',
            'ghi_chu' => 'nullable|string',
            'vi_tri_cong_viec' => 'nullable|string|max:100',
            'don_vi_ky_hd' => 'nullable|string|max:100',
            'trang_thai_ky' => 'nullable|string|max:50',
            'thoi_han' => 'nullable|integer'
        ]);

        $hopDong = HopDongLaoDong::create($validated);
        \App\Models\ThongTinLuong::updateOrCreate(
            ['nhan_vien_id' => $hopDong->nhan_vien_id],
            ['luong_co_ban' => $hopDong->luong_co_ban]
        );
        return redirect()->route('hop-dong.index')
            ->with('success', 'Thêm hợp đồng thành công!');
    }

    public function edit(HopDongLaoDong $hopDong)
    {
        $nhanViens = NhanVien::dangLamViec()->get();
        
        return view('hop-dong.edit', compact('hopDong', 'nhanViens'));
    }

    public function update(Request $request, HopDongLaoDong $hopDong)
    {
        $validated = $request->validate([
            'nhan_vien_id' => 'required|exists:nhanvien,id',
            'so_hop_dong' => 'required|string|max:100|unique:hop_dong_lao_dong,so_hop_dong,' . $hopDong->id,
            'loai_hop_dong' => 'required|string|max:100',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'required|date|after:ngay_bat_dau',
            'trang_thai' => 'required|in:hoat_dong,het_han,cham_dut',
            'ngay_ky' => 'required|date',
            'luong_co_ban' => 'required|numeric|min:0',
            'luong_bao_hiem' => 'required|numeric|min:0',
            'ghi_chu' => 'nullable|string',
            'vi_tri_cong_viec' => 'required|string|max:100',
            'don_vi_ky_hd' => 'required|string|max:100',
            'trang_thai_ky' => 'required|string|max:50',
            'thoi_han' => 'required|integer'
        ]);

        $hopDong->update($validated);
        \App\Models\ThongTinLuong::updateOrCreate(
            ['nhan_vien_id' => $hopDong->nhan_vien_id],
            ['luong_co_ban' => $hopDong->luong_co_ban]
        );
        return redirect()->route('hop-dong.index')
            ->with('success', 'Cập nhật hợp đồng thành công!');
    }

    public function destroy(HopDongLaoDong $hopDong)
    {
        $hopDong->delete();

        return redirect()->route('hop-dong.index')
            ->with('success', 'Xóa hợp đồng thành công!');
    }
}
