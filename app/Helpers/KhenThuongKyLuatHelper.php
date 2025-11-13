<?php
// Hiển thị danh sách khen thưởng/kỷ luật của nhân viên hoặc phòng ban chứa nhân viên
// Sử dụng trong tab "Khen thưởng & Kỷ luật" của show nhân viên

use App\Models\KhenThuongKyLuat;
use App\Models\KhenThuongKyLuatDoiTuong;
use App\Models\NhanVien;
use App\Models\PhongBan;

/**
 * Lấy danh sách khen thưởng/kỷ luật cho nhân viên (cá nhân + tập thể)
 * @param NhanVien $nhanVien
 * @return array [khenThuong, kyLuat]
 */
function getKhenThuongKyLuatForNhanVien(NhanVien $nhanVien) {
    // 1. Khen thưởng/kỷ luật cá nhân
    $ktklCaNhan = KhenThuongKyLuatDoiTuong::with('khenThuongKyLuat')
        ->where('loai_doi_tuong', 'nhan_vien')
        ->where('doi_tuong_id', $nhanVien->id)
        ->get();

    // 2. Khen thưởng/kỷ luật phòng ban (tập thể)
    $phongBanIds = [];
    if ($nhanVien->phong_ban_id) {
        $phongBanIds[] = $nhanVien->phong_ban_id;
        // Nếu có phòng ban cha, lấy luôn id cha (nếu muốn)
        $phongBan = $nhanVien->phongBan;
        if ($phongBan && $phongBan->phong_ban_cha_id) {
            $phongBanIds[] = $phongBan->phong_ban_cha_id;
        }
    }
    $ktklTapThe = KhenThuongKyLuatDoiTuong::with('khenThuongKyLuat', 'phongBan')
        ->where('loai_doi_tuong', 'phong_ban')
        ->whereIn('doi_tuong_id', $phongBanIds)
        ->get();

    // Gộp lại, phân loại
    $khenThuong = collect();
    $kyLuat = collect();
    foreach ($ktklCaNhan->merge($ktklTapThe) as $item) {
        // Kiểm tra ngày vào làm của nhân viên
        // Nếu nhân viên vào làm sau ngày quyết định thì không ghi nhận
        $ngayVaoLam = $nhanVien->ngay_vao_lam;
        $ngayQuyetDinh = $item->khenThuongKyLuat->ngay_quyet_dinh;

        if ($ngayVaoLam && $ngayQuyetDinh && $ngayVaoLam > $ngayQuyetDinh) {
            // Nhân viên vào làm sau ngày quyết định, bỏ qua
            continue;
        }

        if ($item->khenThuongKyLuat && $item->khenThuongKyLuat->loai === 'khen_thuong') {
            $khenThuong->push($item);
        } elseif ($item->khenThuongKyLuat && $item->khenThuongKyLuat->loai === 'ky_luat') {
            $kyLuat->push($item);
        }
    }
    return [
        'khenThuong' => $khenThuong,
        'kyLuat' => $kyLuat
    ];
}
