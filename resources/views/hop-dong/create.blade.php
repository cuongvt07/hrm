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
    <form action="{{ route('hop-dong.store') }}" method="POST" enctype="multipart/form-data" id="hopDongForm">
        @csrf
        <!-- HIDDEN INPUT PHẢI Ở NGOÀI TAB CONTENT -->
        <input type="hidden" name="phu_cap_ids" id="phu_cap_ids" value="">
        
        <div class="card-body">
        <div class="tab-content" id="hopDongTabContent">
            <div class="tab-pane fade show active" id="chitiet" role="tabpanel" aria-labelledby="tab-chitiet">
            <div class="mb-3">
                <label for="tep_tin_hop_dong" class="form-label">Tài liệu hợp đồng (ảnh, doc, excel, pdf)</label>
                <input type="file" name="tep_tin_hop_dong[]" id="tep_tin_hop_dong" class="form-control" accept=".jpg,.jpeg,.png,.gif,.bmp,.pdf,.doc,.docx,.xls,.xlsx" multiple>
                <small class="text-muted">Có thể chọn nhiều file. Định dạng: ảnh, pdf, doc, excel.</small>
            </div>
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
                <div class="col-md-3" id="ngay_ket_thuc_group">
                    <label for="ngay_ket_thuc" class="form-label">Ngày kết thúc</label>
                    <input type="date" name="ngay_ket_thuc" id="ngay_ket_thuc" class="form-control bg-light" readonly style="pointer-events:none;">
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
            
            <div class="mb-3">
                <label for="trang_thai_ky" class="form-label">Trạng thái ký</label>
                <select name="trang_thai_ky" id="trang_thai_ky" class="form-select">
                    <option value="">-- Chọn trạng thái --</option>
                    <option value="duyet" {{ old('trang_thai_ky') == 'duyet' ? 'selected' : '' }}>Đã ký</option>
                    <option value="tai_ki" {{ old('trang_thai_ky') == 'tai_ki' ? 'selected' : '' }}>Gia hạn</option>
                </select>
            </div>
            <div class="mb-3" id="thoi_han_group">
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
                            <input class="form-check-input phu-cap-checkbox" type="checkbox" value="{{ $item->id }}" id="phuCap{{ $item->id }}" data-name="{{ $item->ten_item }}">
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
            <button type="submit" class="btn btn-primary">Lưu hợp đồng</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== SCRIPT KHỞI TẠO ===');
    
    // 1. Khởi tạo Select2
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

    // 2. Auto generate số hợp đồng
    function generateRandomSuffix(len = 6) {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let out = '';
        for (let i = 0; i < len; i++) out += chars.charAt(Math.floor(Math.random() * chars.length));
        return out;
    }

    $('#nhan_vien_id').on('change', function() {
        var selected = $(this).find('option:selected');
        var text = selected.text();
        var maNV = '';
        if (text.indexOf('-') > -1) {
            maNV = text.split('-').pop().trim();
        }
        if (maNV) {
            let base = 'HĐ_' + maNV;
            fetch("{{ url('hop-dong/check-employee') }}/" + selected.val())
                .then(res => res.json())
                .then(data => {
                    if (data && data.count && data.count > 0) {
                        $('#so_hop_dong').val(base + '_' + generateRandomSuffix());
                    } else {
                        $('#so_hop_dong').val(base);
                    }
                })
                .catch(err => {
                    console.error('Error checking employee contracts', err);
                    $('#so_hop_dong').val(base);
                });
        } else {
            $('#so_hop_dong').val('');
        }
    });

    // 3. Format money input
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

    // 4. Xử lý loại hợp đồng
    function handleLoaiHopDongChange() {
        const loaiHopDong = document.getElementById('loai_hop_dong').value;
        const ngayKetThucGroup = document.getElementById('ngay_ket_thuc_group');
        const thoiHanGroup = document.getElementById('thoi_han_group');
        const ngayKetThucInput = document.getElementById('ngay_ket_thuc');
        const thoiHanInput = document.getElementById('thoi_han');

        if (!ngayKetThucGroup || !thoiHanGroup || !ngayKetThucInput || !thoiHanInput) return;

        if (loaiHopDong === 'Hợp đồng không xác định thời hạn') {
            ngayKetThucGroup.style.display = 'none';
            thoiHanGroup.style.display = 'none';
            ngayKetThucInput.value = '';
            thoiHanInput.value = '';
            ngayKetThucInput.removeAttribute('required');
            thoiHanInput.removeAttribute('required');
            ngayKetThucInput.setAttribute('disabled', 'disabled');
            thoiHanInput.setAttribute('disabled', 'disabled');
        } else {
            ngayKetThucGroup.style.display = 'block';
            thoiHanGroup.style.display = 'block';
            ngayKetThucInput.removeAttribute('disabled');
            thoiHanInput.removeAttribute('disabled');
            if (loaiHopDong === 'Hợp đồng xác định thời hạn' || loaiHopDong === 'Thử việc') {
                ngayKetThucInput.setAttribute('required', 'required');
                thoiHanInput.setAttribute('required', 'required');
            } else {
                ngayKetThucInput.removeAttribute('required');
                thoiHanInput.removeAttribute('required');
            }
        }
    }

    function populateThoiHanOptions(selectEl, loai) {
        if (!selectEl) return;
        const prev = selectEl.value;
        selectEl.innerHTML = '';
        const optDefault = document.createElement('option');
        optDefault.value = '';
        optDefault.text = '-- Chọn thời hạn --';
        selectEl.appendChild(optDefault);
        if (loai === 'Thử việc') {
            for (let i = 1; i <= 12; i++) {
                const opt = document.createElement('option');
                opt.value = i;
                opt.text = i + ' tháng';
                selectEl.appendChild(opt);
            }
        } else {
            for (let i = 1; i <= 10; i++) {
                const opt = document.createElement('option');
                opt.value = i;
                opt.text = i + ' năm';
                selectEl.appendChild(opt);
            }
        }
        if (prev) {
            selectEl.value = prev;
            if (selectEl.value !== prev) selectEl.value = '';
        }
    }

    function isFixedTerm(loai) {
        if (!loai) return false;
        return loai.indexOf('xac_dinh') !== -1 || loai.indexOf('Hợp đồng xác định thời hạn') !== -1;
    }

    function computeNgayKetThuc() {
        const loai = document.getElementById('loai_hop_dong') ? document.getElementById('loai_hop_dong').value : '';
        const ngayBatDau = document.getElementById('ngay_bat_dau') ? document.getElementById('ngay_bat_dau').value : '';
        const thoiHan = document.getElementById('thoi_han') ? document.getElementById('thoi_han').value : '';
        const ngayKetThucInput = document.getElementById('ngay_ket_thuc');
        if (!ngayKetThucInput) return;

        if (ngayBatDau && thoiHan) {
            const d = new Date(ngayBatDau);
            if (isNaN(d.getTime())) {
                ngayKetThucInput.value = '';
                return;
            }
            if (loai === 'Thử việc') {
                d.setMonth(d.getMonth() + parseInt(thoiHan, 10));
                d.setDate(d.getDate() - 1);
            } else if (isFixedTerm(loai)) {
                d.setFullYear(d.getFullYear() + parseInt(thoiHan, 10));
                d.setDate(d.getDate() - 1);
            } else {
                d.setFullYear(d.getFullYear() + parseInt(thoiHan, 10));
                d.setDate(d.getDate() - 1);
            }
            const yyyy = d.getFullYear();
            const mm = String(d.getMonth() + 1).padStart(2, '0');
            const dd = String(d.getDate()).padStart(2, '0');
            ngayKetThucInput.value = `${yyyy}-${mm}-${dd}`;
        } else {
            ngayKetThucInput.value = '';
        }
    }

    const loaiSel = document.getElementById('loai_hop_dong');
    if (loaiSel) {
        handleLoaiHopDongChange();
        populateThoiHanOptions(document.getElementById('thoi_han'), loaiSel.value);
        
        loaiSel.addEventListener('change', function() {
            populateThoiHanOptions(document.getElementById('thoi_han'), loaiSel.value);
            handleLoaiHopDongChange();
            computeNgayKetThuc();
        });
    }

    const thoiHanEl = document.getElementById('thoi_han');
    const ngayBatDauEl = document.getElementById('ngay_bat_dau');
    if (thoiHanEl) thoiHanEl.addEventListener('change', computeNgayKetThuc);
    if (ngayBatDauEl) ngayBatDauEl.addEventListener('change', computeNgayKetThuc);
    computeNgayKetThuc();

    // 5. XỬ LÝ CHECKBOX PHỤ CẤP - QUAN TRỌNG NHẤT
    console.log('=== KHỞI TẠO CHECKBOX PHỤ CẤP ===');
    
    // Debug: Kiểm tra checkboxes
    setTimeout(function() {
        const checkboxes = document.querySelectorAll('.phu-cap-checkbox');
        console.log('Tổng số checkbox:', checkboxes.length);
        checkboxes.forEach(function(cb, index) {
            console.log(`Checkbox ${index}: ID=${cb.id}, Value=${cb.value}, Checked=${cb.checked}`);
        });
    }, 500);

    // 6. XỬ LÝ FORM SUBMIT - QUAN TRỌNG
    const form = document.getElementById('hopDongForm');
    if (form) {
        console.log('Form tìm thấy, gắn event submit...');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Tạm dừng để xử lý
            
            console.log('=== FORM SUBMIT - BẮT ĐẦU XỬ LÝ ===');
            
            // Bước 1: Thu thập phụ cấp đã chọn
            const checkboxes = document.querySelectorAll('.phu-cap-checkbox:checked');
            console.log('Số checkbox được chọn:', checkboxes.length);
            
            const selectedIds = [];
            checkboxes.forEach(function(cb) {
                console.log('Checkbox đã chọn:', cb.value, '-', cb.dataset.name);
                selectedIds.push(cb.value);
            });
            
            console.log('Danh sách ID phụ cấp:', selectedIds);
            
            // Bước 2: Lưu vào hidden input
            const hiddenInput = document.getElementById('phu_cap_ids');
            if (hiddenInput) {
                const jsonValue = JSON.stringify(selectedIds);
                hiddenInput.value = jsonValue;
                console.log('Hidden input đã được set:', jsonValue);
                console.log('Verify giá trị hidden input:', hiddenInput.value);
            } else {
                console.error('KHÔNG TÌM THẤY HIDDEN INPUT #phu_cap_ids!');
                alert('Lỗi: Không tìm thấy trường phụ cấp. Vui lòng liên hệ quản trị viên.');
                return false;
            }
            
            // Bước 3: Clean money inputs
            const moneyInputs = form.querySelectorAll('.money-input');
            moneyInputs.forEach(function(input) {
                const oldValue = input.value;
                input.value = input.value.replace(/\./g, '').replace(/,/g, '');
                console.log('Money input cleaned:', oldValue, '->', input.value);
            });
            
            // Bước 4: Hiển thị thông báo xác nhận nếu không chọn phụ cấp
            if (selectedIds.length === 0) {
                console.warn('Không có phụ cấp nào được chọn');
                if (!confirm('Bạn chưa chọn phụ cấp nào. Tiếp tục lưu hợp đồng?')) {
                    return false;
                }
            }
            
            console.log('=== SUBMIT FORM ===');
            // Submit form
            form.submit();
        });
        
        console.log('Event submit đã được gắn thành công!');
    } else {
        console.error('KHÔNG TÌM THẤY FORM!');
    }
});
</script>
@endpush