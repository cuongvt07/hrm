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

<div class="filter-container mb-3">
    <form id="filterForm" class="needs-validation">
        <div class="d-flex flex-wrap gap-2 align-items-center">
            {{-- Search box with icon --}}
            <div class="search-wrapper position-relative flex-grow-1" style="min-width: 250px; max-width: 400px;">
                <i class="fas fa-search position-absolute text-muted" style="left: 10px; top: 50%; transform: translateY(-50%);"></i>
                <input type="text" class="form-control form-control-sm ps-4" id="search" name="search"
                    placeholder="Tìm kiếm theo tên, mã NV..." value="{{ $search }}"
                    style="padding-left: 30px; border-radius: 20px;">
            </div>

            {{-- Filter dropdowns in a button group --}}
            <div class="btn-group">
                {{-- Department dropdown --}}
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-building me-1"></i> Phòng ban
                    </button>
                    <div class="dropdown-menu p-2" style="min-width: 250px; max-height: 400px; overflow-y: auto;">
                        <select class="form-select form-select-sm select-scrollable" id="phong_ban_id" name="phong_ban_id" onchange="this.form.submit()" size="8">
                            <option value="">Tất cả phòng ban</option>
                            @foreach($phongBans as $phongBan)
                                <option value="{{ $phongBan->id }}" {{ $phong_ban_id == $phongBan->id ? 'selected' : '' }}>
                                    {{ $phongBan->ten_phong_ban }}
                                </option>
                                @if($phongBan->phongBanCon && $phongBan->phongBanCon->count() > 0)
                                    @foreach($phongBan->phongBanCon as $phongBanCon)
                                        <option value="{{ $phongBanCon->id }}" {{ $phong_ban_id == $phongBanCon->id ? 'selected' : '' }}>
                                            &nbsp;&nbsp;&nbsp;&nbsp;└─ {{ $phongBanCon->ten_phong_ban }}
                                        </option>
                                    @endforeach
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Position dropdown --}}
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-tie me-1"></i> Chức vụ
                    </button>
                    <div class="dropdown-menu p-2" style="min-width: 200px;">
                        <select class="form-select form-select-sm" id="chuc_vu_id" name="chuc_vu_id" onchange="this.form.submit()">
                            <option value="">Tất cả chức vụ</option>
                            @foreach($chucVus as $chucVu)
                                <option value="{{ $chucVu->id }}" {{ $chuc_vu_id == $chucVu->id ? 'selected' : '' }}>
                                    {{ $chucVu->ten_chuc_vu }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Status dropdown --}}
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-clock me-1"></i> Trạng thái
                    </button>
                    <div class="dropdown-menu p-2" style="min-width: 200px;">
                        <select class="form-select form-select-sm" id="trang_thai" name="trang_thai" onchange="this.form.submit()">
                            <option value="">Tất cả trạng thái</option>
                            <option value="nhan_vien_chinh_thuc" {{ $trang_thai == 'nhan_vien_chinh_thuc' ? 'selected' : '' }}>
                                <i class="fas fa-check-circle text-success"></i> Đang làm việc
                            </option>
                            <option value="thu_viec" {{ $trang_thai == 'thu_viec' ? 'selected' : '' }}>
                                <i class="fas fa-user-clock text-warning"></i> Thử việc
                            </option>
                            <option value="thai_san" {{ $trang_thai == 'thai_san' ? 'selected' : '' }}>
                                <i class="fas fa-baby text-info"></i> Thai sản
                            </option>
                            <option value="nghi_viec" {{ $trang_thai == 'nghi_viec' ? 'selected' : '' }}>
                                <i class="fas fa-user-times text-danger"></i> Nghỉ việc
                            </option>
                        </select>
                    </div>
                </div>

                {{-- Reset button --}}
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetFilter()" title="Đặt lại bộ lọc">
                    <i class="fas fa-undo"></i>
                </button>
            </div>

            {{-- Action buttons --}}
            <div class="ms-auto">
                <div class="btn-group">
                    @if($showBulkDelete)
                        <button type="button" class="btn btn-outline-danger btn-sm"
                                onclick="deleteSelectedEmployees()" id="bulkDeleteBtn" disabled
                                title="Xóa các nhân viên đã chọn">
                            <i class="fas fa-trash me-1"></i> Xóa
                        </button>
                    @endif
                    @if($showExport)
                        <a href="{{ route('nhan-vien.export') }}" 
                           class="btn btn-outline-success btn-sm"
                           onclick="return exportWithFilters(event)"
                           title="Xuất danh sách nhân viên">
                            <i class="fas fa-file-excel me-1"></i> Xuất Excel
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Active filters display --}}
        <div class="active-filters mt-2" style="font-size: 0.875rem;">
            @if($search || $phong_ban_id || $chuc_vu_id || $trang_thai)
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <span class="text-muted"><i class="fas fa-filter me-1"></i>Bộ lọc:</span>
                    @if($search)
                        <span class="badge bg-light text-dark border">
                            Tìm kiếm: {{ $search }}
                            <button type="button" class="btn-close btn-close-sm ms-2" onclick="clearFilter('search')"></button>
                        </span>
                    @endif
                    @if($phong_ban_id)
                        <span class="badge bg-light text-dark border">
                            Phòng ban: {{ $phongBans->firstWhere('id', $phong_ban_id)?->ten_phong_ban }}
                            <button type="button" class="btn-close btn-close-sm ms-2" onclick="clearFilter('phong_ban_id')"></button>
                        </span>
                    @endif
                    @if($chuc_vu_id)
                        <span class="badge bg-light text-dark border">
                            Chức vụ: {{ $chucVus->firstWhere('id', $chuc_vu_id)?->ten_chuc_vu }}
                            <button type="button" class="btn-close btn-close-sm ms-2" onclick="clearFilter('chuc_vu_id')"></button>
                        </span>
                    @endif
                    @if($trang_thai)
                        <span class="badge bg-light text-dark border">
                            Trạng thái: {{ $trang_thai == 'nhan_vien_chinh_thuc' ? 'Đang làm việc' : ($trang_thai == 'thu_viec' ? 'Thử việc' : ($trang_thai == 'thai_san' ? 'Thai sản' : ($trang_thai == 'nghi_viec' ? 'Nghỉ việc' : 'Khác'))) }}
                            <button type="button" class="btn-close btn-close-sm ms-2" onclick="clearFilter('trang_thai')"></button>
                        </span>
                    @endif
                </div>
            @endif
        </div>
    </form>
