<?php

namespace App\Http\Controllers;

use App\Models\HopDongLaoDong;
use App\Models\NhanVien;
use Illuminate\Http\Request;

class HopDongController extends Controller
{
    public function index(Request $request)
    {
        $query = HopDongLaoDong::with('nhanVien');

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

        // Lọc theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Lọc theo loại hợp đồng
        if ($request->filled('loai_hop_dong')) {
            $query->where('loai_hop_dong', $request->loai_hop_dong);
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
            'so_hop_dong' => 'required|string|max:100|unique:hop_dong_lao_dong',
            'loai_hop_dong' => 'nullable|string|max:100',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'required|date|after:ngay_bat_dau',
            'trang_thai' => 'required|in:hoat_dong,het_han,cham_dut',
            'ngay_ky' => 'nullable|date',
            'luong_co_ban' => 'nullable|numeric|min:0',
            'luong_bao_hiem' => 'nullable|numeric|min:0',
            'ghi_chu' => 'nullable|string'
        ]);

        HopDongLaoDong::create($validated);

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
            'loai_hop_dong' => 'nullable|string|max:100',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'required|date|after:ngay_bat_dau',
            'trang_thai' => 'required|in:hoat_dong,het_han,cham_dut',
            'ngay_ky' => 'nullable|date',
            'luong_co_ban' => 'nullable|numeric|min:0',
            'luong_bao_hiem' => 'nullable|numeric|min:0',
            'ghi_chu' => 'nullable|string'
        ]);

        $hopDong->update($validated);

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
