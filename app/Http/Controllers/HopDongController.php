<?php

namespace App\Http\Controllers;

use App\Models\HopDongLaoDong;
use App\Models\NhanVien;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            'so_hop_dong' => 'nullable|string|max:100',
            'loai_hop_dong' => 'nullable|string|max:100',
            'ngay_bat_dau' => 'nullable|date',
            'ngay_ket_thuc' => 'nullable|date',
            'trang_thai' => 'nullable|in:hieu_luc,het_hieu_luc',
            'ngay_ky' => 'nullable|date',
            'luong_co_ban' => 'nullable|numeric|min:0',
            'luong_bao_hiem' => 'nullable|numeric|min:0',
            'ghi_chu' => 'nullable|string',
            'vi_tri_cong_viec' => 'nullable|string|max:100',
            'don_vi_ky_hd' => 'nullable|string|max:100',
            'trang_thai_ky' => 'nullable|string|max:50',
            'thoi_han' => 'nullable|integer'
        ]);
        $hopDongCu = HopDongLaoDong::findOrFail($request->hopdong_cu_id);
        $validated['nhan_vien_id'] = $hopDongCu->nhan_vien_id;
        // Thêm mã random vào số hợp đồng để khác biệt
        if (!empty($validated['so_hop_dong'])) {
            $validated['so_hop_dong'] .= '_' . strtoupper(Str::random(6));
        }
        // Tạo hợp đồng mới
        $hopDongMoi = HopDongLaoDong::create($validated);
        \App\Models\ThongTinLuong::updateOrCreate(
            ['nhan_vien_id' => $hopDongMoi->nhan_vien_id],
            ['luong_co_ban' => $hopDongMoi->luong_co_ban]
        );
        // Cập nhật trạng thái hợp đồng cũ
        $hopDongCu->update(['trang_thai' => 'het_hieu_luc']);
        return redirect()->route('hop-dong.index')->with('success', 'Gia hạn hợp đồng thành công!');
    }

    public function sapHetHan(Request $request)
    {
        $query = HopDongLaoDong::with('nhanVien');
        // Lấy danh sách các số hợp đồng đã bị tái ký (tức là có hợp đồng mới bắt đầu bằng số hợp đồng gốc + '_')
        $soHopDongGocDaTaiKi = HopDongLaoDong::whereRaw("so_hop_dong REGEXP '_.{6}$'")
            ->pluck('so_hop_dong')
            ->map(function($so){
                return preg_replace('/_.{6}$/', '', $so);
            })
            ->unique()
            ->toArray();

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

        $hopDongs = $query
            ->whereDate('ngay_ket_thuc', '<=', now()->addMonth())
            ->whereDate('ngay_ket_thuc', '>=', now())
            // Ẩn các hợp đồng hết hiệu lực đã bị tái ký dựa vào tiền tố so_hop_dong
            ->where(function($q) use ($soHopDongGocDaTaiKi) {
                $q->where('trang_thai', '!=', 'het_hieu_luc')
                ->orWhereNotIn('so_hop_dong', $soHopDongGocDaTaiKi);
            })
            ->orderBy('ngay_ket_thuc', 'desc')
            ->paginate(20);
        $nhanViens = NhanVien::dangLamViec()->get();

        if ($request->ajax()) {
            $tableHtml = view('hop-dong.partials.table', compact('hopDongs', 'nhanViens'))->render();
            return response()->json(['table' => $tableHtml]);
        }

        return view('hop-dong.saphethan', compact('hopDongs', 'nhanViens'));
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

        if ($request->ajax()) {
            $tableHtml = view('hop-dong.partials.table', compact('hopDongs', 'nhanViens'))->render();
            return response()->json(['table' => $tableHtml]);
        }

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
        try {
            $validated = $request->validate([
                'nhan_vien_id' => 'nullable|exists:nhanvien,id',
                'so_hop_dong' => 'nullable|string|max:100|unique:hop_dong_lao_dong',
                'loai_hop_dong' => 'nullable|string|max:100',
                'ngay_bat_dau' => 'nullable|date',
                'ngay_ket_thuc' => 'nullable|date|after:ngay_bat_dau',
                'trang_thai' => 'nullable|in:hieu_luc,het_hieu_luc',
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
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function edit(HopDongLaoDong $hopDong)
    {
        $nhanViens = NhanVien::dangLamViec()->get();
        
        return view('hop-dong.edit', compact('hopDong', 'nhanViens'));
    }

    public function update(Request $request, HopDongLaoDong $hopDong)
    {
        try {
            $validated = $request->validate([
                'nhan_vien_id' => 'nullable|exists:nhanvien,id',
                'so_hop_dong' => 'nullable|string|max:100|unique:hop_dong_lao_dong,so_hop_dong,' . $hopDong->id,
                'loai_hop_dong' => 'nullable|string|max:100',
                'ngay_bat_dau' => 'nullable|date',
                'ngay_ket_thuc' => 'nullable|date',
                'trang_thai' => 'nullable|in:hieu_luc,het_hieu_luc',
                'ngay_ky' => 'nullable|date',
                'luong_co_ban' => 'nullable|numeric|min:0',
                'luong_bao_hiem' => 'nullable|numeric|min:0',
                'ghi_chu' => 'nullable|string',
                'vi_tri_cong_viec' => 'nullable|string|max:100',
                'don_vi_ky_hd' => 'nullable|string|max:100',
                'trang_thai_ky' => 'nullable|string|max:50',
                'thoi_han' => 'nullable|integer'
            ]);

            $hopDong->update($validated);
            \App\Models\ThongTinLuong::updateOrCreate(
                ['nhan_vien_id' => $hopDong->nhan_vien_id],
                ['luong_co_ban' => $hopDong->luong_co_ban]
            );
            return redirect()->route('hop-dong.index')
                ->with('success', 'Cập nhật hợp đồng thành công!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function destroy(HopDongLaoDong $hopDong)
    {
        $hopDong->delete();
        return redirect()->route('hop-dong.index')
            ->with('success', 'Xóa hợp đồng thành công!');
    }

    // Bulk update trạng thái hợp đồng
    public function bulkUpdateStatus(Request $request)
    {
        $ids = $request->input('ids', []);
        $status = $request->input('status');
        if (!is_array($ids) || empty($ids) || !$status) {
            return response()->json([
                'success' => false,
                'message' => 'Thiếu dữ liệu hoặc trạng thái.'
            ], 400);
        }
        $updated = \App\Models\HopDongLaoDong::whereIn('id', $ids)->update(['trang_thai' => $status]);
        return response()->json([
            'success' => true,
            'message' => "Đã cập nhật trạng thái cho {$updated} hợp đồng."
        ]);
    }
}
