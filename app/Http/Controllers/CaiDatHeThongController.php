<?php

namespace App\Http\Controllers;

use App\Models\CaiDatHeThong;
use Illuminate\Http\Request;

class CaiDatHeThongController extends Controller
{
    // Hiển thị giao diện cài đặt hệ thống
    public function index(Request $request)
    {
        $danhMucs = CaiDatHeThong::select('ten_cai_dat')->distinct()->pluck('ten_cai_dat');
        $activeDanhMuc = $request->get('danh_muc', $danhMucs->first());
        $items = CaiDatHeThong::where('ten_cai_dat', $activeDanhMuc)->get();
        return view('cai-dat.he-thong.index', compact('danhMucs', 'activeDanhMuc', 'items'));
    }

    // Thêm mới item
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'ten_cai_dat' => 'required|string|max:100',
                'gia_tri_cai_dat' => 'required|string|max:255',
                'mo_ta' => 'nullable|string',
            ]);
            CaiDatHeThong::create($validated);
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Thêm danh mục thành công!']);
            }
            return back()->with('success', 'Thêm item thành công!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi dữ liệu',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }
    }

    // Sửa item
    public function update(Request $request, CaiDatHeThong $item)
    {
        $validated = $request->validate([
            'ten_cai_dat' => 'required|string|max:100',
            'gia_tri_cai_dat' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
        ]);
        $item->update($validated);
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Cập nhật item thành công!']);
        }
        return back()->with('success', 'Cập nhật item thành công!');
    }

    // Xóa item
    public function destroy(CaiDatHeThong $item)
    {
        $item->delete();
        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Xóa item thành công!']);
        }
        return back()->with('success', 'Xóa item thành công!');
    }
}
