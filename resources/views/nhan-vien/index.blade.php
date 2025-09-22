@extends('layouts.app')

@section('title', 'Quản lý Nhân viên')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">Quản lý Nhân viên</h2>
                    <p class="text-muted mb-0">Quản lý thông tin nhân viên trong hệ thống</p>
                </    .then(response => response.text())
    .then(html => {
        // Update the entire employee list section
        const employeeListSection = document.querySelector('.row.mb-3').nextElementSibling;
        employeeListSection.outerHTML = html;

        // Update total count in header
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const totalElement = doc.getElementById('totalEmployees');
        if (totalElement) {
            document.getElementById('totalEmployees').textContent = totalElement.textContent;
        }

        // Re-setup event listeners
        setupDeleteListeners();
        setupBulkActions();

        hideLoading();
    })         <div>
                    <a href="{{ route('nhan-vien.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Thêm nhân viên
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-3">
        <div class="col-12">
            <x-filters.employee-filter
                :search="request('search')"
                :phong_ban_id="request('phong_ban_id')"
                :chuc_vu_id="request('chuc_vu_id')"
                :trang_thai="request('trang_thai')"
                :phongBans="$phongBans"
                :chucVus="$chucVus"
            />
        </div>
    </div>

    <!-- Employee List Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Danh sách nhân viên</h5>
                    <div class="d-flex align-items-center">
                        <span class="text-muted me-3">Tổng: <strong id="totalEmployees">{{ $nhanViens->total() }}</strong> nhân viên</span>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="exportEmployees('excel')">
                                <i class="fas fa-file-excel me-1"></i>Excel
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="exportEmployees('pdf')">
                                <i class="fas fa-file-pdf me-1"></i>PDF
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @include('nhan-vien.partials.table')
                </div>
                @if($nhanViens->hasPages())
                <div class="card-footer">
                    {{ $nhanViens->appends(request()->query())->links() }}
                </div>
                @endif
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="selectAll" class="form-check-input" onchange="toggleSelectAll()">
                                    </th>
                                    <th width="50">#</th>
                                    <th>Mã NV</th>
                                    <th>Họ và tên</th>
                                    <th>Giới tính</th>
                                    <th>Ngày sinh</th>
                                    <th>Điện thoại</th>
                                    <th>Email</th>
                                    <th>Chức vụ</th>
                                    <th>Phòng ban</th>
                                    <th>Ngày thử việc</th>
                                    <th>Ngày chính thức</th>
                                    <th>Loại hợp đồng</th>
                                    <th>Trạng thái</th>
                                    <th>Thâm niên</th>
                                    <th width="150">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="employeeTableBody">
                                @forelse($nhanViens as $index => $nhanVien)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input employee-checkbox" 
                                               value="{{ $nhanVien->id }}" onchange="updateSelectAllState()">
                                    </td>
                                    <td>{{ $nhanViens->firstItem() + $index }}</td>
                                    <td>
                                        <span class="fw-bold text-primary">{{ $nhanVien->ma_nhanvien }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($nhanVien->anh_dai_dien)
                                                <img src="{{ Storage::url($nhanVien->anh_dai_dien) }}" 
                                                     alt="Avatar" class="rounded-circle me-2" width="32" height="32">
                                            @else
                                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                     style="width: 32px; height: 32px;">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $nhanVien->ho }} {{ $nhanVien->ten }}</div>
                                                @if($nhanVien->email)
                                                    <small class="text-muted">{{ $nhanVien->email }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $genderText = match($nhanVien->gioi_tinh) {
                                                'nam' => 'Nam',
                                                'nu' => 'Nữ',
                                                default => 'Khác'
                                            };
                                            $genderClass = match($nhanVien->gioi_tinh) {
                                                'nam' => 'text-primary',
                                                'nu' => 'text-danger',
                                                default => 'text-secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-light {{ $genderClass }}">{{ $genderText }}</span>
                                    </td>
                                    <td>
                                        @if($nhanVien->ngay_sinh)
                                            {{ \Carbon\Carbon::parse($nhanVien->ngay_sinh)->format('d/m/Y') }}
                                            <br><small class="text-muted">({{ \Carbon\Carbon::parse($nhanVien->ngay_sinh)->age }} tuổi)</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($nhanVien->so_dien_thoai)
                                            <a href="tel:{{ $nhanVien->so_dien_thoai }}" class="text-decoration-none">
                                                {{ $nhanVien->so_dien_thoai }}
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($nhanVien->email)
                                            <a href="mailto:{{ $nhanVien->email }}" class="text-decoration-none">
                                                {{ $nhanVien->email }}
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($nhanVien->chucVu)
                                            <span class="badge bg-info">{{ $nhanVien->chucVu->ten_chuc_vu }}</span>
                                        @else
                                            <span class="text-muted">Chưa có</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($nhanVien->phongBan)
                                            <span class="badge bg-secondary">{{ $nhanVien->phongBan->ten_phong_ban }}</span>
                                        @else
                                            <span class="text-muted">Chưa có</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($nhanVien->ngay_thu_viec)
                                            {{ \Carbon\Carbon::parse($nhanVien->ngay_thu_viec)->format('d/m/Y') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($nhanVien->ngay_vao_lam)
                                            {{ \Carbon\Carbon::parse($nhanVien->ngay_vao_lam)->format('d/m/Y') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($nhanVien->loai_hop_dong)
                                            <span class="badge bg-dark">{{ $nhanVien->loai_hop_dong }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusConfig = [
                                                'nhan_vien_chinh_thuc' => ['class' => 'success', 'text' => 'Đang làm việc'],
                                                'thu_viec' => ['class' => 'warning', 'text' => 'Thử việc'],
                                                'thai_san' => ['class' => 'info', 'text' => 'Thai sản'],
                                                'nghi_viec' => ['class' => 'danger', 'text' => 'Đã nghỉ việc'],
                                                'khac' => ['class' => 'secondary', 'text' => 'Khác']
                                            ];
                                            $config = $statusConfig[$nhanVien->trang_thai] ?? $statusConfig['khac'];
                                        @endphp
                                        <span class="badge bg-{{ $config['class'] }}">{{ $config['text'] }}</span>
                                    </td>
                                    <td>
                                        @if($nhanVien->ngay_vao_lam)
                                            @php
                                                $startDate = \Carbon\Carbon::parse($nhanVien->ngay_vao_lam);
                                                $now = \Carbon\Carbon::now();
                                                $totalMonths = $startDate->diffInMonths($now);
                                                $years = floor($totalMonths / 12);
                                                $months = $totalMonths % 12;
                                                
                                                $seniority = match(true) {
                                                    $years == 0 && $months > 0 => $months . ' tháng',
                                                    $years > 0 && $months == 0 => $years . ' năm',
                                                    $years > 0 && $months > 0 => $years . ' năm ' . $months . ' tháng',
                                                    default => '< 1 tháng'
                                                };
                                            @endphp
                                            <span class="badge bg-info">{{ $seniority }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('nhan-vien.show', $nhanVien->id) }}" class="btn btn-outline-info btn-sm"
                                               title="Xem chi tiết" data-bs-toggle="tooltip">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('nhan-vien.edit', $nhanVien->id) }}" class="btn btn-outline-warning btn-sm"
                                               title="Chỉnh sửa" data-bs-toggle="tooltip">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger btn-sm delete-btn"
                                                    title="Xóa" data-bs-toggle="tooltip"
                                                    data-id="{{ $nhanVien->id }}"
                                                    data-name="{{ $nhanVien->ho . ' ' . $nhanVien->ten }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="16" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-users fa-3x mb-3 text-muted"></i>
                                            <h5 class="mb-2">Không có nhân viên nào</h5>
                                            <p class="mb-0">Hãy thêm nhân viên đầu tiên của bạn</p>
                                            <a href="{{ route('nhan-vien.create') }}" class="btn btn-primary mt-3">
                                                <i class="fas fa-plus me-1"></i>Thêm nhân viên
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($nhanViens->hasPages())
                <x-pagination :paginator="$nhanViens" />
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Xác nhận</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="confirmModalBody">
                <!-- Dynamic content -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirmActionBtn">Xác nhận</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.alert.custom-alert {
    position: fixed !important;
    top: 20px !important;
    right: 20px !important;
    z-index: 9999 !important;
    max-width: 400px !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.table th {
    border-bottom: 2px solid #dee2e6;
    background-color: #f8f9fa;
    font-weight: 600;
    color: #495057;
    white-space: nowrap;
}

.table td {
    vertical-align: middle;
    border-bottom: 1px solid #e9ecef;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.badge {
    font-size: 0.75em;
}

.btn-group .btn {
    border-radius: 0.25rem;
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }
    
    .badge {
        font-size: 0.65em;
    }
}

.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.form-check-input:indeterminate {
    background-color: #6c757d;
    border-color: #6c757d;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10h8'/%3e%3c/svg%3e");
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    padding: 1rem 1.25rem;
}

.filter-section {
    background: #f8f9fa;
    border-radius: 0.375rem;
    padding: 1rem;
    margin-bottom: 1.5rem;
}
</style>
@endpush

@push('scripts')
<script>
// Global variables
let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
let baseUrl = window.location.origin;

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Employee index page loaded');

    // Setup delete button event listeners
    setupDeleteListeners();

    // Setup filter form
    setupFilterForm();

    // Setup bulk actions
    setupBulkActions();
});

// Setup filter form with AJAX
function setupFilterForm() {
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        // Remove duplicate submit handler
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            applyFilters();
        });

        // Add input event listeners for real-time search
        const searchInput = document.getElementById('search');
        const phongBanSelect = document.getElementById('phong_ban_id');
        const chucVuSelect = document.getElementById('chuc_vu_id');
        const trangThaiSelect = document.getElementById('trang_thai');

        // Debounce search input
        let searchTimeout;
        searchInput?.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(applyFilters, 500);
        });

        // Apply filters on select change
        [phongBanSelect, chucVuSelect, trangThaiSelect].forEach(select => {
            select?.addEventListener('change', applyFilters);
        });
    }
}

