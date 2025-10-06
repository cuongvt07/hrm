@extends('layouts.app')
@section('title', 'Gia hạn hợp đồng lao động')
@section('content')
    <div class="card">
        <div class="card-header pb-0">
            <div class="card-header">
                <h5 class="mb-0">Gia hạn hợp đồng lao động</h5>
            </div>
            <ul class="nav nav-tabs card-header-tabs" id="hopDongTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-chitiet" data-bs-toggle="tab" data-bs-target="#chitiet"
                        type="button" role="tab" aria-controls="chitiet" aria-selected="true">Chi tiết hợp đồng</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-phucap" data-bs-toggle="tab" data-bs-target="#phucap" type="button"
                        role="tab" aria-controls="phucap" aria-selected="false">Phụ cấp & phúc lợi</button>
                </li>
            </ul>
        </div>
        <form action="{{ route('hop-dong.giahan.store') }}" method="POST" id="giaHanForm">
            @csrf
            <input type="hidden" name="hopdong_cu_id" value="{{ $hopDongCu->id }}">
            <div class="card-body">
                <div class="tab-content" id="hopDongTabContent">
                    <div class="tab-pane fade show active" id="chitiet" role="tabpanel" aria-labelledby="tab-chitiet">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nhân viên</label>
                                <input type="text" class="form-control" value="{{ $hopDongCu->nhanVien->ho_ten }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label for="so_hop_dong" class="form-label">Số hợp đồng mới</label>
                                <input type="text" name="so_hop_dong" id="so_hop_dong" class="form-control bg-light"
                                    value="{{ old('so_hop_dong', $hopDongCu->so_hop_dong) }}" readonly required>
                                <small class="text-muted">Mã HĐ sẽ được thêm mới tự động</small>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="loai_hop_dong" class="form-label">Loại hợp đồng</label>
                                <select name="loai_hop_dong" id="loai_hop_dong" class="form-select" onchange="handleLoaiHopDongChange()">
                                    <option value="">-- Chọn loại hợp đồng --</option>
                                    <option value="thu_viec" {{ old('loai_hop_dong', $hopDongCu->loai_hop_dong) == 'thu_viec' ? 'selected' : '' }}>Thử việc</option>
                                    <option value="xac_dinh_thoi_han" {{ old('loai_hop_dong', $hopDongCu->loai_hop_dong) == 'xac_dinh_thoi_han' ? 'selected' : '' }}>Hợp đồng xác
                                        định thời hạn</option>
                                    <option value="khong_xac_dinh_thoi_han" {{ old('loai_hop_dong', $hopDongCu->loai_hop_dong) == 'khong_xac_dinh_thoi_han' ? 'selected' : '' }}>Hợp đồng
                                        không xác định thời hạn</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="ngay_bat_dau" class="form-label">Ngày có hiệu lực</label>
                                <input type="date" name="ngay_bat_dau" id="ngay_bat_dau" class="form-control"
                                    value="{{ old('ngay_bat_dau') }}" required>
                            </div>
                            <div class="col-md-3" id="ngay_ket_thuc_group">
                                <label for="ngay_ket_thuc" class="form-label">Ngày hết hạn</label>
                                <input type="date" name="ngay_ket_thuc" id="ngay_ket_thuc" class="form-control"
                                    value="{{ old('ngay_ket_thuc') }}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="ngay_ky" class="form-label">Ngày ký</label>
                                <input type="date" name="ngay_ky" id="ngay_ky" class="form-control"
                                    value="{{ old('ngay_ky') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="luong_co_ban" class="form-label">Lương cơ bản</label>
                                <input type="number" step="0.01" name="luong_co_ban" id="luong_co_ban" class="form-control"
                                    value="{{ old('luong_co_ban', $hopDongCu->luong_co_ban) }}">
                            </div>
                            <div class="col-md-3">
                                <label for="luong_bao_hiem" class="form-label">Lương đóng bảo hiểm</label>
                                <input type="number" step="0.01" name="luong_bao_hiem" id="luong_bao_hiem"
                                    class="form-control" value="{{ old('luong_bao_hiem', $hopDongCu->luong_bao_hiem) }}">
                            </div>
                            <div class="col-md-3">
                                <label for="trang_thai" class="form-label">Trạng thái hợp đồng</label>
                                <select name="trang_thai" id="trang_thai" class="form-select" required>
                                    <option value="hieu_luc" {{ old('trang_thai', $hopDongCu->trang_thai) == 'hieu_luc' ? 'selected' : '' }}>Hiệu lực</option>
                                    <option value="het_hieu_luc" {{ old('trang_thai', $hopDongCu->trang_thai) == 'het_hieu_luc' ? 'selected' : '' }}>Hết hiệu lực</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="vi_tri_cong_viec" class="form-label">Vị trí công việc</label>
                                <input type="text" name="vi_tri_cong_viec" id="vi_tri_cong_viec" class="form-control"
                                    value="{{ old('vi_tri_cong_viec', $hopDongCu->vi_tri_cong_viec) }}">
                            </div>
                            <div class="col-md-3">
                                <label for="don_vi_ky_hd" class="form-label">Đơn vị ký hợp đồng</label>
                                <input type="text" name="don_vi_ky_hd" id="don_vi_ky_hd" class="form-control"
                                    value="{{ old('don_vi_ky_hd', $hopDongCu->don_vi_ky_hd) }}">
                            </div>
                            <div class="col-md-3">
                                <label for="trang_thai_ky" class="form-label">Trạng thái ký</label>
                                <select name="trang_thai_ky" id="trang_thai_ky" class="form-select">
                                    <option value="">-- Chọn trạng thái --</option>
                                    <option value="duyet" {{ old('trang_thai_ky', $hopDongCu->trang_thai_ky) == 'duyet' ? 'selected' : '' }}>Đã ký</option>
                                    <option value="tai_ki" {{ old('trang_thai_ky', $hopDongCu->trang_thai_ky) == 'tai_ki' ? 'selected' : '' }}>Gia hạn</option>
                                </select>
                            </div>
                            <div class="col-md-3" id="thoi_han_group">
                                <label for="thoi_han" class="form-label">Thời hạn hợp đồng (năm)</label>
                                <select name="thoi_han" id="thoi_han" class="form-select">
                                    <option value="">-- Chọn thời hạn --</option>
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}" {{ old('thoi_han', $hopDongCu->thoi_han) == $i ? 'selected' : '' }}>{{ $i }} năm</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="ghi_chu" class="form-label">Ghi chú</label>
                            <textarea name="ghi_chu" id="ghi_chu" class="form-control"
                                rows="2">{{ old('ghi_chu', $hopDongCu->ghi_chu) }}</textarea>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="phucap" role="tabpanel" aria-labelledby="tab-phucap">
                        <h5 class="mb-3">Chọn phụ cấp áp dụng cho hợp đồng</h5>
                        <div class="mb-3 d-none">
                            @php
                                $phuCapIdsOld = old('phu_cap_ids', $hopDong->phu_cap_ids ?? '');
                                if (is_array($phuCapIdsOld)) {
                                    $phuCapIdsArr = $phuCapIdsOld;
                                    $phuCapIdsOld = json_encode($phuCapIdsOld);
                                } elseif (is_string($phuCapIdsOld) && $phuCapIdsOld !== '') {
                                    $phuCapIdsArr = json_decode($phuCapIdsOld, true) ?? [];
                                } else {
                                    $phuCapIdsArr = [];
                                }
                            @endphp
                            <input type="hidden" name="phu_cap_ids" id="phu_cap_ids" value="{{ $phuCapIdsOld }}">
                        </div>
                        @if(isset($phuCapItems) && $phuCapItems->count())
                            @php
                                $selectedPhuCap = collect($phuCapIdsArr);
                            @endphp
                            <div class="row">
                                @foreach($phuCapItems as $item)
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input phu-cap-checkbox" type="checkbox" value="{{ $item->id }}"
                                                id="phuCap{{ $item->id }}" @if($selectedPhuCap->contains($item->id)) checked @endif>
                                            <label class="form-check-label" for="phuCap{{ $item->id }}">
                                                {{ $item->ten_item }}
                                                @if($item->mo_ta)
                                                    <span class="text-muted small">({{ $item->mo_ta }})</span>
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-muted">Chưa có phụ cấp nào được cấu hình.</div>
                        @endif
                    </div>
                </div>
            </div>
    </div>
    <div class="card-footer text-end">
        <a href="{{ route('hop-dong.saphethan') }}" class="btn btn-secondary">Quay lại</a>
        <button type="submit" class="btn btn-primary">Lưu hợp đồng mới</button>
    </div>
    </form>
    </div>
