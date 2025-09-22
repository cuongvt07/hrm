<?php
namespace App\Http\Controllers;

use App\Models\NhanVien;
use App\Models\PhongBan;
use App\Models\ChucVu;
use App\Models\ThongTinLienHe;
use App\Models\ThongTinGiaDinh;
use App\Models\TepTin;
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

        // Check if this is an AJAX request
        if ($request->ajax()) {
            return response()->json([
                'table' => view('nhan-vien.partials.table', compact('nhanViens'))->render(),
                'pagination' => $nhanViens->hasPages() ? view('vendor.pagination.bootstrap-5', ['paginator' => $nhanViens])->render() : '',
                'total' => $nhanViens->total()
            ]);
        }

        return view('nhan-vien.index', compact('nhanViens', 'phongBans', 'chucVus'));
    }

    public function show(NhanVien $nhanVien)
    {
        $nhanVien->load(['phongBan', 'chucVu', 'taiKhoan', 'hopDongLaoDong', 'thongTinLienHe', 'thongTinGiaDinh', 'tepTin']);

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
            'ngay_thu_viec' => 'nullable|date',
            'trang_thai' => 'required|in:nhan_vien_chinh_thuc,thu_viec,thai_san,nghi_viec,khac',
            'anh_dai_dien' => 'nullable|image|max:2048',
            // Contact information validation
            'dien_thoai_di_dong' => 'nullable|string|max:20',
            'dien_thoai_co_quan' => 'nullable|string|max:20',
            'dien_thoai_nha_rieng' => 'nullable|string|max:20',
            'dien_thoai_khac' => 'nullable|string|max:20',
            'email_co_quan' => 'nullable|email|max:100',
            'email_ca_nhan' => 'nullable|email|max:100',
            'dia_chi_thuong_tru' => 'nullable|string',
            'dia_chi_hien_tai' => 'nullable|string',
            'lien_he_khan_cap_ten' => 'nullable|string|max:100',
            'lien_he_khan_cap_quan_he' => 'nullable|string|max:50',
            'lien_he_khan_cap_dien_thoai' => 'nullable|string|max:20'
        ]);

        if ($request->hasFile('anh_dai_dien')) {
            $validated['anh_dai_dien'] = $request->file('anh_dai_dien')->store('avatars', 'public');
        }

        $nhanVien = NhanVien::create($validated);

        // Create contact information if provided
        $contactData = array_filter([
            'nhan_vien_id' => $nhanVien->id,
            'dien_thoai_di_dong' => $request->dien_thoai_di_dong,
            'dien_thoai_co_quan' => $request->dien_thoai_co_quan,
            'dien_thoai_nha_rieng' => $request->dien_thoai_nha_rieng,
            'dien_thoai_khac' => $request->dien_thoai_khac,
            'email_co_quan' => $request->email_co_quan,
            'email_ca_nhan' => $request->email_ca_nhan,
            'dia_chi_thuong_tru' => $request->dia_chi_thuong_tru,
            'dia_chi_hien_tai' => $request->dia_chi_hien_tai,
            'lien_he_khan_cap_ten' => $request->lien_he_khan_cap_ten,
            'lien_he_khan_cap_quan_he' => $request->lien_he_khan_cap_quan_he,
            'lien_he_khan_cap_dien_thoai' => $request->lien_he_khan_cap_dien_thoai
        ]);

        if (!empty($contactData) && count($contactData) > 1) { // More than just nhan_vien_id
            ThongTinLienHe::create($contactData);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Thêm nhân viên thành công!'
            ]);
        }

        return redirect()->route('nhan-vien.index')
            ->with('success', 'Xóa nhân viên thành công!');
    }

    // AJAX methods for family members
    public function addFamilyMember(Request $request, NhanVien $nhanVien)
    {
        $validated = $request->validate([
            'quan_he' => 'required|string|max:50',
            'ho_ten' => 'required|string|max:100',
            'ngay_sinh' => 'nullable|date',
            'nghe_nghiep' => 'nullable|string|max:100',
            'dia_chi_lien_he' => 'nullable|string',
            'dien_thoai' => 'nullable|string|max:20',
            'la_nguoi_phu_thuoc' => 'nullable|boolean',
            'ghi_chu' => 'nullable|string'
        ]);

        $validated['nhan_vien_id'] = $nhanVien->id;

        ThongTinGiaDinh::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Thêm thành viên gia đình thành công!'
        ]);
    }

    public function updateFamilyMember(Request $request, NhanVien $nhanVien, ThongTinGiaDinh $familyMember)
    {
        // Ensure the family member belongs to the employee
        if ($familyMember->nhan_vien_id !== $nhanVien->id) {
            return response()->json([
                'success' => false,
                'message' => 'Không có quyền truy cập thành viên gia đình này!'
            ], 403);
        }

        $validated = $request->validate([
            'quan_he' => 'required|string|max:50',
            'ho_ten' => 'required|string|max:100',
            'ngay_sinh' => 'nullable|date',
            'nghe_nghiep' => 'nullable|string|max:100',
            'dia_chi_lien_he' => 'nullable|string',
            'dien_thoai' => 'nullable|string|max:20',
            'la_nguoi_phu_thuoc' => 'nullable|boolean',
            'ghi_chu' => 'nullable|string'
        ]);

        $familyMember->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thành viên gia đình thành công!'
        ]);
    }

    public function deleteFamilyMember(Request $request, NhanVien $nhanVien, ThongTinGiaDinh $familyMember)
    {
        // Ensure the family member belongs to the employee
        if ($familyMember->nhan_vien_id !== $nhanVien->id) {
            return response()->json([
                'success' => false,
                'message' => 'Không có quyền truy cập thành viên gia đình này!'
            ], 403);
        }

        $familyMember->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa thành viên gia đình thành công!'
        ]);
    }

    // AJAX methods for documents
    public function addDocument(Request $request, NhanVien $nhanVien)
    {
        $validated = $request->validate([
            'loai_tep' => 'required|string|in:avatar,cv,chung_chi,hop_dong,khac',
            'ten_tep' => 'required|string|max:255',
            'tep_tin' => 'required|file|max:10240' // 10MB max
        ]);

        if ($request->hasFile('tep_tin')) {
            $file = $request->file('tep_tin');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('documents', $fileName, 'public');

            TepTin::create([
                'nhan_vien_id' => $nhanVien->id,
                'module_id' => null, // Có thể mở rộng sau
                'loai_tep' => $validated['loai_tep'],
                'ten_tep' => $validated['ten_tep'],
                'duong_dan_tep' => $filePath,
                'kieu_mime' => $file->getMimeType(),
                'nguoi_tai_len' => 1 // Temporary user ID
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Upload tài liệu thành công!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Không tìm thấy file để upload!'
        ]);
    }

    public function deleteDocument(Request $request, NhanVien $nhanVien, TepTin $document)
    {
        // Ensure the document belongs to the employee
        if ($document->nhan_vien_id !== $nhanVien->id) {
            return response()->json([
                'success' => false,
                'message' => 'Không có quyền truy cập tài liệu này!'
            ], 403);
        }

        // Delete file from storage
        if ($document->duong_dan_tep && Storage::disk('public')->exists($document->duong_dan_tep)) {
            Storage::disk('public')->delete($document->duong_dan_tep);
        }

        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa tài liệu thành công!'
        ]);
    }

    private function getStatusText($status)
    {
        $statusMap = [
            'nhan_vien_chinh_thuc' => 'Đang làm việc',
            'thu_viec' => 'Thử việc',
            'thai_san' => 'Thai sản',
            'nghi_viec' => 'Đã nghỉ việc',
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

    public function edit(NhanVien $nhanVien)
    {
        $nhanVien->load(['thongTinLienHe', 'thongTinGiaDinh', 'tepTin']);
        $phongBans = PhongBan::all();
        $chucVus = ChucVu::all();

        return view('nhan-vien.edit', compact('nhanVien', 'phongBans', 'chucVus'));
    }
}
