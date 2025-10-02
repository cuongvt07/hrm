@extends('layouts.app')
@section('title', 'Tạo quyết định khen thưởng/kỷ luật')
@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Tạo quyết định {{ request('loai', old('loai', 'khen_thuong')) === 'ky_luat' ? 'kỷ luật' : 'khen thưởng' }}</h5>
    </div>
    <form action="{{ route('che-do.khen-thuong-ky-luat.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="loai" value="{{ request('loai', old('loai', 'khen_thuong')) }}">
        <div class="card-body">
            <div class="row mb-3">
                <input type="hidden" name="loai" value="{{ request('loai', old('loai', 'khen_thuong')) }}">
                <div class="col-md-3">
                    <label class="form-label">Số quyết định</label>
                    <input type="text" name="so_quyet_dinh" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Ngày quyết định <span class="text-danger">*</span></label>
                    <input type="date" name="ngay_quyet_dinh" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Người quyết định</label>
                    <input type="text" name="nguoi_quyet_dinh" class="form-control">
                </div>
            </div>
            <div class="mb-3">
        <div class="mb-3">
            <label class="form-label">Trạng thái</label>
            <select name="trang_thai" class="form-select">
                <option value="chua_thuc_hien" selected>Chưa thực hiện</option>
                <option value="dang_thuc_hien">Đang thực hiện</option>
                <option value="hoan_thanh">Hoàn thành</option>
            </select>
        </div>
                <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                <input type="text" name="tieu_de" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea name="mo_ta" class="form-control" rows="2"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Giá trị (nếu có)</label>
                <input type="number" name="gia_tri" class="form-control" min="0" step="0.01">
            </div>
            <div class="mb-3">
                <label class="form-label">Tệp đính kèm (nếu có)</label>
                <input type="file" name="tep_tin" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Áp dụng cho <span class="text-danger">*</span></label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="loai_doi_tuong" id="radioCaNhan" value="nhan_vien" checked>
                    <label class="form-check-label" for="radioCaNhan">Cá nhân</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="loai_doi_tuong" id="radioTapThe" value="phong_ban">
                    <label class="form-check-label" for="radioTapThe">Tập thể</label>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12" id="chonNhanVienBox">
                    <label class="form-label">Chọn nhân viên áp dụng</label>
                    <select id="selectNhanVien" class="form-select">
                        <option value="" disabled selected>-- Chọn nhân viên --</option>
                        @foreach($nhanViens as $nv)
                            <option value="{{ $nv->id }}">{{ $nv->ho }} {{ $nv->ten }}</option>
                        @endforeach
                    </select>
                    <div id="previewNhanVien" class="mt-2"></div>
                </div>
                <div class="col-md-12 d-none" id="chonPhongBanBox">
                    <label class="form-label">Chọn phòng ban áp dụng</label>
                    <select id="selectPhongBan" class="form-select">
                        <option value="" disabled selected>-- Chọn phòng ban --</option>
                        @foreach($phongBans as $pb)
                            <option value="{{ $pb->id }}">{{ $pb->ten_phong_ban }}</option>
                        @endforeach
                    </select>
                    <div id="previewPhongBan" class="mt-2"></div>
                </div>
            </div>
        </div>
        <div class="card-footer text-end">
            <a href="{{ route('che-do.khen-thuong.index') }}" class="btn btn-secondary">Quay lại</a>
            <button type="submit" class="btn btn-primary">Lưu quyết định</button>
        </div>
    </form>
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const radioCaNhan = document.getElementById('radioCaNhan');
        const radioTapThe = document.getElementById('radioTapThe');
        const chonNhanVienBox = document.getElementById('chonNhanVienBox');
        const chonPhongBanBox = document.getElementById('chonPhongBanBox');
        const selectNhanVien = document.getElementById('selectNhanVien');
        const selectPhongBan = document.getElementById('selectPhongBan');
        const previewNhanVien = document.getElementById('previewNhanVien');
        const previewPhongBan = document.getElementById('previewPhongBan');

        // Lưu các đối tượng đã chọn
        let selectedNhanViens = [];
        let selectedPhongBans = [];

        function toggleBox() {
            if (radioCaNhan.checked) {
                chonNhanVienBox.classList.remove('d-none');
                chonPhongBanBox.classList.add('d-none');
            } else {
                chonNhanVienBox.classList.add('d-none');
                chonPhongBanBox.classList.remove('d-none');
            }
        }
        radioCaNhan.addEventListener('change', toggleBox);
        radioTapThe.addEventListener('change', toggleBox);
        toggleBox();

        // Thêm nhân viên vào preview
        selectNhanVien && selectNhanVien.addEventListener('change', function() {
            const option = selectNhanVien.options[selectNhanVien.selectedIndex];
            const value = option.value;
            const text = option.text;
            if (value && !selectedNhanViens.some(item => item.value === value)) {
                selectedNhanViens.push({ value, text });
                renderPreview(previewNhanVien, selectedNhanViens, 'nhan_vien');
                option.disabled = true;
                selectNhanVien.selectedIndex = 0;
            }
        });
        // Thêm phòng ban vào preview
        selectPhongBan && selectPhongBan.addEventListener('change', function() {
            const option = selectPhongBan.options[selectPhongBan.selectedIndex];
            const value = option.value;
            const text = option.text;
            if (value && !selectedPhongBans.some(item => item.value === value)) {
                selectedPhongBans.push({ value, text });
                renderPreview(previewPhongBan, selectedPhongBans, 'phong_ban');
                option.disabled = true;
                selectPhongBan.selectedIndex = 0;
            }
        });

        // Render preview và nút xóa
        function renderPreview(container, arr, type) {
            container.innerHTML = '';
            arr.forEach((item, idx) => {
                const div = document.createElement('div');
                div.className = 'badge bg-primary text-white me-2 mb-2';
                div.style.fontSize = '1rem';
                div.innerHTML = `
                    <span>${item.text}</span>
                    <button type="button" class="btn btn-sm btn-danger ms-2 py-0 px-2" style="font-size:0.9em;line-height:1;" data-idx="${idx}" data-type="${type}">&times;</button>
                    <input type="hidden" name="doi_tuong_ap_dung[]" value="${item.value}">
                `;
                container.appendChild(div);
            });
            // Gán sự kiện xóa
            container.querySelectorAll('button[data-type]').forEach(btn => {
                btn.addEventListener('click', function() {
                    const idx = parseInt(this.getAttribute('data-idx'));
                    const type = this.getAttribute('data-type');
                    if (type === 'nhan_vien') {
                        // Enable lại option
                        const val = selectedNhanViens[idx].value;
                        [...selectNhanVien.options].forEach(opt => { if (opt.value === val) opt.disabled = false; });
                        selectedNhanViens.splice(idx, 1);
                        renderPreview(previewNhanVien, selectedNhanViens, 'nhan_vien');
                    } else {
                        const val = selectedPhongBans[idx].value;
                        [...selectPhongBan.options].forEach(opt => { if (opt.value === val) opt.disabled = false; });
                        selectedPhongBans.splice(idx, 1);
                        renderPreview(previewPhongBan, selectedPhongBans, 'phong_ban');
                    }
                });
            });
        }
    });
</script>
@endpush
    </form>
</div>
@endsection
