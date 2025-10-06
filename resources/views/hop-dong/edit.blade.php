@extends('layouts.app')
@section('title', 'Sửa hợp đồng lao động')
@section('content')
<div class="card">
    <div class="card-header pb-0">
        <ul class="nav nav-tabs card-header-tabs" id="hopDongTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab-chitiet" data-bs-toggle="tab" data-bs-target="#chitiet" type="button" role="tab" aria-controls="chitiet" aria-selected="true">Chi tiết hợp đồng</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-phucap" data-bs-toggle="tab" data-bs-target="#phucap" type="button" role="tab" aria-controls="phucap" aria-selected="false">Phụ cấp & phúc lợi</button>
            </li>
        </ul>
    </div>
    <!-- Hiển thị lỗi chung -->
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('hop-dong.update', $hopDong->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="tab-content" id="hopDongTabContent">
                <div class="tab-pane fade show active" id="chitiet" role="tabpanel" aria-labelledby="tab-chitiet">
                    <!-- Thông tin chi tiết hợp đồng (giống create) -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nhan_vien_id" class="form-label">Nhân viên</label>
                            <input type="text" class="form-control bg-light" value="{{ $hopDong->nhanVien->ho ?? '' }} {{ $hopDong->nhanVien->ten ?? '' }} - {{ $hopDong->nhanVien->ma_nhanvien ?? '' }}" readonly>
                            <input type="hidden" name="nhan_vien_id" value="{{ $hopDong->nhan_vien_id }}">
                            @error('nhan_vien_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="so_hop_dong" class="form-label">Số hợp đồng</label>
                            <input type="text" name="so_hop_dong" id="so_hop_dong" class="form-control bg-light" value="{{ $hopDong->so_hop_dong }}" required readonly>
                            @error('so_hop_dong')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="tep_tin_hop_dong" class="form-label">Tài liệu hợp đồng (ảnh, doc, excel, pdf)</label>
                        <input type="file" name="tep_tin_hop_dong[]" id="tep_tin_hop_dong" class="form-control" accept=".jpg,.jpeg,.png,.gif,.bmp,.pdf,.doc,.docx,.xls,.xlsx" multiple>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <small class="text-muted">Có thể chọn nhiều file. Định dạng: ảnh, pdf, doc, excel.</small>
                            @if($hopDong->tepTin && count($hopDong->tepTin))
                                <span class="ms-2">
                                    @foreach($hopDong->tepTin as $file)
                                        <a href="{{ asset('storage/' . $file->duong_dan_tep) }}" target="_blank" class="badge bg-light text-dark border me-1">
                                            <i class="fas fa-paperclip me-1"></i>{{ $file->ten_tep ?? basename($file->duong_dan_tep) }}
                                        </a>
                                    @endforeach
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="loai_hop_dong" class="form-label">Loại hợp đồng</label>
                            <select name="loai_hop_dong" id="loai_hop_dong" class="form-select">
                                @error('loai_hop_dong')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                                <option value="">-- Chọn loại hợp đồng --</option>
                                <option value="Thử việc" {{ $hopDong->loai_hop_dong == 'Thử việc' ? 'selected' : '' }}>Thử việc</option>
                                <option value="Hợp đồng xác định thời hạn" {{ $hopDong->loai_hop_dong == 'Hợp đồng xác định thời hạn' ? 'selected' : '' }}>Hợp đồng xác định thời hạn</option>
                                <option value="Hợp đồng không xác định thời hạn" {{ $hopDong->loai_hop_dong == 'Hợp đồng không xác định thời hạn' ? 'selected' : '' }}>Hợp đồng không xác định thời hạn</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="ngay_bat_dau" class="form-label">Ngày bắt đầu</label>
                            <input type="date" name="ngay_bat_dau" id="ngay_bat_dau" class="form-control" value="{{ $hopDong->ngay_bat_dau ? $hopDong->ngay_bat_dau->format('Y-m-d') : '' }}">
                            @error('ngay_bat_dau')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="ngay_ket_thuc" class="form-label">Ngày kết thúc</label>
                            <input type="date" name="ngay_ket_thuc" id="ngay_ket_thuc" class="form-control" value="{{ $hopDong->ngay_ket_thuc ? $hopDong->ngay_ket_thuc->format('Y-m-d') : '' }}">
                            @error('ngay_ket_thuc')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="ngay_ky" class="form-label">Ngày ký</label>
                            <input type="date" name="ngay_ky" id="ngay_ky" class="form-control" value="{{ $hopDong->ngay_ky ? $hopDong->ngay_ky->format('Y-m-d') : '' }}">
                            @error('ngay_ky')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="luong_co_ban" class="form-label">Lương cơ bản</label>
                            <input type="text" name="luong_co_ban" id="luong_co_ban" class="form-control money-input" value="{{ number_format($hopDong->luong_co_ban, 0, ',', '.') }}" autocomplete="off">
                            @error('luong_co_ban')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="luong_bao_hiem" class="form-label">Lương đóng bảo hiểm</label>
                            <input type="text" name="luong_bao_hiem" id="luong_bao_hiem" class="form-control money-input" value="{{ number_format($hopDong->luong_bao_hiem, 0, ',', '.') }}" autocomplete="off">
                            @error('luong_bao_hiem')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="trang_thai" class="form-label">Trạng thái</label>
                            <select name="trang_thai" id="trang_thai" class="form-select" required>
                                @error('trang_thai')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                                <option value="hieu_luc" {{ $hopDong->trang_thai == 'hieu_luc' ? 'selected' : '' }}>Hiệu lực</option>
                                <option value="het_hieu_luc" {{ $hopDong->trang_thai == 'het_hieu_luc' ? 'selected' : '' }}>Hết hiệu lực</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="vi_tri_cong_viec" class="form-label">Vị trí công việc</label>
                        <input type="text" name="vi_tri_cong_viec" id="vi_tri_cong_viec" class="form-control" value="{{ $hopDong->vi_tri_cong_viec }}">
                        @error('vi_tri_cong_viec')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="trang_thai_ky" class="form-label">Trạng thái ký</label>
                        <select name="trang_thai_ky" id="trang_thai_ky" class="form-select">
                            @error('trang_thai_ky')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                            <option value="">-- Chọn trạng thái --</option>
                            <option value="duyet" {{ $hopDong->trang_thai_ky == 'duyet' ? 'selected' : '' }}>Đã ký</option>
                            <option value="tai_ki" {{ $hopDong->trang_thai_ky == 'tai_ki' ? 'selected' : '' }}>Gia hạn</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="thoi_han" class="form-label">Thời hạn hợp đồng (tháng)</label>
                        <select name="thoi_han" id="thoi_han" class="form-select">
                            @error('thoi_han')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                            <option value="">-- Chọn thời hạn --</option>
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}" {{ $hopDong->thoi_han == $i ? 'selected' : '' }}>{{ $i }} năm</option>
                            @endfor
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="ghi_chu" class="form-label">Ghi chú</label>
                        <textarea name="ghi_chu" id="ghi_chu" class="form-control" rows="2">{{ $hopDong->ghi_chu }}</textarea>
                        @error('ghi_chu')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
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
                                    <input class="form-check-input phu-cap-checkbox" type="checkbox" value="{{ $item->id }}" id="phuCap{{ $item->id }}" @if($selectedPhuCap->contains($item->id)) checked @endif>
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
<script>
// Khi submit form, lưu các phụ cấp đã chọn vào input hidden
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function() {
            const checked = Array.from(document.querySelectorAll('.phu-cap-checkbox:checked')).map(cb => cb.value);
            document.getElementById('phu_cap_ids').value = JSON.stringify(checked);
        });
    }
});
</script>
@endpush
