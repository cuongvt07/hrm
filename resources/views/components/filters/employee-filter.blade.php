@props([
    'search' => '',
    'phong_ban_id' => '',
    'chuc_vu_id' => '',
    'trang_thai' => '',
    'phongBans' => collect(),
    'chucVus' => collect(),
    'showBulkDelete' => true,
    'showExport' => true
])

<div class="card">
    <div class="card-body py-2">
        <form id="filterForm" class="row g-2 align-items-center">
            {{-- Ô tìm kiếm --}}
            <div class="col-md-3 col-12">
                <input type="text" class="form-control form-control-sm" id="search" name="search"
                       placeholder="Tìm kiếm theo tên, mã NV..." value="{{ $search }}">
            </div>

            {{-- Chọn phòng ban --}}
            <div class="col-md-2 col-6">
                <select class="form-select form-select-sm" id="phong_ban_id" name="phong_ban_id">
                    <option value="">Tất cả phòng ban</option>
                    @foreach($phongBans as $phongBan)
                        <option value="{{ $phongBan->id }}" {{ $phong_ban_id == $phongBan->id ? 'selected' : '' }}>
                            {{ $phongBan->ten_phong_ban }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Chọn chức vụ --}}
            <div class="col-md-2 col-6">
                <select class="form-select form-select-sm" id="chuc_vu_id" name="chuc_vu_id">
                    <option value="">Tất cả chức vụ</option>
                    @foreach($chucVus as $chucVu)
                        <option value="{{ $chucVu->id }}" {{ $chuc_vu_id == $chucVu->id ? 'selected' : '' }}>
                            {{ $chucVu->ten_chuc_vu }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Chọn trạng thái --}}
            <div class="col-md-2 col-6">
                <select class="form-select form-select-sm" id="trang_thai" name="trang_thai">
                    <option value="">Tất cả trạng thái</option>
                    <option value="nhan_vien_chinh_thuc" {{ $trang_thai == 'nhan_vien_chinh_thuc' ? 'selected' : '' }}>Đang làm việc</option>
                    <option value="thu_viec" {{ $trang_thai == 'thu_viec' ? 'selected' : '' }}>Thử việc</option>
                    <option value="thai_san" {{ $trang_thai == 'thai_san' ? 'selected' : '' }}>Thai sản</option>
                    <option value="nghi_viec" {{ $trang_thai == 'nghi_viec' ? 'selected' : '' }}>Nghỉ việc</option>
                    <option value="khac" {{ $trang_thai == 'khac' ? 'selected' : '' }}>Khác</option>
                </select>
            </div>

            {{-- Nhóm nút --}}
            <div class="col-md-3 col-12">
                <div class="d-flex flex-wrap">
                    <div class="btn-group me-2 mb-2 mb-md-0" role="group">
                        <button type="submit" class="btn btn-primary btn-sm" title="Tìm kiếm">
                            <i class="fas fa-search me-1"></i> Tìm kiếm
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetFilter()" title="Đặt lại bộ lọc">
                            <i class="fas fa-undo me-1"></i> Reset
                        </button>
                    </div>

                    @if($showBulkDelete)
                        <button type="button" class="btn btn-outline-danger btn-sm mb-2 mb-md-0"
                                onclick="deleteSelectedEmployees()" id="bulkDeleteBtn" disabled
                                title="Xóa các nhân viên đã chọn">
                            <i class="fas fa-trash me-1"></i> Xóa nhiều
                        </button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Script reset --}}
<script>
    function resetFilter() {
        document.getElementById("filterForm").reset();
        window.location.href = "{{ route('nhan-vien.index') }}";
    }
</script>
