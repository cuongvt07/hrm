<?php
namespace App\Http\Controllers;

use App\Models\GiayToTuyThan;
use App\Models\NhanVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GiayToTuyThanController extends Controller
{
    // Thêm giấy tờ tùy thân
    public function store(Request $request, NhanVien $nhanVien)
    {
        $validated = $request->validate([
            'loai_giay_to' => 'required|string|max:100',
            'so_giay_to' => 'required|string|max:50',
            'ngay_cap' => 'nullable|date',
            'noi_cap' => 'nullable|string|max:100',
            'ngay_het_han' => 'nullable|date',
            'ghi_chu' => 'nullable|string|max:255'
        ]);
        $validated['nhan_vien_id'] = $nhanVien->id;
        $giayTo = GiayToTuyThan::create($validated);
        return response()->json([
            'success' => true,
            'message' => 'Thêm giấy tờ thành công!',
            'giayTo' => $giayTo
        ]);
    }

    // Sửa giấy tờ tùy thân
    public function update(Request $request, NhanVien $nhanVien, GiayToTuyThan $giayTo)
    {
        if ($giayTo->nhan_vien_id !== $nhanVien->id) {
            return response()->json([
                'success' => false,
                'message' => 'Không có quyền sửa giấy tờ này!'
            ], 403);
        }
        $validated = $request->validate([
            'loai_giay_to' => 'required|string|max:100',
            'so_giay_to' => 'required|string|max:50',
            'ngay_cap' => 'nullable|date',
            'noi_cap' => 'nullable|string|max:100',
            'ngay_het_han' => 'nullable|date',
            'ghi_chu' => 'nullable|string|max:255'
        ]);
        $giayTo->update($validated);
        return response()->json([
            'success' => true,
            'message' => 'Cập nhật giấy tờ thành công!',
            'giayTo' => $giayTo
        ]);
    }

    // Xóa giấy tờ tùy thân
    public function destroy(Request $request, NhanVien $nhanVien, GiayToTuyThan $giayTo)
    {
        if ($giayTo->nhan_vien_id !== $nhanVien->id) {
            return response()->json([
                'success' => false,
                'message' => 'Không có quyền xóa giấy tờ này!'
            ], 403);
        }
        $giayTo->delete();
        return response()->json([
            'success' => true,
            'message' => 'Xóa giấy tờ thành công!'
        ]);
    }
}
