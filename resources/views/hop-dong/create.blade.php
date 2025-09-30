@extends('layouts.app')
@section('title', 'Thêm hợp đồng lao động')
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
    <form action="{{ route('hop-dong.store') }}" method="POST">
        @csrf
        <div class="card-body">
        <div class="tab-content" id="hopDongTabContent">
            <div class="tab-pane fade show active" id="chitiet" role="tabpanel" aria-labelledby="tab-chitiet">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nhan_vien_id" class="form-label">Nhân viên</label>
                    <select name="nhan_vien_id" id="nhan_vien_id" class="form-select" required>
                        <option value="">-- Chọn nhân viên --</option>
                        @foreach($nhanViens as $nv)
                            <option value="{{ $nv->id }}" data-search="{{ $nv->ho }} {{ $nv->ten }} {{ $nv->ma_nhanvien }}">
                                {{ $nv->ho }} {{ $nv->ten }} - {{ $nv->ma_nhanvien }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="so_hop_dong" class="form-label">Số hợp đồng</label>
                    <input type="text" name="so_hop_dong" id="so_hop_dong" class="form-control bg-light" required readonly>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="loai_hop_dong" class="form-label">Loại hợp đồng</label>
                    <select name="loai_hop_dong" id="loai_hop_dong" class="form-select">
                        <option value="">-- Chọn loại hợp đồng --</option>
                        <option value="Thử việc" {{ old('loai_hop_dong') == 'Thử việc' ? 'selected' : '' }}>Thử việc</option>
                        <option value="Hợp đồng xác định thời hạn" {{ old('loai_hop_dong') == 'Hợp đồng xác định thời hạn' ? 'selected' : '' }}>Hợp đồng xác định thời hạn</option>
                        <option value="Hợp đồng không xác định thời hạn" {{ old('loai_hop_dong') == 'Hợp đồng không xác định thời hạn' ? 'selected' : '' }}>Hợp đồng không xác định thời hạn</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="ngay_bat_dau" class="form-label">Ngày bắt đầu</label>
                    <input type="date" name="ngay_bat_dau" id="ngay_bat_dau" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="ngay_ket_thuc" class="form-label">Ngày kết thúc</label>
                    <input type="date" name="ngay_ket_thuc" id="ngay_ket_thuc" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="ngay_ky" class="form-label">Ngày ký</label>
                    <input type="date" name="ngay_ky" id="ngay_ky" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="luong_co_ban" class="form-label">Lương cơ bản</label>
                    <input type="text" name="luong_co_ban" id="luong_co_ban" class="form-control money-input" autocomplete="off">
                </div>
                <div class="col-md-3">
                    <label for="luong_bao_hiem" class="form-label">Lương đóng bảo hiểm</label>
                    <input type="text" name="luong_bao_hiem" id="luong_bao_hiem" class="form-control money-input" autocomplete="off">
                </div>
                <div class="col-md-3">
                    <label for="trang_thai" class="form-label">Trạng thái</label>
                    <select name="trang_thai" id="trang_thai" class="form-select" required>
                        <option value="hieu_luc">Hiệu lực</option>
                        <option value="het_hieu_luc">Hết hiệu lực</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="vi_tri_cong_viec" class="form-label">Vị trí công việc</label>
                <input type="text" name="vi_tri_cong_viec" id="vi_tri_cong_viec" class="form-control" value="{{ old('vi_tri_cong_viec') }}">
            </div>
            <div class="mb-3 d-none">
                <label for="phu_cap_ids" class="form-label">Phụ cấp (ẩn)</label>
                <input type="hidden" name="phu_cap_ids" id="phu_cap_ids" value="">
            </div>
            <div class="mb-3">
                <label for="trang_thai_ky" class="form-label">Trạng thái ký</label>
                <select name="trang_thai_ky" id="trang_thai_ky" class="form-select">
                    <option value="">-- Chọn trạng thái --</option>
                    <option value="duyet" {{ old('trang_thai_ky') == 'duyet' ? 'selected' : '' }}>Duyệt</option>
                    <option value="tai_ki" {{ old('trang_thai_ky') == 'tai_ki' ? 'selected' : '' }}>Tái kí</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="thoi_han" class="form-label">Thời hạn hợp đồng</label>
                <select name="thoi_han" id="thoi_han" class="form-select">
                    <option value="">-- Chọn thời hạn --</option>
                    @for($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}" {{ old('thoi_han') == $i ? 'selected' : '' }}>{{ $i }} năm</option>
                    @endfor
                </select>
            </div>
            <div class="mb-3">
                <label for="ghi_chu" class="form-label">Ghi chú</label>
                <textarea name="ghi_chu" id="ghi_chu" class="form-control" rows="2"></textarea>
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
            <h5 class="mb-3">Chọn phụ cấp áp dụng cho hợp đồng</h5>
            @if(isset($phuCapItems) && $phuCapItems->count())
                <div class="row">
                @foreach($phuCapItems as $item)
                    <div class="col-md-4 mb-2">
                        <div class="form-check">
                            <input class="form-check-input phu-cap-checkbox" type="checkbox" value="{{ $item->id }}" id="phuCap{{ $item->id }}">
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
        <div class="card-footer text-end">
            <a href="{{ route('hop-dong.index') }}" class="btn btn-secondary">Quay lại</a>
            <button type="submit" class="btn btn-primary">Lưu hợp đồng</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Nạp Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#nhan_vien_id').select2({
            placeholder: "Chọn nhân viên",
            allowClear: true,
            width: '100%',
            matcher: function(params, data) {
                if ($.trim(params.term) === '') {
                    return data;
                }
                var combinedText = (data.text || '') + ' ' + ($(data.element).data('search') || '');
                if (combinedText.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                    return data;
                }
                return null;
            }
        });

        // Auto render số hợp đồng
        $('#nhan_vien_id').on('change', function() {
            var selected = $(this).find('option:selected');
            var text = selected.text();
            var maNV = '';
            // Tách mã nhân viên từ text "Họ Tên - MÃNV"
            if (text.indexOf('-') > -1) {
                maNV = text.split('-').pop().trim();
            }
            if (maNV) {
                $('#so_hop_dong').val('HĐ_' + maNV);
            } else {
                $('#so_hop_dong').val('');
            }
        });
    });
</script>
@endpush
