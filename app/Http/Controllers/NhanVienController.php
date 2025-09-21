<?php
namespace App\Http\Controllers;

use App\Models\NhanVien;
use App\Models\PhongBan;
use App\Models\ChucVu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NhanVienController extends Controller
{
    public function index(Request $request)
    {
        $query = NhanVien::with(['phongBan', 'chucVu']);

        // Tìm kiếm
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ten', 'like', "%{$search}%")
                  ->orWhere('ho', 'like', "%{$search}%")
                  ->orWhere('ma_nhanvien', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Lọc theo phòng ban
        if ($request->filled('phong_ban_id')) {
            $query->where('phong_ban_id', $request->phong_ban_id);
        }

        // Lọc theo chức vụ
        if ($request->filled('chuc_vu_id')) {
            $query->where('chuc_vu_id', $request->chuc_vu_id);
        }

        // Lọc theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        $nhanViens = $query->paginate(10);
        $phongBans = PhongBan::all();
        $chucVus = ChucVu::all();

        return view('nhan-vien.index', compact('nhanViens', 'phongBans', 'chucVus'));
    }

    public function show(NhanVien $nhanVien)
    {
        $nhanVien->load(['phongBan', 'chucVu', 'taiKhoan', 'hopDongLaoDong', 'nghiPhep']);
        
        return view('nhan-vien.show', compact('nhanVien'));
    }

    public function create()
    {
        $phongBans = PhongBan::all();
        $chucVus = ChucVu::all();
        
        return view('nhan-vien.create', compact('phongBans', 'chucVus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ma_nhanvien' => 'required|string|max:50|unique:nhanvien',
            'ten' => 'required|string|max:100',
            'ho' => 'required|string|max:100',
            'ngay_sinh' => 'nullable|date',
            'gioi_tinh' => 'nullable|in:nam,nu,khac',
            'tinh_trang_hon_nhan' => 'nullable|in:doc_than,da_ket_hon,ly_hon',
            'quoc_tich' => 'nullable|string|max:100',
            'dan_toc' => 'nullable|string|max:100',
            'ton_giao' => 'nullable|string|max:100',
            'so_dien_thoai' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100|unique:nhanvien',
            'dia_chi' => 'nullable|string',
            'phong_ban_id' => 'nullable|exists:phong_ban,id',
            'chuc_vu_id' => 'nullable|exists:chuc_vu,id',
            'ngay_vao_lam' => 'nullable|date',
            'trang_thai' => 'required|in:nhan_vien_chinh_thuc,thu_viec,thai_san,nghi_viec,khac',
            'anh_dai_dien' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('anh_dai_dien')) {
            $validated['anh_dai_dien'] = $request->file('anh_dai_dien')->store('avatars', 'public');
        }

        NhanVien::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thêm nhân viên thành công!'
            ]);
        }

        return redirect()->route('nhan-vien.index')
            ->with('success', 'Thêm nhân viên thành công!');
    }

    public function edit(NhanVien $nhanVien)
    {
        $phongBans = PhongBan::all();
        $chucVus = ChucVu::all();
        
        return view('nhan-vien.edit', compact('nhanVien', 'phongBans', 'chucVus'));
    }

    public function update(Request $request, NhanVien $nhanVien)
    {
        $validated = $request->validate([
            'ma_nhanvien' => 'required|string|max:50|unique:nhanvien,ma_nhanvien,' . $nhanVien->id,
            'ten' => 'required|string|max:100',
            'ho' => 'required|string|max:100',
            'ngay_sinh' => 'nullable|date',
            'gioi_tinh' => 'nullable|in:nam,nu,khac',
            'tinh_trang_hon_nhan' => 'nullable|in:doc_than,da_ket_hon,ly_hon',
            'quoc_tich' => 'nullable|string|max:100',
            'dan_toc' => 'nullable|string|max:100',
            'ton_giao' => 'nullable|string|max:100',
            'so_dien_thoai' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100|unique:nhanvien,email,' . $nhanVien->id,
            'dia_chi' => 'nullable|string',
            'phong_ban_id' => 'nullable|exists:phong_ban,id',
            'chuc_vu_id' => 'nullable|exists:chuc_vu,id',
            'ngay_vao_lam' => 'nullable|date',
            'trang_thai' => 'required|in:nhan_vien_chinh_thuc,thu_viec,thai_san,nghi_viec,khac',
            'anh_dai_dien' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('anh_dai_dien')) {
            // Delete old avatar if exists
            if ($nhanVien->anh_dai_dien && Storage::disk('public')->exists($nhanVien->anh_dai_dien)) {
                Storage::disk('public')->delete($nhanVien->anh_dai_dien);
            }
            $validated['anh_dai_dien'] = $request->file('anh_dai_dien')->store('avatars', 'public');
        }

        $nhanVien->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật nhân viên thành công!'
            ]);
        }

        return redirect()->route('nhan-vien.index')
            ->with('success', 'Cập nhật nhân viên thành công!');
    }

    public function destroy(NhanVien $nhanVien)
    {
        // Delete avatar if exists
        if ($nhanVien->anh_dai_dien && Storage::disk('public')->exists($nhanVien->anh_dai_dien)) {
            Storage::disk('public')->delete($nhanVien->anh_dai_dien);
        }

        $nhanVien->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Xóa nhân viên thành công!'
            ]);
        }

        return redirect()->route('nhan-vien.index')
            ->with('success', 'Xóa nhân viên thành công!');
    }

    public function export(Request $request)
    {
        $query = NhanVien::with(['phongBan', 'chucVu']);

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ten', 'like', "%{$search}%")
                  ->orWhere('ho', 'like', "%{$search}%")
                  ->orWhere('ma_nhanvien', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('phong_ban_id')) {
            $query->where('phong_ban_id', $request->phong_ban_id);
        }

        if ($request->filled('chuc_vu_id')) {
            $query->where('chuc_vu_id', $request->chuc_vu_id);
        }

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        $nhanViens = $query->get();

        $format = $request->get('export', 'excel');

        if ($format === 'pdf') {
            return $this->exportToPdf($nhanViens);
        }

        return $this->exportToExcel($nhanViens);
    }

    private function exportToExcel($nhanViens)
    {
        // For now, return a simple CSV
        $filename = 'danh_sach_nhan_vien_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($nhanViens) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Headers
            fputcsv($file, [
                'STT', 'Mã NV', 'Họ tên', 'Email', 'SĐT', 'Phòng ban', 'Chức vụ', 
                'Trạng thái', 'Ngày vào làm', 'Ngày sinh', 'Giới tính'
            ]);

            foreach ($nhanViens as $index => $nhanVien) {
                fputcsv($file, [
                    $index + 1,
                    $nhanVien->ma_nhanvien,
                    $nhanVien->ho . ' ' . $nhanVien->ten,
                    $nhanVien->email ?? '',
                    $nhanVien->so_dien_thoai ?? '',
                    $nhanVien->phongBan->ten_phong_ban ?? '',
                    $nhanVien->chucVu->ten_chuc_vu ?? '',
                    $this->getStatusText($nhanVien->trang_thai),
                    $nhanVien->ngay_vao_lam ? \Carbon\Carbon::parse($nhanVien->ngay_vao_lam)->format('d/m/Y') : '',
                    $nhanVien->ngay_sinh ? \Carbon\Carbon::parse($nhanVien->ngay_sinh)->format('d/m/Y') : '',
                    $this->getGenderText($nhanVien->gioi_tinh)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportToPdf($nhanViens)
    {
        // Simple PDF export - you can enhance this with a proper PDF library
        $html = view('nhan-vien.export-pdf', compact('nhanViens'))->render();
        
        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="danh_sach_nhan_vien_' . date('Y-m-d_H-i-s') . '.html"');
    }

    private function getStatusText($status)
    {
        $statusMap = [
            'nhan_vien_chinh_thuc' => 'Nhân viên chính thức',
            'thu_viec' => 'Thử việc',
            'thai_san' => 'Thai sản',
            'nghi_viec' => 'Nghỉ việc',
            'khac' => 'Khác'
        ];

        return $statusMap[$status] ?? 'Khác';
    }

    private function getGenderText($gender)
    {
        $genderMap = [
            'nam' => 'Nam',
            'nu' => 'Nữ',
            'khac' => 'Khác'
        ];

        return $genderMap[$gender] ?? '';
    }
}