// Apply filters with AJAX
function applyFilters() {
    const filterForm = document.getElementById('filterForm');
    if (!filterForm) return;

    const formData = new FormData(filterForm);
    const params = new URLSearchParams();

    for (let [key, value] of formData.entries()) {
        if (value.trim()) {
            params.append(key, value);
        }
    }

    // Show loading
    showLoading();

    // Update URL without reload
    const newUrl = `${baseUrl}/nhan-vien?${params.toString()}`;
    window.history.pushState({}, '', newUrl);

    // Fetch filtered data
    fetch(`${baseUrl}/nhan-vien?${params.toString()}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Update table content
        const tableContainer = document.querySelector('.card-body.p-0');
        tableContainer.innerHTML = data.table;

        // Update pagination
        const paginationContainer = document.querySelector('.card-footer');
        if (paginationContainer) {
            paginationContainer.innerHTML = data.pagination;
        }

        // Update total count
        document.getElementById('totalEmployees').textContent = data.total;

        // Re-setup event listeners
        setupDeleteListeners();
        setupBulkActions();

        hideLoading();
    })
    .catch(error => {
        console.error('Filter error:', error);
        showAlert('Có lỗi xảy ra khi lọc dữ liệu', 'danger');
        hideLoading();
    });
}

// Setup bulk actions
function setupBulkActions() {
    // Checkbox handling for bulk actions
    const selectAllCheckbox = document.getElementById('selectAll');
    const employeeCheckboxes = document.querySelectorAll('.employee-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            employeeCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkDeleteButton();
        });
    }

    employeeCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBulkDeleteButton();
        });
    });

    function updateBulkDeleteButton() {
        const checkedBoxes = document.querySelectorAll('.employee-checkbox:checked');
        if (bulkDeleteBtn) {
            bulkDeleteBtn.disabled = checkedBoxes.length === 0;
        }
    }
}

// Reset filter
function resetFilter() {
    window.location.href = `${baseUrl}/nhan-vien`;
}

// Export employees
function exportEmployees(type) {
    const currentUrl = new URL(window.location);
    const params = currentUrl.searchParams;

    let exportUrl = `${baseUrl}/nhan-vien/export/${type}`;
    if (params.toString()) {
        exportUrl += `?${params.toString()}`;
    }

    window.open(exportUrl, '_blank');
}

// Delete selected employees
function deleteSelectedEmployees() {
    const selectedIds = Array.from(document.querySelectorAll('.employee-checkbox:checked'))
        .map(checkbox => checkbox.value);

    if (selectedIds.length === 0) {
        showAlert('Vui lòng chọn nhân viên cần xóa', 'warning');
        return;
    }

    if (!confirm(`Bạn có chắc chắn muốn xóa ${selectedIds.length} nhân viên đã chọn?`)) {
        return;
    }

    // Implement bulk delete logic here
    showAlert('Tính năng xóa nhiều nhân viên đang được phát triển', 'info');
}

// Utility functions
function showLoading() {
    let loadingElement = document.querySelector('.loading-overlay');
    if (!loadingElement) {
        loadingElement = document.createElement('div');
        loadingElement.className = 'loading-overlay';
        loadingElement.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        `;
        loadingElement.innerHTML = `
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        `;
        document.body.appendChild(loadingElement);
    } else {
        loadingElement.style.display = 'flex';
    }
}

function hideLoading() {
    const loadingElement = document.querySelector('.loading-overlay');
    if (loadingElement) {
        loadingElement.style.display = 'none';
    }
}

function showAlert(message, type = 'info') {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.alert.custom-alert');
    existingAlerts.forEach(alert => alert.remove());

    // Create new alert
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show custom-alert`;
    alertDiv.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 400px;';
    alertDiv.innerHTML = `
        <i class="fas fa-${getAlertIcon(type)} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(alertDiv);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

function getAlertIcon(type) {
    const icons = {
        'success': 'check-circle',
        'danger': 'exclamation-triangle',
        'warning': 'exclamation-circle',
        'info': 'info-circle'
    };
    return icons[type] || 'info-circle';
}
</script>
@endpush