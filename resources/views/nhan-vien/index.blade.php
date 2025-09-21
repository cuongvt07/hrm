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
                </div>
                <div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#employeeModal" onclick="openEmployeeModal()">
                        <i class="fas fa-plus me-2"></i>Thêm nhân viên
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Ultra Compact Filter Section -->
    <div class="row mb-2">
        <div class="col-12">
            <div class="filter-bar">
                <form id="filterForm" class="filter-form">
                    <div class="filter-group">
                        <input type="text" class="form-control" id="search" name="search" placeholder="Tìm kiếm...">
                    </div>
                    <div class="filter-group">
                        <select class="form-select" id="phong_ban_id" name="phong_ban_id">
                            <option value="">Tất cả phòng ban</option>
                            @foreach($phongBans as $phongBan)
                                <option value="{{ $phongBan->id }}">{{ $phongBan->ten_phong_ban }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <select class="form-select" id="chuc_vu_id" name="chuc_vu_id">
                            <option value="">Tất cả chức vụ</option>
                            @foreach($chucVus as $chucVu)
                                <option value="{{ $chucVu->id }}">{{ $chucVu->ten_chuc_vu }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <select class="form-select" id="trang_thai" name="trang_thai">
                            <option value="">Tất cả trạng thái</option>
                            <option value="nhan_vien_chinh_thuc">Đang làm việc</option>
                            <option value="thu_viec">Thử việc</option>
                            <option value="thai_san">Thai sản</option>
                            <option value="nghi_viec">Nghỉ việc</option>
                            <option value="khac">Khác</option>
                        </select>
                    </div>
                    <div class="filter-actions">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-search"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetFilter()">
                            <i class="fas fa-refresh"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteSelectedEmployees()" id="bulkDeleteBtn" disabled>
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </form>
            </div>
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
                    <div class="table-responsive">
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
                                    <th>Email cơ quan</th>
                                    <th>Vị trí công việc</th>
                                    <th>Đơn vị công tác</th>
                                    <th>Ngày thử việc</th>
                                    <th>Ngày chính thức</th>
                                    <th>Loại hợp đồng</th>
                                    <th>Trạng thái</th>
                                    <th>Thâm niên</th>
                                    <th width="120">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="employeeTableBody">
                                @forelse($nhanViens as $index => $nhanVien)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input employee-checkbox" value="{{ $nhanVien->id }}" onchange="updateSelectAllState()">
                                    </td>
                                    <td>{{ $nhanViens->firstItem() + $index }}</td>
                                    <td>
                                        <span class="fw-bold text-primary">{{ $nhanVien->ma_nhanvien }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $nhanVien->ho }} {{ $nhanVien->ten }}</div>
                                    </td>
                                    <td>
                                        @php
                                            $genderText = $nhanVien->gioi_tinh == 'nam' ? 'Nam' : ($nhanVien->gioi_tinh == 'nu' ? 'Nữ' : 'Khác');
                                        @endphp
                                        <span class="badge bg-light text-dark">{{ $genderText }}</span>
                                    </td>
                                    <td>
                                        @if($nhanVien->ngay_sinh)
                                            {{ \Carbon\Carbon::parse($nhanVien->ngay_sinh)->format('d/m/Y') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($nhanVien->so_dien_thoai)
                                            {{ $nhanVien->so_dien_thoai }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($nhanVien->email)
                                            <small class="text-muted">{{ $nhanVien->email }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($nhanVien->chucVu)
                                            <span class="badge bg-info">{{ $nhanVien->chucVu->ten_chuc_vu }}</span>
                                        @else
                                            <span class="text-muted">Chưa phân công</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($nhanVien->phongBan)
                                            <span class="badge bg-light text-dark">{{ $nhanVien->phongBan->ten_phong_ban }}</span>
                                        @else
                                            <span class="text-muted">Chưa phân công</span>
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
                                            <span class="badge bg-secondary">{{ $nhanVien->loai_hop_dong }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = [
                                                'nhan_vien_chinh_thuc' => 'success',
                                                'thu_viec' => 'warning',
                                                'thai_san' => 'info',
                                                'nghi_viec' => 'danger',
                                                'khac' => 'secondary'
                                            ][$nhanVien->trang_thai] ?? 'secondary';
                                            
                                            $statusText = [
                                                'nhan_vien_chinh_thuc' => 'Đang làm việc',
                                                'thu_viec' => 'Thử việc',
                                                'thai_san' => 'Thai sản',
                                                'nghi_viec' => 'Đã nghỉ việc',
                                                'khac' => 'Khác'
                                            ][$nhanVien->trang_thai] ?? 'Khác';
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                                    </td>
                                    <td>
                                        @if($nhanVien->ngay_vao_lam)
                                            @php
                                                $startDate = \Carbon\Carbon::parse($nhanVien->ngay_vao_lam);
                                                $now = \Carbon\Carbon::now();
                                                $totalMonths = $startDate->diffInMonths($now);
                                                $years = floor($totalMonths / 12);
                                                $months = $totalMonths % 12;
                                                
                                                // Round to nearest month if less than 1 year
                                                if ($years == 0 && $months > 0) {
                                                    $seniority = $months . ' tháng';
                                                } else if ($years > 0 && $months == 0) {
                                                    $seniority = $years . ' năm';
                                                } else if ($years > 0 && $months > 0) {
                                                    $seniority = $years . ' năm ' . $months . ' tháng';
                                                } else {
                                                    $seniority = '< 1 tháng';
                                                }
                                            @endphp
                                            <span class="badge bg-info">{{ $seniority }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-info btn-sm" 
                                                    onclick="viewEmployee({{ $nhanVien->id }})" 
                                                    title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-warning btn-sm" 
                                                    onclick="editEmployee({{ $nhanVien->id }})" 
                                                    title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="16" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-users fa-3x mb-3"></i>
                                            <p>Không có nhân viên nào</p>
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

<!-- Employee Modal -->
<div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="employeeModalLabel">Thông tin nhân viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="employeeModalContent">
                    <!-- Content will be loaded via AJAX -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Employee Detail Overlay will be created dynamically -->
@endsection

@push('scripts')
<script>
// Global variables
let currentEmployeeId = null;

// Checkbox functionality
function toggleSelectAll() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const employeeCheckboxes = document.querySelectorAll('.employee-checkbox');
    
    employeeCheckboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
}

function updateSelectAllState() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const employeeCheckboxes = document.querySelectorAll('.employee-checkbox');
    const checkedBoxes = document.querySelectorAll('.employee-checkbox:checked');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    
    if (checkedBoxes.length === 0) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = false;
        bulkDeleteBtn.disabled = true;
    } else if (checkedBoxes.length === employeeCheckboxes.length) {
        selectAllCheckbox.indeterminate = false;
        selectAllCheckbox.checked = true;
        bulkDeleteBtn.disabled = false;
    } else {
        selectAllCheckbox.indeterminate = true;
        bulkDeleteBtn.disabled = false;
    }
}

function getSelectedEmployees() {
    const checkedBoxes = document.querySelectorAll('.employee-checkbox:checked');
    return Array.from(checkedBoxes).map(checkbox => checkbox.value);
}

function deleteSelectedEmployees() {
    const selectedIds = getSelectedEmployees();
    if (selectedIds.length === 0) {
        showAlert('Vui lòng chọn ít nhất một nhân viên để xóa', 'warning');
        return;
    }
    
    if (confirm(`Bạn có chắc chắn muốn xóa ${selectedIds.length} nhân viên đã chọn?`)) {
        // Implement bulk delete
        showAlert('Tính năng xóa hàng loạt sẽ được triển khai', 'info');
    }
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Filter form submission
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        filterEmployees();
    });

    // Search input with debounce
    let searchTimeout;
    document.getElementById('search').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filterEmployees();
        }, 500);
    });

    // Select change events
    ['phong_ban_id', 'chuc_vu_id', 'trang_thai'].forEach(id => {
        document.getElementById(id).addEventListener('change', filterEmployees);
    });
});

