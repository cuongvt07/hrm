<?php
namespace App\Http\Controllers;

use App\Models\ChucVu;
use Illuminate\Http\Request;

class ChucVuController extends Controller
{
    public function index()
    {
        $chucVus = ChucVu::all();
        return view('chuc-vu.index', compact('chucVus'));
    }

    public function create()
    {
        return view('chuc-vu.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten_chuc_vu' => 'required|string|max:100',
        ]);
        ChucVu::create($request->only('ten_chuc_vu'));
        return redirect()->route('chuc-vu.index')->with('success', 'Thêm chức vụ thành công!');
    }

    public function edit(ChucVu $chucVu)
    {
        return view('chuc-vu.edit', compact('chucVu'));
    }

    public function update(Request $request, ChucVu $chucVu)
    {
        $request->validate([
            'ten_chuc_vu' => 'required|string|max:100',
        ]);
        $chucVu->update($request->only('ten_chuc_vu'));
        return redirect()->route('chuc-vu.index')->with('success', 'Cập nhật chức vụ thành công!');
    }

    public function destroy(ChucVu $chucVu)
    {
        $chucVu->delete();
        return redirect()->route('chuc-vu.index')->with('success', 'Xóa chức vụ thành công!');
    }
}