</div>

<style>
.filter-container {
    background: #fff;
    border-radius: 8px;
    padding: 1rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}
.search-wrapper .form-control:focus {
    box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.15);
    border-color: #80bdff;
}
.active-filters .badge {
    font-weight: normal;
    padding: 0.4em 0.8em;
}
.btn-close-sm {
    font-size: 0.5rem;
    padding: 0.25em;
}
.dropdown-menu {
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.select-scrollable {
    height: auto !important;
    max-height: 350px;
    overflow-y: auto;
}
.select-scrollable option {
    padding: 0.5rem 0.75rem;
}
</style>

<script>
function resetFilter() {
    document.getElementById("filterForm").reset();
    window.location.href = "{{ route('nhan-vien.index') }}";
}

function clearFilter(fieldName) {
    document.getElementById(fieldName).value = '';
    document.getElementById('filterForm').submit();
}

// Auto-submit on select change
document.querySelectorAll('.dropdown-menu select').forEach(select => {
    select.addEventListener('change', function() {
        this.closest('form').submit();
    });
});

// Prevent dropdown from closing when selecting
document.querySelectorAll('.dropdown-menu').forEach(dropdown => {
    dropdown.addEventListener('click', e => {
        e.stopPropagation();
    });
});
</script>

{{-- Script xuất Excel --}}
<script>
    function exportWithFilters(event) {
        event.preventDefault();
        const form = document.getElementById('filterForm');
        const formData = new FormData(form);
        const searchParams = new URLSearchParams(formData);
        window.location.href = '{{ route('nhan-vien.export') }}?' + searchParams.toString();
        return false;
    }
</script>

{{-- Script reset --}}
<script>
    function resetFilter() {
        document.getElementById("filterForm").reset();
        window.location.href = "{{ route('nhan-vien.index') }}";
    }
</script>
