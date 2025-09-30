@extends('layouts.app')
@section('title', 'Chi tiết hợp đồng lao động')
@section('content')
<div class="card">
    <div class="card-header pb-0">
        <ul class="nav nav-tabs card-header-tabs" id="hopDongTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab-chitiet" data-bs-toggle="tab" data-bs-target="#chitiet" type="button" role="tab" aria-controls="chitiet" aria-selected="true">Chi tiết hợp đồng</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-phucloi" data-bs-toggle="tab" data-bs-target="#phucloi" type="button" role="tab" aria-controls="phucloi" aria-selected="false">Phụ cấp & phúc lợi</button>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="hopDongTabContent">
            <div class="tab-pane fade show active" id="chitiet" role="tabpanel" aria-labelledby="tab-chitiet">
                <!-- Chi tiết hợp đồng lao động -->
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
            <div class="tab-pane fade" id="phucloi" role="tabpanel" aria-labelledby="tab-phucloi">
                <h5 class="mb-3">Danh sách phúc lợi công ty</h5>
                @if(isset($phucLoiItems) && $phucLoiItems->count())
                <div class="table-responsive mb-4">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 30%">Tên phúc lợi</th>
                                <th>Mô tả</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($phucLoiItems as $item)
                            <tr>
                                <td>{{ $item->ten_item }}</td>
                                <td>{{ $item->mo_ta }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <div class="text-muted">Chưa có phúc lợi nào được cấu hình.</div>
                @endif
                <h5 class="mb-3">Phụ cấp áp dụng cho hợp đồng này</h5>
                @php
                    $phuCapIds = $hopDong->phu_cap_ids ?? [];
                    if (is_string($phuCapIds)) {
                        $phuCapIds = json_decode($phuCapIds, true) ?? [];
                    }
                    $phuCapItems = collect();
                    $phuCapDanhMuc = \App\Models\CaiDatHeThong::where('gia_tri_cai_dat', 'phu-cap')->first();
                    if ($phuCapDanhMuc) {
                        $phuCapItems = \App\Models\CaiDatItem::where('danh_muc_id', $phuCapDanhMuc->id)
                            ->whereIn('id', $phuCapIds)->get();
                    }
                @endphp
                @if($phuCapItems->count())
                    <ul class="list-group mb-3">
                        @foreach($phuCapItems as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $item->ten_item }}</span>
                                @if($item->mo_ta)
                                    <span class="text-muted small">{{ $item->mo_ta }}</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-muted">Chưa chọn phụ cấp nào cho hợp đồng này.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
