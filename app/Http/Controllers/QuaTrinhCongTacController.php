<?php
namespace App\Http\Controllers;

use App\Models\QuaTrinhCongTac;
use App\Models\NhanVien;
use App\Models\ChucVu;
use App\Models\PhongBan;
use Illuminate\Http\Request;

class QuaTrinhCongTacController extends Controller
{
    public function store(Request $request, $nhanvien_id)
    {
        $request->validate([
            'chucvu_id' => 'required|exists:chuc_vu,id',
            'phongban_id' => 'required|exists:phong_ban,id',
            'mo_ta' => 'nullable|string|max:255',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'nullable|date|after_or_equal:ngay_bat_dau',
        ]);
        QuaTrinhCongTac::create([
            'nhanvien_id' => $nhanvien_id,
            'chucvu_id' => $request->chucvu_id,
            'phongban_id' => $request->phongban_id,
            'mo_ta' => $request->mo_ta,
            'ngay_bat_dau' => $request->ngay_bat_dau,
            'ngay_ket_thuc' => $request->ngay_ket_thuc,
        ]);
        return back()->with('success', 'Thêm quá trình công tác thành công');
    }

    public function destroy($id)
    {
        QuaTrinhCongTac::findOrFail($id)->delete();
        return back()->with('success', 'Xóa quá trình công tác thành công');
    }
}