// Filter employees
function filterEmployees() {
    const formData = new FormData(document.getElementById('filterForm'));
    const params = new URLSearchParams(formData);
    
    fetch(`{{ route('nhan-vien.index') }}?${params.toString()}`)
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newTableBody = doc.querySelector('#employeeTableBody');
            const newTotal = doc.querySelector('#totalEmployees');
            
            if (newTableBody) {
                document.getElementById('employeeTableBody').innerHTML = newTableBody.innerHTML;
            }
            if (newTotal) {
                document.getElementById('totalEmployees').textContent = newTotal.textContent;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Có lỗi xảy ra khi tải dữ liệu', 'error');
        });
}

// Reset filter
function resetFilter() {
    document.getElementById('filterForm').reset();
    filterEmployees();
}

// Open employee modal for create
function openEmployeeModal() {
    currentEmployeeId = null;
    document.getElementById('employeeModalLabel').textContent = 'Thêm nhân viên mới';
    
    fetch('{{ route("nhan-vien.create") }}')
        .then(response => response.text())
        .then(html => {
            document.getElementById('employeeModalContent').innerHTML = html;
            const modal = new bootstrap.Modal(document.getElementById('employeeModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Có lỗi xảy ra khi tải form', 'error');
        });
}

// Edit employee
function editEmployee(id) {
    currentEmployeeId = id;
    document.getElementById('employeeModalLabel').textContent = 'Chỉnh sửa nhân viên';
    
    // Close detail overlay first if open
    const overlay = document.getElementById('employeeDetailOverlay');
    if (overlay) {
        overlay.style.display = 'none';
    }
    
    fetch(`{{ url('nhan-vien') }}/${id}/edit`)
        .then(response => response.text())
        .then(html => {
            // Create edit overlay container if not exists
            let overlay = document.getElementById('employeeEditOverlay');
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.id = 'employeeEditOverlay';
                document.body.appendChild(overlay);
            }
            overlay.innerHTML = html;
            overlay.style.display = 'flex';
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Có lỗi xảy ra khi tải form', 'error');
        });
}

