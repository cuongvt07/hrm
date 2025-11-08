<?php
namespace App\Http\Controllers;

use App\Helpers\FileUploadHelper;
use App\Models\GiayToTuyThan;
use App\Models\NhanVien;
use App\Models\PhongBan;
use App\Models\ChucVu;
use App\Models\ThongTinLienHe;
use App\Models\ThongTinGiaDinh;
use App\Models\TepTin;
use App\Models\ThongTinLuong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use App\Models\BaoHiem;
use App\Models\QuaTrinhCongTac;

class NhanVienController extends Controller
{
    public function index(Request $request)
    {
        $query = NhanVien::query()->with(['phongBan', 'chucVu','taiKhoan']);

        // Lọc search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereRaw("CONCAT(ho, ' ', ten) LIKE ?", ["%$search%"])
                    ->orWhere('ma_nhanvien', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('so_dien_thoai', 'like', "%$search%");
            });
        }

        // Lọc phòng ban (bao gồm cả phòng ban con)
        if ($request->filled('phong_ban_id')) {
            $phongBanId = $request->phong_ban_id;
            // Lấy tất cả phòng ban con
            $phongBanCon = \App\Models\PhongBan::where('phong_ban_cha_id', $phongBanId)->pluck('id')->toArray();
            $allPhongBanIds = array_merge([$phongBanId], $phongBanCon);
            
            $query->whereIn('phong_ban_id', $allPhongBanIds);
        }

        // Lọc chức vụ
        if ($request->filled('chuc_vu_id')) {
            $query->where('chuc_vu_id', $request->chuc_vu_id);
        }

        // Lọc trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        $nhanViens = $query
            ->orderBy('ma_nhanvien', 'desc')
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'table' => view('nhan-vien.partials.table', compact('nhanViens'))->render(),
                'total' => $nhanViens->total()
            ]);
        }

                $phongBans = PhongBan::with('phongBanCon')
            ->whereNull('phong_ban_cha_id')
            ->get();
        $chucVus = ChucVu::all();

        return view('nhan-vien.index', compact('nhanViens', 'phongBans', 'chucVus'));
    }

    public function show(NhanVien $nhanVien)
    {
        $nhanVien->load(['phongBan', 'chucVu', 'taiKhoan', 'hopDongLaoDong', 'thongTinLienHe', 'thongTinGiaDinh', 'tepTin', 'thongTinGiayTo.tepTin', 'thongTinLuong', 'quanLyTrucTiep', 'capDuoi', 'baoHiem', 'taiKhoan']);

        // Lấy danh sách khen thưởng/kỷ luật (cá nhân + tập thể)
        require_once app_path('Helpers/KhenThuongKyLuatHelper.php');
        $ktkl = getKhenThuongKyLuatForNhanVien($nhanVien);
        // Không filter lại, lấy toàn bộ khen thưởng/kỷ luật đã được helper xử lý
        $khenThuong = $ktkl['khenThuong'];
        $kyLuat = $ktkl['kyLuat'];

        return view('nhan-vien.show', compact('nhanVien', 'khenThuong', 'kyLuat'));
    }

    public function create()
    {
    $phongBans = PhongBan::with('phongBanCon')->whereNull('phong_ban_cha_id')->get();
        $chucVus = ChucVu::all();
        $lastId = NhanVien::max('id') ?? 0;
        $nextCode = 'NV' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
        $managers = NhanVien::whereIn('trang_thai', ['nhan_vien_chinh_thuc'])->get();

        return view('nhan-vien.create', [
            'nextCode' => $nextCode,
            'phongBans' => $phongBans,
            'chucVus' => $chucVus,
            'managers' => $managers
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
                'temp_family_members' => 'nullable|string',
                'quan_ly_truc_tiep_id' => 'nullable|exists:nhanvien,id',
                // Thông tin lương
                'so_tai_khoan' => 'nullable|string|max:50',
                'ten_ngan_hang' => 'nullable|string|max:100',
                'chi_nhanh_ngan_hang' => 'nullable|string|max:100',
            ]);

            if ($request->hasFile('anh_dai_dien')) {
                $validated['anh_dai_dien'] = $request->file('anh_dai_dien')->store('avatars', 'public');
            }

            $nhanVien = NhanVien::create($validated);

            // Lưu thông tin bảo hiểm
            $baoHiemData = [
                'nhan_vien_id' => $nhanVien->id,
                'ngay_tham_gia_bh' => $request->ngay_tham_gia_bh,
                'ty_le_dong_bh' => $request->ty_le_dong_bh,
                'ty_le_bhxh' => $request->ty_le_bhxh,
                'ty_le_bhyt' => $request->ty_le_bhyt,
                'ty_le_bhtn' => $request->ty_le_bhtn,
                'so_so_bhxh' => $request->so_so_bhxh,
                'ma_so_bhxh' => $request->ma_so_bhxh,
                'tham_gia_bao_hiem' => $request->tham_gia_bao_hiem ?? 1,
                'tinh_cap' => $request->tinh_cap,
                'ma_tinh_cap' => $request->ma_tinh_cap,
                'so_the_bhyt' => $request->so_the_bhyt,
            ];
            BaoHiem::create($baoHiemData);

            // Lưu thông tin liên hệ
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
            if (!empty($contactData) && count($contactData) > 1) {
                ThongTinLienHe::create($contactData);
            }

            // Lưu thông tin lương
            $salaryData = array_filter([
                'nhan_vien_id' => $nhanVien->id,
                'so_tai_khoan' => $request->so_tai_khoan,
                'ten_ngan_hang' => $request->ten_ngan_hang,
                'chi_nhanh_ngan_hang' => $request->chi_nhanh_ngan_hang
            ]);
            if (!empty($salaryData) && count($salaryData) > 1) {
                ThongTinLuong::create($salaryData);
            }

            // Lưu thông tin gia đình
            if ($request->filled('temp_family_members')) {
                $familyMembers = json_decode($request->temp_family_members, true);
                if (is_array($familyMembers)) {
                    foreach ($familyMembers as $member) {
                        ThongTinGiaDinh::create([
                            'nhan_vien_id' => $nhanVien->id,
                            'quan_he' => $member['quan_he'] ?? null,
                            'ho_ten' => $member['ho_ten'] ?? null,
                            'ngay_sinh' => $member['ngay_sinh'] ?? null,
                            'nghe_nghiep' => $member['nghe_nghiep'] ?? null,
                            'dia_chi_lien_he' => $member['dia_chi_lien_he'] ?? null,
                            'dien_thoai' => $member['dien_thoai'] ?? null,
                            'la_nguoi_phu_thuoc' => $member['la_nguoi_phu_thuoc'] ?? false,
                            'ghi_chu' => $member['ghi_chu'] ?? null
                        ]);
                    }
                }
            }

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
        try {
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
                // Family members (optional)
                'temp_family_members' => 'nullable|string',
                // My file
                'temp_my_files' => 'nullable|string',
                'giay_to_files.*' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
                'quan_ly_truc_tiep_id' => 'nullable|exists:nhanvien,id'
            ]);

            // Handle avatar upload
            if ($request->hasFile('anh_dai_dien')) {
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

            // Nếu trạng thái là 'nghi_viec' thì cập nhật tất cả hợp đồng về 'het_hieu_luc'
            if (($validated['trang_thai'] ?? null) === 'nghi_viec') {
                if (method_exists($nhanVien, 'hopDongLaoDong')) {
                    $nhanVien->hopDongLaoDong()->update(['trang_thai' => 'het_hieu_luc']);
                } else {
                    \App\Models\HopDongLaoDong::where('nhan_vien_id', $nhanVien->id)->update(['trang_thai' => 'het_hieu_luc']);
                }
            }

            // Update or create bảo hiểm
            $baoHiemData = array_filter([
                'nhan_vien_id' => $nhanVien->id,
                'ngay_tham_gia_bh' => $request->ngay_tham_gia_bh,
                'ty_le_dong_bh' => $request->ty_le_dong_bh,
                'ty_le_bhxh' => $request->ty_le_bhxh,
                'ty_le_bhyt' => $request->ty_le_bhyt,
                'ty_le_bhtn' => $request->ty_le_bhtn,
                'so_so_bhxh' => $request->so_so_bhxh,
                'ma_so_bhxh' => $request->ma_so_bhxh,
                'tham_gia_bao_hiem' => $request->tham_gia_bao_hiem ?? 1,
                'tinh_cap' => $request->tinh_cap,
                'ma_tinh_cap' => $request->ma_tinh_cap,
                'so_the_bhyt' => $request->so_the_bhyt,
                'ghi_chu' => $request->ghi_chu_bao_hiem,
            ]);
            BaoHiem::updateOrCreate(
                ['nhan_vien_id' => $nhanVien->id],
                $baoHiemData
            );

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
            if (!empty($contactData) && count($contactData) > 1) {
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

                        if (empty($familyData['quan_he']) || empty($familyData['ho_ten'])) {
                            continue;
                        }

                        if (!empty($member['id'])) {
                            ThongTinGiaDinh::where('id', $member['id'])
                                ->where('nhan_vien_id', $nhanVien->id)
                                ->update($familyData);
                        } else {
                            ThongTinGiaDinh::create($familyData);
                        }
                    }
                }
            }

