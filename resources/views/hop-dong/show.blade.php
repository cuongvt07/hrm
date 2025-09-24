@extends('layouts.app')
@section('title', 'Chi tiết hợp đồng lao động')
@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Chi tiết hợp đồng lao động</h5>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Nhân viên</label>
                <input type="text" class="form-control bg-light" value="{{ $hopDong->nhanVien->ho ?? '' }} {{ $hopDong->nhanVien->ten ?? '' }} - {{ $hopDong->nhanVien->ma_nhanvien ?? '' }}" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label">Số hợp đồng</label>
                <input type="text" class="form-control bg-light" value="{{ $hopDong->so_hop_dong }}" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Loại hợp đồng</label>
                <input type="text" class="form-control bg-light" value="{{ $hopDong->loai_hop_dong }}" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label">Ngày bắt đầu</label>
                <input type="text" class="form-control bg-light" value="{{ $hopDong->ngay_bat_dau ? $hopDong->ngay_bat_dau->format('Y-m-d') : '' }}" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label">Ngày kết thúc</label>
                <input type="text" class="form-control bg-light" value="{{ $hopDong->ngay_ket_thuc ? $hopDong->ngay_ket_thuc->format('Y-m-d') : '' }}" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3">
                <label class="form-label">Ngày ký</label>
                <input type="text" class="form-control bg-light" value="{{ $hopDong->ngay_ky ? $hopDong->ngay_ky->format('Y-m-d') : '' }}" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label">Lương cơ bản</label>
                <input type="text" class="form-control bg-light" value="{{ number_format($hopDong->luong_co_ban, 0, ',', '.') }}" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label">Lương đóng bảo hiểm</label>
                <input type="text" class="form-control bg-light" value="{{ number_format($hopDong->luong_bao_hiem, 0, ',', '.') }}" readonly>
            </div>
            <div class="col-md-3">
                <label class="form-label">Trạng thái</label>
                <input type="text" class="form-control bg-light" value="{{ $hopDong->trang_thai }}" readonly>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Vị trí công việc</label>
            <input type="text" class="form-control bg-light" value="{{ $hopDong->vi_tri_cong_viec }}" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Đơn vị ký hợp đồng</label>
            <input type="text" class="form-control bg-light" value="{{ $hopDong->don_vi_ky_hd }}" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Trạng thái ký</label>
            <input type="text" class="form-control bg-light" value="{{ $hopDong->trang_thai_ky }}" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Thời hạn hợp đồng</label>
            <input type="text" class="form-control bg-light" value="{{ $hopDong->thoi_han }}" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Ghi chú</label>
            <textarea class="form-control bg-light" rows="2" readonly>{{ $hopDong->ghi_chu }}</textarea>
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('hop-dong.index') }}" class="btn btn-secondary">Quay lại</a>
            <a href="{{ route('hop-dong.edit', $hopDong->id) }}" class="btn btn-warning ms-2"><i class="fas fa-edit me-1"></i>Sửa hợp đồng</a>
        </div>
    </div>
</div>
@endsection
