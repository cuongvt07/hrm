<?php

namespace App\Http\Controllers;

use App\Models\HopDongLaoDong;
use App\Models\NhanVien;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class HopDongController extends Controller
{
    public function giaHanForm($id)
    {
        $hopDongCu = HopDongLaoDong::with('nhanVien')->findOrFail($id);
        // Lấy danh mục phúc lợi
        $phucLoiDanhMuc = \App\Models\CaiDatHeThong::where('gia_tri_cai_dat', 'phuc-loi')->first();
        $phucLoiItems = collect();
        if ($phucLoiDanhMuc) {
            $phucLoiItems = \App\Models\CaiDatItem::where('danh_muc_id', $phucLoiDanhMuc->id)->get();
        }
        // Lấy danh mục phụ cấp
        $phuCapDanhMuc = \App\Models\CaiDatHeThong::where('gia_tri_cai_dat', 'phu-cap')->first();
        $phuCapItems = collect();
        if ($phuCapDanhMuc) {
            $phuCapItems = \App\Models\CaiDatItem::where('danh_muc_id', $phuCapDanhMuc->id)->get();
        }
        return view('hop-dong.giahan', compact('hopDongCu', 'phucLoiItems', 'phuCapItems'));
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
            'phu_cap_ids' => 'nullable|string',
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
        // Nếu loại hợp đồng là xác định thời hạn hoặc không xác định thời hạn
        // và nhân viên đang ở trạng thái thử việc thì chuyển sang nhân viên chính thức
        $loai = $validated['loai_hop_dong'] ?? $request->input('loai_hop_dong');
        if ($loai) {
            $loaiLower = strtolower($loai);
            if (str_contains($loaiLower, 'xac_dinh') || str_contains($loaiLower, 'khong_xac_dinh') || $loai === 'Hợp đồng xác định thời hạn' || $loai === 'Hợp đồng không xác định thời hạn') {
                $nv = NhanVien::find($hopDongMoi->nhan_vien_id);
                if ($nv && ($nv->trang_thai === 'thu_viec' || $nv->trang_thai === 'thuviec' )) {
                    $nv->update(['trang_thai' => 'nhan_vien_chinh_thuc']);
                }
            }
        }
        // Thêm bản ghi vào quá trình công tác: lưu mức lương vào mô tả

        $nv = NhanVien::find($hopDongMoi->nhan_vien_id);
            \App\Models\QuaTrinhCongTac::create([
            'nhanvien_id' => $hopDongMoi->nhan_vien_id,
            'chucvu_id' => ($nv ? $nv->chuc_vu_id : null),
            'phongban_id' => ($nv ? $nv->phong_ban_id : null),
            'mo_ta' => json_encode([
                'vi_tri' => $hopDongMoi->vi_tri_cong_viec ?? '',
                'luong'  => $hopDongMoi->luong_co_ban ?? '',
            ]),
            'ngay_bat_dau' => $hopDongMoi->ngay_bat_dau ?? null,
            'ngay_ket_thuc' => $hopDongMoi->ngay_ket_thuc ?? null,
        ]);

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
        // Áp dụng scope thống nhất: sắp hết hạn = ngay_ket_thuc <= now + 30 ngày
        // Chỉ show những hợp đồng đang ở trạng thái 'hieu_luc'
        $query = $query->sapHetHan(30)->where('trang_thai', 'hieu_luc');

        // Ẩn các hợp đồng đã bị tái ký dựa vào tiền tố so_hop_dong
        $query = $query->whereNotIn('so_hop_dong', $soHopDongGocDaTaiKi);

        $hopDongs = $query->orderBy('ngay_ket_thuc', 'desc')->paginate(20);
        $nhanViens = NhanVien::dangLamViec()->get();

        if ($request->ajax()) {
            $tableHtml = view('hop-dong.partials.table', compact('hopDongs', 'nhanViens'))->render();
            return response()->json(['table' => $tableHtml]);
        }

        return view('hop-dong.saphethan', compact('hopDongs', 'nhanViens'));
    }

    /**
     * Export danh sách hợp đồng sắp hết hạn (CSV)
     */
    public function exportSapHetHan(Request $request)
    {
        $query = HopDongLaoDong::with('nhanVien');

        // Same filters as sapHetHan
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

        if ($request->filled('loai_hop_dong')) {
            $query->where('loai_hop_dong', 'like', "%{$request->loai_hop_dong}%");
        }

        if ($request->filled('ngay_bat_dau')) {
            $query->whereDate('ngay_bat_dau', '>=', $request->ngay_bat_dau);
        }
        if ($request->filled('ngay_ket_thuc')) {
            $query->whereDate('ngay_ket_thuc', '<=', $request->ngay_ket_thuc);
        }

        if ($request->filled('thoi_han')) {
            $query->where('thoi_han', $request->thoi_han);
        }

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // conditions for expiring within a month (same as sapHetHan)
        $soHopDongGocDaTaiKi = HopDongLaoDong::whereRaw("so_hop_dong REGEXP '_.{6}$'")
            ->pluck('so_hop_dong')
            ->map(function($so){
                return preg_replace('/_.{6}$/', '', $so);
            })
            ->unique()
            ->toArray();
    // Áp dụng scope thống nhất: sắp hết hạn = ngay_ket_thuc <= now + 30 ngày
    // Chỉ export những hợp đồng đang ở trạng thái 'hieu_luc'
    $query = $query->sapHetHan(30)->where('trang_thai', 'hieu_luc');
    $query = $query->whereNotIn('so_hop_dong', $soHopDongGocDaTaiKi)->orderBy('ngay_ket_thuc', 'desc');

        $rows = $query->get();

        $filename = 'hop_dong_sap_het_han_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

    $columns = ['Số hợp đồng', 'Họ và tên', 'Mã nhân viên', 'Loại hợp đồng', 'Ngày ký', 'Ngày bắt đầu', 'Ngày kết thúc', 'Trạng thái', 'Thời hạn', 'Lương cơ bản', 'Ghi chú'];

        $callback = function() use ($rows, $columns, $request) {
            $out = fopen('php://output', 'w');
            // BOM for Excel (UTF-8)
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));

            // Title row
            $title = 'DANH SÁCH HỢP ĐỒNG SẮP HẾT HẠN';
            fputcsv($out, [$title]);

            // If filters used, output key => value rows
            $filterKeys = [
                'search' => 'Tìm kiếm',
                'loai_hop_dong' => 'Loại hợp đồng',
                'ngay_bat_dau' => 'Ngày bắt đầu (từ)',
                'ngay_ket_thuc' => 'Ngày kết thúc (đến)',
                'thoi_han' => 'Thời hạn',
                'trang_thai' => 'Trạng thái'
            ];
            $hasFilters = false;
            foreach ($filterKeys as $key => $label) {
                if ($request->filled($key)) {
                    $hasFilters = true;
                    $val = $request->get($key);
                    fputcsv($out, [$label, $val]);
                }
            }

            // Blank line before header
            fputcsv($out, []);

            // Header columns
            fputcsv($out, $columns);

            foreach ($rows as $r) {
                $nhanVien = $r->nhanVien;
                $name = $nhanVien ? trim(($nhanVien->ho ?? '') . ' ' . ($nhanVien->ten ?? '')) : '';
                $so = $r->so_hop_dong;
                $loai = $r->loai_hop_dong;
                $maNV = '';
                if ($nhanVien) {
                    $maNV = $nhanVien->ma_nhanvien ?? ($nhanVien->ma_nhan_vien ?? '');
                }
                // Map trạng thái sang nhãn tiếng Việt
                if (isset($r->trang_thai)) {
                    if ($r->trang_thai === 'hieu_luc') {
                        $r->trang_thai = 'Hiệu lực';
                    } elseif ($r->trang_thai === 'het_hieu_luc') {
                        $r->trang_thai = 'Hết hiệu lực';
                    } else {
                        $r->trang_thai = (string) $r->trang_thai;
                    }
                }
                // Safely format dates via Carbon (handles string/null/Carbon)
                try {
                    $ngayKy = $r->ngay_ky ? \Carbon\Carbon::parse($r->ngay_ky)->format('Y-m-d') : '';
                } catch (\Exception $e) {
                    $ngayKy = (string) $r->ngay_ky;
                }
                try {
                    $ngayBD = $r->ngay_bat_dau ? \Carbon\Carbon::parse($r->ngay_bat_dau)->format('Y-m-d') : '';
                } catch (\Exception $e) {
                    $ngayBD = (string) $r->ngay_bat_dau;
                }
                try {
                    $ngayKT = $r->ngay_ket_thuc ? \Carbon\Carbon::parse($r->ngay_ket_thuc)->format('Y-m-d') : '';
                } catch (\Exception $e) {
                    $ngayKT = (string) $r->ngay_ket_thuc;
                }
                $trangThai = $r->trang_thai;
                $thoiHan = $r->thoi_han;
                // Display unit-aware thoi_han: months for 'Thử việc', years otherwise
                if ($thoiHan) {
                    if (isset($r->loai_hop_dong) && $r->loai_hop_dong === 'Thử việc') {
                        $thoiHanLabel = $thoiHan . ' tháng';
                    } else {
                        $thoiHanLabel = $thoiHan . ' năm';
                    }
                } else {
                    $thoiHanLabel = '';
                }
                $luong = $r->luong_co_ban;
                $ghiChu = $r->ghi_chu;
                fputcsv($out, [$so, $name, $maNV, $loai, $ngayKy, $ngayBD, $ngayKT, $trangThai, $thoiHanLabel, $luong, $ghiChu]);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Terminate a contract: set contract to 'het_hieu_luc', set ngay_ket_thuc to today,
     * and mark the related employee as 'nghi_viec'. Returns JSON.
     */
    public function terminate(Request $request, $id)
    {
        $hopDong = HopDongLaoDong::findOrFail($id);
        DB::beginTransaction();
        try {
            $today = now();
            $hopDong->update([
                'trang_thai' => 'het_hieu_luc',
                'ngay_ket_thuc' => $today,
            ]);

            // Update employee status to 'nghi_viec'
            if ($hopDong->nhan_vien_id) {
                $nv = NhanVien::find($hopDong->nhan_vien_id);
                if ($nv) {
                    $nv->update(['trang_thai' => 'nghi_viec']);
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Chấm dứt hợp đồng thành công.']);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error terminating contract: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Lỗi khi chấm dứt hợp đồng.'], 500);
        }
    }

    /**
     * AJAX helper: check if an employee has existing active contracts
     */
    public function checkEmployeeContracts($id)
    {
        $count = HopDongLaoDong::where('nhan_vien_id', $id)->where('trang_thai', 'hieu_luc')->count();
        $latest = HopDongLaoDong::where('nhan_vien_id', $id)->orderBy('created_at', 'desc')->first();
        return response()->json([
            'count' => $count,
            'latest_so_hop_dong' => $latest ? $latest->so_hop_dong : null,
        ]);
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

            $query->where(function($q) {
                $q->where('trang_thai', 'het_hieu_luc')
                  ->orWhere(function($sub) {
                      $sub->where('trang_thai', 'hieu_luc')
                          ->where(function($w) {
                              $w->whereNull('ngay_ket_thuc')
                                ->orWhereDate('ngay_ket_thuc', '>', now()->addDays(30));
                          });
                  });
            });

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

        // Lấy danh mục phúc lợi
        $phucLoiDanhMuc = \App\Models\CaiDatHeThong::where('gia_tri_cai_dat', 'phuc-loi')->first();
        $phucLoiItems = collect();
        if ($phucLoiDanhMuc) {
            $phucLoiItems = \App\Models\CaiDatItem::where('danh_muc_id', $phucLoiDanhMuc->id)->get();
        }

        return view('hop-dong.show', compact('hopDong', 'phucLoiItems'));
    }

    public function create()
    {
        $nhanViens = NhanVien::dangLamViec()->get();
        // Lấy danh mục phúc lợi
        $phucLoiDanhMuc = \App\Models\CaiDatHeThong::where('gia_tri_cai_dat', 'phuc-loi')->first();
        $phucLoiItems = collect();
        if ($phucLoiDanhMuc) {
            $phucLoiItems = \App\Models\CaiDatItem::where('danh_muc_id', $phucLoiDanhMuc->id)->get();
        }
        // Lấy danh mục phụ cấp
        $phuCapDanhMuc = \App\Models\CaiDatHeThong::where('gia_tri_cai_dat', 'phu-cap')->first();
        $phuCapItems = collect();
        if ($phuCapDanhMuc) {
            $phuCapItems = \App\Models\CaiDatItem::where('danh_muc_id', $phuCapDanhMuc->id)->get();
        }
        return view('hop-dong.create', compact('nhanViens', 'phucLoiItems', 'phuCapItems'));
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
                'phu_cap_ids' => 'nullable|string',
                'trang_thai_ky' => 'nullable|string|max:50',
                'thoi_han' => 'nullable|integer',
            ]);

                // If nhan_vien_id provided, check for existing active contracts and mark them as expired
                if (!empty($validated['nhan_vien_id'])) {
                    $existingActive = HopDongLaoDong::where('nhan_vien_id', $validated['nhan_vien_id'])
                        ->where('trang_thai', 'hieu_luc')
                        ->get();

                    if ($existingActive->count() > 0) {
                        // mark previous active contracts as expired
                        HopDongLaoDong::where('nhan_vien_id', $validated['nhan_vien_id'])
                            ->where('trang_thai', 'hieu_luc')
                            ->update(['trang_thai' => 'het_hieu_luc']);

                        // ensure new so_hop_dong is unique by appending a random suffix if provided
                        if (!empty($validated['so_hop_dong'])) {
                            $validated['so_hop_dong'] .= '_' . strtoupper(Str::random(6));
                        } else {
                            // fallback generate from employee code
                            $nvTmp = NhanVien::find($validated['nhan_vien_id']);
                            $ma = $nvTmp ? ($nvTmp->ma_nhanvien ?? '') : '';
                            $validated['so_hop_dong'] = 'HĐ_' . ($ma ? $ma : strtoupper(Str::random(6))) . '_' . strtoupper(Str::random(6));
                        }
                    }
                }

            // Validate file upload nếu có file
            if ($request->hasFile('tep_tin_hop_dong')) {
                $request->validate([
                    'tep_tin_hop_dong' => 'array',
                    'tep_tin_hop_dong.*' => 'file|mimes:jpg,jpeg,png,gif,bmp,pdf,doc,docx,xls,xlsx|max:10240',
                ]);
            }

            // Chuyển phu_cap_ids từ JSON string sang array nếu có
            if (!empty($validated['phu_cap_ids'])) {
                $validated['phu_cap_ids'] = json_decode($validated['phu_cap_ids'], true) ?? [];
            }
            $hopDong = HopDongLaoDong::create($validated);
            \App\Models\ThongTinLuong::updateOrCreate(
                ['nhan_vien_id' => $hopDong->nhan_vien_id],
                ['luong_co_ban' => $hopDong->luong_co_ban]
            );

            // Nếu loại hợp đồng là xác định thời hạn hoặc không xác định thời hạn
            // và nhân viên đang ở trạng thái thử việc thì chuyển sang nhân viên chính thức
            $loai = $validated['loai_hop_dong'] ?? $request->input('loai_hop_dong');
            if ($loai) {
                $loaiLower = strtolower($loai);
                if (str_contains($loaiLower, 'xac_dinh') || str_contains($loaiLower, 'khong_xac_dinh') || $loai === 'Hợp đồng xác định thời hạn' || $loai === 'Hợp đồng không xác định thời hạn') {
                    $nv = NhanVien::find($hopDong->nhan_vien_id);
                    if ($nv && ($nv->trang_thai === 'thu_viec' || $nv->trang_thai === 'thuviec')) {
                        $nv->update(['trang_thai' => 'nhan_vien_chinh_thuc']);
                    }
                }
            }

            // Xử lý upload file tài liệu hợp đồng
            if ($request->hasFile('tep_tin_hop_dong')) {
                $files = $request->file('tep_tin_hop_dong');
                foreach ($files as $file) {
                    if ($file->isValid()) {
                        $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                        $filePath = $file->storeAs('documents', $fileName, 'public');
                        \App\Models\TepTin::create([
                            'nhan_vien_id' => $hopDong->nhan_vien_id,
                            'hop_dong_id'  => $hopDong->id,
                            'loai_tep'     => 'hop_dong',
                            'ten_tep'      => $file->getClientOriginalName(),
                            'duong_dan_tep' => $filePath,
                            'nguoi_tai_len' => auth()->id(),
                        ]);
                    }
                }
            }
    
            $nv = NhanVien::find($hopDong->nhan_vien_id);
            \App\Models\QuaTrinhCongTac::create([
                'nhanvien_id' => $hopDong->nhan_vien_id,
                'chucvu_id' => ($nv ? $nv->chuc_vu_id : null),
                'phongban_id' => ($nv ? $nv->phong_ban_id : null),
                'mo_ta' => json_encode([
                    'vi_tri' => $hopDong->vi_tri_cong_viec ?? '',
                    'luong'  => $hopDong->luong_co_ban ?? '',
                ]),
                'ngay_bat_dau' => $hopDong->ngay_bat_dau ?? null,
                'ngay_ket_thuc' => $hopDong->ngay_ket_thuc ?? null,
            ]);
    
            return redirect()->route('hop-dong.index')
                ->with('success', 'Thêm hợp đồng thành công!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    public function edit(HopDongLaoDong $hopDong)
    {
        $nhanViens = NhanVien::dangLamViec()->get();
        // Lấy danh mục phúc lợi
        $phucLoiDanhMuc = \App\Models\CaiDatHeThong::where('gia_tri_cai_dat', 'phuc-loi')->first();
        $phucLoiItems = collect();
        if ($phucLoiDanhMuc) {
            $phucLoiItems = \App\Models\CaiDatItem::where('danh_muc_id', $phucLoiDanhMuc->id)->get();
        }
        // Lấy danh mục phụ cấp
        $phuCapDanhMuc = \App\Models\CaiDatHeThong::where('gia_tri_cai_dat', 'phu-cap')->first();
        $phuCapItems = collect();
        if ($phuCapDanhMuc) {
            $phuCapItems = \App\Models\CaiDatItem::where('danh_muc_id', $phuCapDanhMuc->id)->get();
        }
        return view('hop-dong.edit', compact('hopDong', 'nhanViens', 'phucLoiItems', 'phuCapItems'));
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
                'phu_cap_ids' => 'nullable|string',
                'trang_thai_ky' => 'nullable|string|max:50',
                'thoi_han' => 'nullable|integer',
            ]);

            // Validate file upload nếu có file
            if ($request->hasFile('tep_tin_hop_dong')) {
                $request->validate([
                    'tep_tin_hop_dong' => 'array',
                    'tep_tin_hop_dong.*' => 'file|mimes:jpg,jpeg,png,gif,bmp,pdf,doc,docx,xls,xlsx|max:10240',
                ]);
            }

            // Chuyển phu_cap_ids từ JSON string sang array nếu có
            if (!empty($validated['phu_cap_ids'])) {
                $validated['phu_cap_ids'] = json_decode($validated['phu_cap_ids'], true) ?? [];
            }
            $hopDong->update($validated);
            \App\Models\ThongTinLuong::updateOrCreate(
                ['nhan_vien_id' => $hopDong->nhan_vien_id],
                ['luong_co_ban' => $hopDong->luong_co_ban]
            );

                    // Thêm bản ghi vào quá trình công tác: lưu mức lương vào mô tả
                    try {
                        $nv = NhanVien::find($hopDong->nhan_vien_id);
                        \App\Models\QuaTrinhCongTac::create([
                            'nhanvien_id' => $hopDong->nhan_vien_id,
                            'chucvu_id' => ($nv ? $nv->chuc_vu_id : null),
                            'phongban_id' => ($nv ? $nv->phong_ban_id : null),
                            'mo_ta' => json_encode([
                                'vi_tri' => $hopDongMoi->vi_tri_cong_viec ?? '',
                                'luong'  => $hopDongMoi->luong_co_ban ?? '',
                            ]),                            'ngay_bat_dau' => $hopDong->ngay_bat_dau ?? null,
                            'ngay_ket_thuc' => $hopDong->ngay_ket_thuc ?? null,
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('Error creating QuaTrinhCongTac on store: ' . $e->getMessage());
                    }

            // Xử lý upload file tài liệu hợp đồng
            if ($request->hasFile('tep_tin_hop_dong')) {
                $files = $request->file('tep_tin_hop_dong');
                foreach ($files as $file) {
                    if ($file->isValid()) {
                        $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                        $filePath = $file->storeAs('documents', $fileName, 'public');
                        \App\Models\TepTin::create([
                            'nhan_vien_id' => $hopDong->nhan_vien_id,
                            'hop_dong_id'  => $hopDong->id,
                            'loai_tep'     => 'hop_dong',
                            'ten_tep'      => $file->getClientOriginalName(),
                            'duong_dan_tep' => $filePath,
                            'nguoi_tai_len' => auth()->id(),
                        ]);
                    }
                }
            }

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

        /**
     * Cập nhật trạng thái hợp đồng hết hạn (chạy ngầm sau login)
     */
    public function updateExpiredContracts()
    {
        \App\Models\HopDongLaoDong::whereNotNull('ngay_ket_thuc')
            ->whereDate('ngay_ket_thuc', '<', now())
            ->where('trang_thai', 'hieu_luc')
            ->update(['trang_thai' => 'het_hieu_luc']);
        return response()->json(['success' => true]);
    }
}
