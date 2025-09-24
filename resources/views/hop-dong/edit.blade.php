@extends('layouts.app')
@section('title', 'Sửa hợp đồng lao động')
@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Sửa hợp đồng lao động</h5>
    </div>
    <form action="{{ route('hop-dong.update', $hopDong->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nhan_vien_id" class="form-label">Nhân viên</label>
                    <input type="text" class="form-control bg-light" value="{{ $hopDong->nhanVien->ho ?? '' }} {{ $hopDong->nhanVien->ten ?? '' }} - {{ $hopDong->nhanVien->ma_nhanvien ?? '' }}" readonly>
                    <input type="hidden" name="nhan_vien_id" value="{{ $hopDong->nhan_vien_id }}">
                </div>
                <div class="col-md-6">
                    <label for="so_hop_dong" class="form-label">Số hợp đồng</label>
                    <input type="text" name="so_hop_dong" id="so_hop_dong" class="form-control bg-light" value="{{ $hopDong->so_hop_dong }}" required readonly>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="loai_hop_dong" class="form-label">Loại hợp đồng</label>
                    <select name="loai_hop_dong" id="loai_hop_dong" class="form-select">
                        <option value="">-- Chọn loại hợp đồng --</option>
                        <option value="Hợp đồng lao động có thời hạn" {{ $hopDong->loai_hop_dong == 'Hợp đồng lao động có thời hạn' ? 'selected' : '' }}>Hợp đồng lao động có thời hạn</option>
                        <option value="Hợp đồng lao động không thời hạn" {{ $hopDong->loai_hop_dong == 'Hợp đồng lao động không thời hạn' ? 'selected' : '' }}>Hợp đồng lao động không thời hạn</option>
                        <option value="Hợp đồng lao động theo mùa vụ" {{ $hopDong->loai_hop_dong == 'Hợp đồng lao động theo mùa vụ' ? 'selected' : '' }}>Hợp đồng lao động theo mùa vụ</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="ngay_bat_dau" class="form-label">Ngày bắt đầu</label>
                    <input type="date" name="ngay_bat_dau" id="ngay_bat_dau" class="form-control" value="{{ $hopDong->ngay_bat_dau ? $hopDong->ngay_bat_dau->format('Y-m-d') : '' }}">
                </div>
                <div class="col-md-3">
                    <label for="ngay_ket_thuc" class="form-label">Ngày kết thúc</label>
                    <input type="date" name="ngay_ket_thuc" id="ngay_ket_thuc" class="form-control" value="{{ $hopDong->ngay_ket_thuc ? $hopDong->ngay_ket_thuc->format('Y-m-d') : '' }}">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="ngay_ky" class="form-label">Ngày ký</label>
                    <input type="date" name="ngay_ky" id="ngay_ky" class="form-control" value="{{ $hopDong->ngay_ky ? $hopDong->ngay_ky->format('Y-m-d') : '' }}">
                </div>
                <div class="col-md-3">
                    <label for="luong_co_ban" class="form-label">Lương cơ bản</label>
                    <input type="text" name="luong_co_ban" id="luong_co_ban" class="form-control money-input" value="{{ number_format($hopDong->luong_co_ban, 0, ',', '.') }}" autocomplete="off">
                </div>
                <div class="col-md-3">
                    <label for="luong_bao_hiem" class="form-label">Lương đóng bảo hiểm</label>
                    <input type="text" name="luong_bao_hiem" id="luong_bao_hiem" class="form-control money-input" value="{{ number_format($hopDong->luong_bao_hiem, 0, ',', '.') }}" autocomplete="off">
                </div>
                <div class="col-md-3">
                    <label for="trang_thai" class="form-label">Trạng thái</label>
                    <select name="trang_thai" id="trang_thai" class="form-select" required>
                        <option value="hoat_dong" {{ $hopDong->trang_thai == 'hoat_dong' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="het_han" {{ $hopDong->trang_thai == 'het_han' ? 'selected' : '' }}>Hết hạn</option>
                        <option value="cham_dut" {{ $hopDong->trang_thai == 'cham_dut' ? 'selected' : '' }}>Chấm dứt</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label for="vi_tri_cong_viec" class="form-label">Vị trí công việc</label>
                <input type="text" name="vi_tri_cong_viec" id="vi_tri_cong_viec" class="form-control" value="{{ $hopDong->vi_tri_cong_viec }}">
            </div>
            <div class="mb-3">
                <label for="don_vi_ky_hd" class="form-label">Đơn vị ký hợp đồng</label>
                <input type="text" name="don_vi_ky_hd" id="don_vi_ky_hd" class="form-control bg-light" value="{{ $hopDong->don_vi_ky_hd }}" readonly>
            </div>
            <div class="mb-3">
                <label for="trang_thai_ky" class="form-label">Trạng thái ký</label>
                <select name="trang_thai_ky" id="trang_thai_ky" class="form-select">
                    <option value="">-- Chọn trạng thái --</option>
                    <option value="duyet" {{ $hopDong->trang_thai_ky == 'duyet' ? 'selected' : '' }}>Duyệt</option>
                    <option value="tai_ki" {{ $hopDong->trang_thai_ky == 'tai_ki' ? 'selected' : '' }}>Tái kí</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="thoi_han" class="form-label">Thời hạn hợp đồng (tháng)</label>
                <select name="thoi_han" id="thoi_han" class="form-select">
                    <option value="">-- Chọn thời hạn --</option>
                    @for($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ $hopDong->thoi_han == $i ? 'selected' : '' }}>{{ $i }} năm</option>
                    @endfor
                </select>
            </div>
            <div class="mb-3">
                <label for="ghi_chu" class="form-label">Ghi chú</label>
                <textarea name="ghi_chu" id="ghi_chu" class="form-control" rows="2">{{ $hopDong->ghi_chu }}</textarea>
            </div>
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('hop-dong.index') }}" class="btn btn-secondary">Quay lại</a>
            <button type="submit" class="btn btn-primary">Cập nhật hợp đồng</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.money-input').forEach(function(input) {
            input.addEventListener('input', function(e) {
                let value = input.value.replace(/\D/g, '');
                if (value) {
                    input.value = Number(value).toLocaleString('vi-VN');
                } else {
                    input.value = '';
                }
            });
        });
        // Khi submit form, loại bỏ dấu chấm
        document.querySelectorAll('form').forEach(function(form) {
            form.addEventListener('submit', function() {
                form.querySelectorAll('.money-input').forEach(function(input) {
                    input.value = input.value.replace(/\./g, '').replace(/,/g, '');
                });
            });
        });
    });
</script>
@endpush
