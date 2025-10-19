<?php

namespace App\Http\Controllers;

use App\Models\CaiDatItem;
use App\Models\CaiDatDanhMuc;
use Illuminate\Http\Request;

class CaiDatItemController extends Controller
{
    // Hiển thị danh sách item theo danh mục
    public function index($danhMucId)
    {
        $danhMuc = CaiDatDanhMuc::findOrFail($danhMucId);
        $items = $danhMuc->items()->get();
        return view('cai-dat.item.index', compact('danhMuc', 'items'));
    }

    // Tạo mới item
    public function store(Request $request, $danhMucId)
    {
        $validated = $request->validate([
            'ten_item' => 'required|string|max:100',
            'mo_ta' => 'nullable|string',
        ]);
        $item = CaiDatItem::create([
            'danh_muc_id' => $danhMucId,
            'ten_item' => $validated['ten_item'],
            'mo_ta' => $validated['mo_ta'] ?? null,
        ]);
        return response()->json(['success' => true, 'message' => 'Thêm item thành công!', 'item' => $item]);
    }

    // Sửa item
    public function update(Request $request, CaiDatItem $item)
    {
        $validated = $request->validate([
            'ten_item' => 'required|string|max:100',
            'mo_ta' => 'nullable|string',
        ]);
        $item->update($validated);
        return response()->json(['success' => true, 'message' => 'Cập nhật item thành công!', 'item' => $item]);
    }

    // Xóa item
    public function destroy(CaiDatItem $item)
    {
        $item->delete();
        return back()->with('success', 'Xóa danh mục và các items thành công!');
    }
}