// ====== Thêm mới các dòng công tác ======
if ($request->filled('cong_tac_existing') && is_array($request->cong_tac_existing)) {
    foreach ($request->cong_tac_existing as $index => $id) {
        $chucvuId    = $request->cong_tac_existing_chucvu[$index] ?? null;
        $phongbanId  = $request->cong_tac_existing_phongban[$index] ?? null;
        $ngayBatDau  = $request->cong_tac_existing_ngay_bat_dau[$index] ?? null;
        $ngayKetThuc = $request->cong_tac_existing_ngay_ket_thuc[$index] ?? null;
        $moTaRaw     = $request->cong_tac_existing_mo_ta[$index] ?? null;

        // 🧠 Chuẩn hóa mo_ta về JSON chuẩn
        $moTaJson = null;
        if ($moTaRaw) {
            $decoded = json_decode($moTaRaw, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $moTaJson = json_encode([
                    'vi_tri' => $decoded['vi_tri'] ?? '',
                    'luong'  => $decoded['luong'] ?? '',
                ], JSON_UNESCAPED_UNICODE);
            } else {
                $moTaJson = json_encode([
                    'vi_tri' => $moTaRaw,
                    'luong'  => ''
                ], JSON_UNESCAPED_UNICODE);
            }
        } else {
            $viTri = $request->cong_tac_existing_vi_tri[$index] ?? '';
            $luong = $request->cong_tac_existing_luong[$index] ?? '';
            $moTaJson = json_encode([
                'vi_tri' => $viTri,
                'luong'  => $luong
            ], JSON_UNESCAPED_UNICODE);
        }

        // ✅ Log thử để kiểm tra
        \Log::info("Update công tác #{$id}", [
            'chucvu_id' => $chucvuId,
            'phongban_id' => $phongbanId,
            'mo_ta' => $moTaJson,
            'ngay_bat_dau' => $ngayBatDau,
            'ngay_ket_thuc' => $ngayKetThuc,
        ]);

        // ✅ Thực hiện cập nhật
        QuaTrinhCongTac::where('id', $id)->update([
            'chucvu_id'     => $chucvuId,
            'phongban_id'   => $phongbanId,
            'mo_ta'         => $moTaJson,
            'ngay_bat_dau'  => $ngayBatDau,
            'ngay_ket_thuc' => $ngayKetThuc,
        ]);
    }
}
        // 🔹 Thêm mới các dòng công tác
        if ($request->has('cong_tac_temp') && is_array($request->cong_tac_temp)) {
            foreach ($request->cong_tac_temp as $row) {
                if (empty($row['chucvu_id']) || empty($row['phongban_id']) || empty($row['ngay_bat_dau'])) {
                    continue;
                }

                $moTaJson = null;
                if (isset($row['mo_ta'])) {
                    if (is_array($row['mo_ta'])) {
                        $moTaJson = json_encode([
                            'vi_tri' => $row['mo_ta']['vi_tri'] ?? '',
                            'luong'  => $row['mo_ta']['luong'] ?? '',
                        ], JSON_UNESCAPED_UNICODE);
                    } elseif (is_string($row['mo_ta'])) {
                        $decoded = json_decode($row['mo_ta'], true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $moTaJson = json_encode([
                                'vi_tri' => $decoded['vi_tri'] ?? '',
                                'luong'  => $decoded['luong'] ?? '',
                            ], JSON_UNESCAPED_UNICODE);
                        } else {
                            $moTaJson = json_encode(['vi_tri' => $row['mo_ta'], 'luong' => ''], JSON_UNESCAPED_UNICODE);
                        }
                    }
                }

                QuaTrinhCongTac::create([
                    'nhanvien_id'   => $nhanVien->id,
                    'chucvu_id'     => $row['chucvu_id'],
                    'phongban_id'   => $row['phongban_id'],
                    'mo_ta'         => $moTaJson,
                    'ngay_bat_dau'  => $row['ngay_bat_dau'],
                    'ngay_ket_thuc' => $row['ngay_ket_thuc'] ?? null,
                ]);
            }
        }


            // Xóa các quá trình công tác theo id gửi lên từ cong_tac_delete[]
            if ($request->has('cong_tac_delete') && is_array($request->cong_tac_delete)) {
                \App\Models\QuaTrinhCongTac::whereIn('id', $request->cong_tac_delete)->delete();
            }

            // Handle giấy tờ tùy thân (my file) & upload files
            if ($request->has('giay_to') && is_array($request->giay_to)) {
                foreach ($request->giay_to as $index => $giayTo) {
                    // Bỏ qua nếu thiếu thông tin bắt buộc
                    if (empty($giayTo['loai_giay_to']) || empty($giayTo['so_giay_to'])) {
                        continue;
                    }

                    $fileData = [
                        'nhan_vien_id' => $nhanVien->id,
                        'loai_giay_to' => $giayTo['loai_giay_to'] ?? null,
                        'so_giay_to' => $giayTo['so_giay_to'] ?? null,
                        'ngay_cap' => !empty($giayTo['ngay_cap']) ? Carbon::parse($giayTo['ngay_cap'])->format('Y-m-d') : null,
                        'ngay_het_han' => !empty($giayTo['ngay_het_han']) ? Carbon::parse($giayTo['ngay_het_han'])->format('Y-m-d') : null,
                        'noi_cap' => $giayTo['noi_cap'] ?? null,
                        'ghi_chu' => $giayTo['ghi_chu'] ?? null,
                    ];

                    // Xử lý upload file (một giấy tờ có thể có nhiều file → multiple)
                    if ($request->hasFile("giay_to.$index.tep_tin")) {
                        $uploadedFiles = $request->file("giay_to.$index.tep_tin");

                        if (!is_array($uploadedFiles)) {
                            $uploadedFiles = [$uploadedFiles]; // convert single thành array
                        }

                        $uploaded = FileUploadHelper::uploadFiles(
                            $uploadedFiles,
                            $giayTo['loai_giay_to'] ?? 'khac',
                            'public',
                            $nhanVien->id,
                            auth()->id()
                        );

                        // Nếu bạn muốn 1 giấy tờ chỉ lưu 1 file → lấy cái đầu tiên
                        $fileData['tep_tin_id'] = $uploaded[0]['id'] ?? null;
                    } else {
                        // Nếu update mà không upload file mới → giữ nguyên file cũ
                        if (!empty($giayTo['id']) && isset($giayTo['tep_tin_id'])) {
                            $fileData['tep_tin_id'] = $giayTo['tep_tin_id'];
                        }
                    }

                    // Update hoặc tạo mới
                    if (!empty($giayTo['id'])) {
                        \App\Models\GiayToTuyThan::where('id', $giayTo['id'])
                            ->where('nhan_vien_id', $nhanVien->id)
                            ->update($fileData);
                    } else {
                        \App\Models\GiayToTuyThan::create($fileData);
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
            if (!empty($salaryData) && count($salaryData) > 1) {
                ThongTinLuong::updateOrCreate(
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
        } catch (\Throwable $e) {
            \Log::error('Lỗi khi cập nhật nhân viên: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Có lỗi xảy ra: ' . $e->getMessage()])
                ->withInput();
        }
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
            'loai_tep' => 'nullable|string|in:giay_to_tuy_than,chung_chi,hop_dong,khac,bang_cap,chung_nhan',
            'ten_tep' => 'nullable|string|max:255',
            'tep_tin' => 'nullable|file|max:10240' // 10MB max
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
            'nghi_viec' => 'Đã nghỉ việc'
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
        $nhanVien->load([
            'thongTinLienHe', 
            'thongTinGiaDinh', 
            'tepTin',
            'thongTinLuong',
            'baoHiem',
            'quaTrinhCongTac.chucVu',
            'quaTrinhCongTac.phongBan',
            'thongTinGiayTo.tepTin',
            'phongBan',
            'chucVu',
            'hopDongLaoDong',
            'taiKhoan'
        ]);

        foreach ($nhanVien->quaTrinhCongTac as $qtc) {
            if (is_string($qtc->mo_ta)) {
                $qtc->mo_ta = json_decode($qtc->mo_ta, true);
            }
        }
        
        $phongBans = PhongBan::with('phongBanCon')
            ->whereNull('phong_ban_cha_id')
            ->get();
        $chucVus = ChucVu::all();

        // Always provide all managers, but optionally filter if needed
        $managers = NhanVien::where('id', '!=', $nhanVien->id)
            ->whereNotNull('chuc_vu_id')
            ->with('chucVu')
            ->get();

        return view('nhan-vien.edit', [
            'nhanVien' => $nhanVien,
            'phongBans' => $phongBans,
            'chucVus' => $chucVus,
            'managers' => $managers
        ]);
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

    public function export(Request $request)
    {
        $query = NhanVien::query()->with(['phongBan', 'chucVu']);

        // Lọc search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereRaw("CONCAT(ho, ' ', ten) LIKE ?", ["%$search%"])
                    ->orWhere('ma_nhan_vien', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('so_dien_thoai', 'like', "%$search%");
            });
        }

        // Lọc phòng ban (bao gồm cả phòng ban con)
        if ($request->filled('phong_ban_id')) {
            $phongBanId = $request->phong_ban_id;
            // Lấy tất cả phòng ban con
            $phongBanCon = \App\Models\PhongBan::where('phong_ban_cha_id', $phongBanId)->pluck('id')->toArray();
            $allPhongBanIds = array_merge([$phongBanId], $phongBanCon);
            
            $query->whereIn('phong_ban_id', $allPhongBanIds);
        }

        // Lọc chức vụ
        if ($request->filled('chuc_vu_id')) {
            $query->where('chuc_vu_id', $request->chuc_vu_id);
        }

        // Lọc trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        $export = new \App\Exports\NhanVienExport($query);
        return \Maatwebsite\Excel\Facades\Excel::download($export, 'danh-sach-nhan-vien-' . date('Y-m-d') . '.xlsx');
    }
}