@endsection

    <script>
        // Khi submit form, lưu các phụ cấp đã chọn vào input hidden
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function () {
                    const checked = Array.from(document.querySelectorAll('.phu-cap-checkbox:checked')).map(cb => cb.value);
                    document.getElementById('phu_cap_ids').value = JSON.stringify(checked);
                });
            }
        });
    </script>
    <script>
        // Khi submit form, lưu các phúc lợi đã chọn vào input hidden
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function () {
                    const checked = Array.from(document.querySelectorAll('.phuc-loi-checkbox:checked')).map(cb => cb.value);
                    document.getElementById('phuc_loi_ids').value = JSON.stringify(checked);
                });
            }
        });
    </script>
    <script>
    function handleLoaiHopDongChange() {
        const loaiHopDong = document.getElementById('loai_hop_dong').value;
        const ngayKetThucGroup = document.getElementById('ngay_ket_thuc_group');
        const thoiHanGroup = document.getElementById('thoi_han_group');
        const ngayKetThucInput = document.getElementById('ngay_ket_thuc');
        const thoiHanInput = document.getElementById('thoi_han');

        if (loaiHopDong === 'khong_xac_dinh_thoi_han') {
            // Ẩn nhóm ngày kết thúc & thời hạn
            ngayKetThucGroup.style.display = 'none';
            thoiHanGroup.style.display = 'none';

            // Xoá giá trị và bỏ required
            ngayKetThucInput.value = '';
            thoiHanInput.value = '';
            ngayKetThucInput.removeAttribute('required');
            thoiHanInput.removeAttribute('required');

            // Disable để người dùng không nhập được
            ngayKetThucInput.setAttribute('disabled', 'disabled');
            thoiHanInput.setAttribute('disabled', 'disabled');
        } else {
            // Hiển thị lại khi chọn loại khác
            ngayKetThucGroup.style.display = 'block';
            thoiHanGroup.style.display = 'block';
            ngayKetThucInput.removeAttribute('disabled');
            thoiHanInput.removeAttribute('disabled');
            ngayKetThucInput.setAttribute('required', 'required');
            thoiHanInput.setAttribute('required', 'required');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        handleLoaiHopDongChange();
        document.getElementById('loai_hop_dong').addEventListener('change', handleLoaiHopDongChange);
    });
</script>
