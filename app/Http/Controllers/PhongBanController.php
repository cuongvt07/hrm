<?php
namespace App\Http\Controllers;

use App\Models\PhongBan;
use Illuminate\Http\Request;

class PhongBanController extends Controller
{
    public function index()
    {
        $phongBans = PhongBan::with('phongBanCon')->whereNull('phong_ban_cha_id')->get();
        return view('phong-ban.index', compact('phongBans'));
    }

    public function create()
    {
        $phongBans = PhongBan::with('phongBanCon')->whereNull('phong_ban_cha_id')->get();
        return view('phong-ban.create', compact('phongBans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ten_phong_ban' => 'required|string|max:100',
            'phong_ban_cha_id' => 'nullable|exists:phong_ban,id',
        ]);
        PhongBan::create($request->only('ten_phong_ban', 'phong_ban_cha_id'));
        return redirect()->route('phong-ban.index')->with('success', 'Thêm phòng ban thành công!');
    }

    public function edit(PhongBan $phongBan)
    {
        $phongBans = PhongBan::where('id', '!=', $phongBan->id)->get();
        return view('phong-ban.edit', compact('phongBan', 'phongBans'));
    }

    public function update(Request $request, PhongBan $phongBan)
    {
        $request->validate([
            'ten_phong_ban' => 'required|string|max:100',
            'phong_ban_cha_id' => 'nullable|exists:phong_ban,id',
        ]);
        $phongBan->update($request->only('ten_phong_ban', 'phong_ban_cha_id'));
        return redirect()->route('phong-ban.index')->with('success', 'Cập nhật phòng ban thành công!');
    }

    public function destroy(PhongBan $phongBan)
    {
        // Xóa cả phòng ban con
        $this->deleteTree($phongBan);
        return redirect()->route('phong-ban.index')->with('success', 'Xóa phòng ban thành công!');
    }

    private function deleteTree(PhongBan $phongBan)
    {
        foreach ($phongBan->phongBanCon as $child) {
            $this->deleteTree($child);
        }
        $phongBan->delete();
    }
}