// View employee details
function viewEmployee(id) {
    fetch(`{{ url('nhan-vien') }}/${id}`)
        .then(response => response.text())
        .then(html => {
            // Create overlay container if not exists
            let overlay = document.getElementById('employeeDetailOverlay');
            if (!overlay) {
                overlay = document.createElement('div');
                overlay.id = 'employeeDetailOverlay';
                document.body.appendChild(overlay);
            }
            overlay.innerHTML = html;
            overlay.style.display = 'flex';
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Có lỗi xảy ra khi tải thông tin', 'error');
        });
}

// Delete employee
function deleteEmployee(id) {
    if (confirm('Bạn có chắc chắn muốn xóa nhân viên này?')) {
        fetch(`{{ url('nhan-vien') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Xóa nhân viên thành công!', 'success');
                filterEmployees(); // Refresh the list
            } else {
                showAlert(data.message || 'Có lỗi xảy ra khi xóa nhân viên', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Có lỗi xảy ra khi xóa nhân viên', 'error');
        });
    }
}

// Export employees
function exportEmployees(format) {
    const formData = new FormData(document.getElementById('filterForm'));
    const params = new URLSearchParams(formData);
    params.append('export', format);
    
    window.open(`{{ route('nhan-vien.index') }}?${params.toString()}`, '_blank');
}

// Show alert
function showAlert(message, type = 'info') {
    // You can implement a toast notification here
    alert(message);
}

// Save employee (called from form)
function saveEmployee(formData) {
    const url = currentEmployeeId 
        ? `{{ url('nhan-vien') }}/${currentEmployeeId}`
        : '{{ route("nhan-vien.store") }}';
    
    const method = currentEmployeeId ? 'PUT' : 'POST';
    
    fetch(url, {
        method: method,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            bootstrap.Modal.getInstance(document.getElementById('employeeModal')).hide();
            filterEmployees(); // Refresh the list
        } else {
            showAlert(data.message || 'Có lỗi xảy ra', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Có lỗi xảy ra khi lưu dữ liệu', 'error');
    });
}
</script>
@endpush

@push('styles')
<style>
.avatar-sm img {
    object-fit: cover;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.table td {
    vertical-align: middle;
}

.btn-group .btn {
    border-radius: 0.375rem;
}

.btn-group .btn:not(:last-child) {
    margin-right: 2px;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.badge {
    font-size: 0.75em;
}

.modal-xl {
    max-width: 90%;
}

@media (max-width: 768px) {
    .modal-xl {
        max-width: 95%;
    }
    
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        margin-bottom: 2px;
        margin-right: 0;
    }
}
</style>
@endpush
