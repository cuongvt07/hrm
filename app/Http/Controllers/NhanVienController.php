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
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class NhanVienController extends Controller
{
    public function index(Request $request)
    {
        $query = NhanVien::query()->with(['phongBan', 'chucVu']);

        // Lọc search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereRaw("CONCAT(ho, ' ', ten) LIKE ?", ["%$search%"])
                  ->orWhere('ma_nhanvien', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('so_dien_thoai', 'like', "%$search%");
            });
        }

        // Lọc phòng ban
        if ($request->filled('phong_ban_id')) {
            $query->where('phong_ban_id', $request->phong_ban_id);
        }

        // Lọc chức vụ
        if ($request->filled('chuc_vu_id')) {
            $query->where('chuc_vu_id', $request->chuc_vu_id);
        }

        // Lọc trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        $statusOrder = ['nhan_vien_chinh_thuc', 'thu_viec', 'thai_san', 'nghi_viec', 'khac'];
        $nhanViens = $query
            ->orderByRaw("FIELD(trang_thai, '" . implode("','", $statusOrder) . "')")
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'table' => view('nhan-vien.partials.table', compact('nhanViens'))->render(),
                'total' => $nhanViens->total()
            ]);
        }

        $phongBans = PhongBan::all();
        $chucVus = ChucVu::all();

        return view('nhan-vien.index', compact('nhanViens', 'phongBans', 'chucVus'));
    }

    public function show(NhanVien $nhanVien)
    {
        $nhanVien->load(['phongBan', 'chucVu', 'taiKhoan', 'hopDongLaoDong', 'thongTinLienHe', 'thongTinGiaDinh', 'tepTin', 'thongTinGiayTo', 'thongTinLuong', 'quanLyTrucTiep', 'capDuoi']);

        return view('nhan-vien.show', compact('nhanVien'));
    }

    public function create()
    {
        $phongBans = PhongBan::all();
        $chucVus = ChucVu::all();
        $lastId = NhanVien::max('id') ?? 0;
        $nextCode = 'NV' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);

        return view('nhan-vien.create', [
            'nextCode' => $nextCode,
            'phongBans' => $phongBans,
            'chucVus'   => $chucVus
        ]);
    }

    public function store(Request $request)
    {
        try {
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
                'email' => 'nullable|email|max:100|unique:nhanvien,email',
                'dia_chi' => 'nullable|string',
                'phong_ban_id' => 'nullable|exists:phong_ban,id',
                'chuc_vu_id' => 'nullable|exists:chuc_vu,id',
                'ngay_vao_lam' => 'nullable|date',
                'ngay_thu_viec' => 'nullable|date',
                'trang_thai' => 'required|in:nhan_vien_chinh_thuc,thu_viec,thai_san,nghi_viec,khac',
                'anh_dai_dien' => 'nullable|image|max:2048',
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
                'lien_he_khan_cap_dien_thoai' => 'nullable|string|max:20',
                'temp_family_members' => 'nullable|string'
            ]);

            if ($request->hasFile('anh_dai_dien')) {
                $validated['anh_dai_dien'] = $request->file('anh_dai_dien')->store('avatars', 'public');
            }

            $nhanVien = NhanVien::create($validated);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Thêm nhân viên thành công!',
                    'id' => $nhanVien->id
                ]);
            }

            return redirect()->route('nhan-vien.index')
                ->with('success', 'Thêm nhân viên thành công!');

        } catch (ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $e->validator->errors()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $ex) {
            Log::error('Lỗi thêm nhân viên: ' . $ex->getMessage());
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đã có lỗi xảy ra khi thêm nhân viên. Vui lòng thử lại sau.'
                ], 500);
            }
            return redirect()->back()
                ->with('error', 'Đã có lỗi xảy ra khi thêm nhân viên. Vui lòng thử lại sau.')
                ->withInput();
        }
    }

    public function update(Request $request, NhanVien $nhanVien)
    {
        // Validate main employee data
        $validated = $request->validate([
            'ma_nhanvien' => 'required|string|max:50|unique:nhanvien,ma_nhanvien,' . $nhanVien->id,
            'ten' => 'required|string|max:100',
            'ho' => 'required|string|max:100',
            'ngay_sinh_nv' => 'nullable|date',
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
            'lien_he_khan_cap_dien_thoai' => 'nullable|string|max:20',
            // Family members (optional, validated below if provided)
            'temp_family_members' => 'nullable|string',
            // My file (giấy tờ tùy thân)
            'temp_my_files' => 'nullable|string',
            'quan_ly_truc_tiep_id' => 'nullable|exists:nhanvien,id'
        ]);

        // Handle avatar upload
        if ($request->hasFile('anh_dai_dien')) {
            // Delete old avatar if exists
            if ($nhanVien->anh_dai_dien && Storage::disk('public')->exists($nhanVien->anh_dai_dien)) {
                Storage::disk('public')->delete($nhanVien->anh_dai_dien);
            }
            $validated['anh_dai_dien'] = $request->file('anh_dai_dien')->store('avatars', 'public');
        }

        // Rename ngay_sinh_nv to ngay_sinh
        if (isset($validated['ngay_sinh_nv'])) {
            $validated['ngay_sinh'] = $validated['ngay_sinh_nv'];
            unset($validated['ngay_sinh_nv']);
        }

        // Update employee data
        $nhanVien->update($validated);

        // Update or create contact information
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
            ThongTinLienHe::updateOrCreate(
                ['nhan_vien_id' => $nhanVien->id],
                $contactData
            );
        }

        // Handle family members
        if ($request->filled('temp_family_members')) {
            $familyMembers = json_decode($request->input('temp_family_members'), true);
            if (is_array($familyMembers)) {
                foreach ($familyMembers as $member) {
                    // Validate each family member
                    $familyData = [
                        'nhan_vien_id' => $nhanVien->id,
                        'quan_he' => $member['quan_he'] ?? null,
                        'ho_ten' => $member['ho_ten'] ?? null,
                        'ngay_sinh' => $member['ngay_sinh'] ? Carbon::parse($member['ngay_sinh'])->format('Y-m-d') : null,
                        'nghe_nghiep' => $member['nghe_nghiep'] ?? null,
                        'dien_thoai' => $member['dien_thoai'] ?? null,
                        'dia_chi_lien_he' => $member['dia_chi_lien_he'] ?? null,
                        'ghi_chu' => $member['ghi_chu'] ?? null,
                        'la_nguoi_phu_thuoc' => $member['la_nguoi_phu_thuoc'] ?? false
                    ];

                    // Skip if required fields are missing
                    if (empty($familyData['quan_he']) || empty($familyData['ho_ten'])) {
                        continue;
                    }

                    // Check if member has an ID (existing record)
                    if (!empty($member['id'])) {
                        // Update existing record
                        ThongTinGiaDinh::where('id', $member['id'])
                            ->where('nhan_vien_id', $nhanVien->id) // Ensure it belongs to this employee
                            ->update($familyData);
                    } else {
                        // Create new record
                        ThongTinGiaDinh::create($familyData);
                    }
                }
            }
        }

        // Handle giấy tờ tùy thân (my file)
        if ($request->filled('temp_my_files')) {
            $myFilesArr = json_decode($request->input('temp_my_files'), true);
            if (is_array($myFilesArr)) {
                foreach ($myFilesArr as $file) {
                    $fileData = [
                        'nhan_vien_id' => $nhanVien->id,
                        'loai_giay_to' => $file['loai_giay_to'] ?? null,
                        'so_giay_to' => $file['so_giay_to'] ?? null,
                        'ngay_cap' => $file['ngay_cap'] ? Carbon::parse($file['ngay_cap'])->format('Y-m-d') : null,
                        'noi_cap' => $file['noi_cap'] ?? null,
                        'ngay_het_han' => $file['ngay_het_han'] ? Carbon::parse($file['ngay_het_han'])->format('Y-m-d') : null,
                        'ghi_chu' => $file['ghi_chu'] ?? null
                    ];
                    if (empty($fileData['loai_giay_to']) || empty($fileData['so_giay_to'])) {
                        continue;
                    }
                    if (!empty($file['id'])) {
                        \App\Models\GiayToTuyThan::where('id', $file['id'])
                            ->where('nhan_vien_id', $nhanVien->id)
                            ->update($fileData);
                    } else {
                        \App\Models\GiayToTuyThan::create($fileData);
                    }
                }
            }
        }

        // Update or create salary info
        $salaryData = array_filter([
            'nhan_vien_id' => $nhanVien->id,
            'luong_co_ban' => $request->luong_co_ban,
            'so_tai_khoan' => $request->so_tai_khoan,
            'ten_ngan_hang' => $request->ten_ngan_hang,
            'chi_nhanh_ngan_hang' => $request->chi_nhanh_ngan_hang
        ]);

        if (!empty($salaryData) && count($salaryData) > 1) { // More than just nhan_vien_id
            \App\Models\ThongTinLuong::updateOrCreate(
                ['nhan_vien_id' => $nhanVien->id],
                $salaryData
            );
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật nhân viên thành công!'
            ]);
        }

        return redirect()->route('nhan-vien.show', $nhanVien->id)
            ->with('success', 'Cập nhật nhân viên thành công!');
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

        $managers = collect();
        if ($nhanVien->phong_ban_id && $nhanVien->chuc_vu_id) {
            // Lấy id chức vụ hiện tại
            $currentChucVuId = $nhanVien->chuc_vu_id;
            $managers = NhanVien::where('phong_ban_id', $nhanVien->phong_ban_id)->with('chucVu')
                ->where('id', '!=', $nhanVien->id)
                ->whereNotNull('chuc_vu_id')
                ->where('chuc_vu_id', '!=', $currentChucVuId)
                ->get();
        }

        return view('nhan-vien.edit', compact('nhanVien', 'phongBans', 'chucVus', 'managers'));
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids ?? [];

        if (empty($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Chưa chọn nhân viên nào!'
            ], 400);
        }

        $nhanViens = NhanVien::whereIn('id', $ids)->get();

        foreach ($nhanViens as $nv) {
            $nv->update(['trang_thai' => 'nghi_viec']);
            if ($nv->taiKhoan) {
                $nv->taiKhoan->update(['trang_thai' => 'khong_hoat_dong']);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã chuyển trạng thái nghỉ việc cho các nhân viên đã chọn!'
        ]);
    }
}