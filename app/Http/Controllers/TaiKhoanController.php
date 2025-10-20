<?php

namespace App\Http\Controllers;

use App\Models\TaiKhoan;
use App\Models\NhanVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TaiKhoanController extends Controller
{
    /**
     * Cập nhật trạng thái tài khoản qua ajax
     */
    public function updateStatus(Request $request, $id)
    {
        $taiKhoan = TaiKhoan::findOrFail($id);
        $trangThai = $request->input('trang_thai');
        if (!in_array($trangThai, ['hoat_dong', 'khong_hoat_dong'])) {
            return response()->json(['success' => false, 'message' => 'Trạng thái không hợp lệ!']);
        }
        $taiKhoan->trang_thai = $trangThai;
        $taiKhoan->save();
        return response()->json(['success' => true]);
    }
    public function index(Request $request)
    {
        $query = TaiKhoan::with(['nhanVien.phongBan', 'nhanVien.chucVu'])
            ->select('tai_khoan.*')
            ->join('nhanvien', 'tai_khoan.nhan_vien_id', '=', 'nhanvien.id');

        // Filter theo vai_tro
        if ($request->filled('vai_tro')) {
            $query->where('tai_khoan.vai_tro', $request->vai_tro);
        }

        // Filter theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('tai_khoan.trang_thai', $request->trang_thai);
        }

        // Filter theo từ khóa (tên đăng nhập hoặc email hoặc tên nhân viên)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ten_dang_nhap', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('nhanVien', function($q) use ($search) {
                      $q->whereRaw("CONCAT(ho, ' ', ten) LIKE ?", ["%{$search}%"]);
                  });
            });
        }

        $taiKhoans = $query->paginate(10)->withQueryString();

        return view('tai-khoan.index', compact('taiKhoans'));
    }

    public function create()
    {
        // Lấy danh sách nhân viên chưa có tài khoản, kèm phòng ban và chức vụ
        $nhanViens = NhanVien::with(['phongBan', 'chucVu'])
            ->whereDoesntHave('taiKhoan')->get();
        return view('tai-khoan.create', compact('nhanViens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nhan_vien_id' => 'required|exists:nhanvien,id|unique:tai_khoan',
            'ten_dang_nhap' => 'required|unique:tai_khoan|min:4',
            'mat_khau' => 'required|min:6',
            'email' => 'required|email|unique:tai_khoan',
            'vai_tro' => 'required|in:quan_tri,nhan_su,quan_ly,nhan_vien',
            'trang_thai' => 'required|in:hoat_dong,khong_hoat_dong',
        ]);

        TaiKhoan::create([
            'nhan_vien_id' => $request->nhan_vien_id,
            'ten_dang_nhap' => $request->ten_dang_nhap,
            'mat_khau' => Hash::make($request->mat_khau),
            'email' => $request->email,
            'vai_tro' => $request->vai_tro,
            'trang_thai' => $request->trang_thai,
        ]);

        return redirect()->route('tai-khoan.index')
            ->with('success', 'Tạo tài khoản thành công.');
    }

    public function edit(TaiKhoan $taiKhoan)
    {
        return view('tai-khoan.edit', compact('taiKhoan'));
    }

    public function update(Request $request, TaiKhoan $taiKhoan)
    {
        $request->validate([
            'email' => 'required|email|unique:tai_khoan,email,' . $taiKhoan->id,
            'vai_tro' => 'required|in:quan_tri,nhan_su,quan_ly,nhan_vien',
            'trang_thai' => 'required|in:hoat_dong,khong_hoat_dong',
            'mat_khau' => 'nullable|min:6',
            'phong_ban_id' => 'nullable|exists:phong_ban,id',
            'chuc_vu_id' => 'nullable|exists:chuc_vu,id',
        ]);

        DB::transaction(function() use ($request, $taiKhoan) {
            // Cập nhật thông tin tài khoản
            $data = [
                'email' => $request->email,
                'vai_tro' => $request->vai_tro,
                'trang_thai' => $request->trang_thai,
            ];

            // Chỉ cập nhật mật khẩu nếu có nhập mới
            if ($request->filled('mat_khau')) {
                $data['mat_khau'] = Hash::make($request->mat_khau);
            }

            $taiKhoan->update($data);

            // Cập nhật thông tin nhân viên (nếu có)
            if ($request->filled('phong_ban_id') || $request->filled('chuc_vu_id')) {
                $nhanVienData = [];
                if ($request->filled('phong_ban_id')) {
                    $nhanVienData['phong_ban_id'] = $request->phong_ban_id;
                }
                if ($request->filled('chuc_vu_id')) {
                    $nhanVienData['chuc_vu_id'] = $request->chuc_vu_id;
                }
                if (!empty($nhanVienData)) {
                    $taiKhoan->nhanVien->update($nhanVienData);
                }
            }
        });

        return redirect()->route('tai-khoan.index')
            ->with('success', 'Cập nhật tài khoản thành công.');
    }
}