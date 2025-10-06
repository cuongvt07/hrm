<?php

namespace App\Http\Controllers;

use App\Models\CaiDatHeThong;
use App\Models\CaiDatItem;
use Illuminate\Http\Request;

class CaiDatHeThongController extends Controller
{
    // Hiển thị giao diện cài đặt hệ thống
    public function index(Request $request)
    {
        $danhMucs = CaiDatHeThong::select('ten_cai_dat')->distinct()->pluck('ten_cai_dat');
        $activeDanhMuc = $request->get('danh_muc', $danhMucs->last());
        $danhMucModel = CaiDatHeThong::where('ten_cai_dat', $activeDanhMuc)->first();
        $items = [];
        if ($danhMucModel) {
            $items = CaiDatItem::where('danh_muc_id', $danhMucModel->id)->get();
        }
        return view('cai-dat.he-thong.index', compact('danhMucs', 'activeDanhMuc', 'danhMucModel', 'items'));
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

    // Xóa danh mục và tất cả items liên quan
    public function destroy(CaiDatHeThong $item)
    {
        try {
            \DB::transaction(function() use ($item) {
                // Xóa tất cả các items thuộc danh mục
                CaiDatItem::where('danh_muc_id', $item->id)->delete();
                
                // Xóa danh mục
                $item->delete();
            });

            if (request()->ajax()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Xóa danh mục và các items thành công!'
                ]);
            }
            
            return back()->with('success', 'Xóa danh mục và các items thành công!');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi khi xóa danh mục: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Lỗi khi xóa danh mục: ' . $e->getMessage());
        }
    }
}
