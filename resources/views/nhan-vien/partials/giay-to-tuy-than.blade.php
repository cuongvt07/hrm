<!-- Giấy tờ tùy thân Tab -->
<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Loại giấy tờ</th>
                <th>Số giấy tờ</th>
                <th>Ngày cấp</th>
                <th>Nơi cấp</th>
                <th>Ngày hết hạn</th>
                <th>Ghi chú</th>
                <th style="width: 120px;">Thao tác</th>
            </tr>
        </thead>
        <tbody id="giaytoTableBody">
            <!-- Render bằng JS -->
        </tbody>
    </table>
</div>

@if(Route::currentRouteName() == 'nhan-vien.edit')
<!-- Form thêm/sửa giấy tờ tùy thân -->
<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Thêm/Sửa giấy tờ tùy thân</span>
        <div>
            <button type="button" class="btn btn-sm btn-success" id="saveAllBtn">
                <i class="fas fa-save"></i> Lưu tất cả thay đổi
            </button>
            <button type="button" class="btn btn-sm btn-info" id="clearAllBtn">
                <i class="fas fa-undo"></i> Reset về dữ liệu gốc
            </button>
        </div>
    </div>
    <div class="card-body">
        <form id="giayToForm" onsubmit="return false;">
            <input type="hidden" id="giayto_id" name="giayto_id">
            <input type="hidden" id="nhan_vien_id" name="nhan_vien_id" value="{{ $nhanVien->id }}">
            <div class="row g-2">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="loai_giay_to" id="loai_giay_to" placeholder="Loại giấy tờ" required>
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" name="so_giay_to" id="so_giay_to" placeholder="Số giấy tờ" required>
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" name="ngay_cap" id="ngay_cap">
                </div>
                <div class="col-md-2">
                    <input type="text" class="form-control" name="noi_cap" id="noi_cap" placeholder="Nơi cấp">
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" name="ngay_het_han" id="ngay_het_han">
                </div>
                <div class="col-md-1">
                    <input type="text" class="form-control" name="ghi_chu" id="ghi_chu" placeholder="Ghi chú">
                </div>
            </div>
            <div class="mt-3 text-end">
                <button type="button" class="btn btn-success" id="addGiayToBtn">
                    <i class="fas fa-plus"></i> Thêm giấy tờ
                </button>
                <button type="button" class="btn btn-secondary" id="giaytoResetBtn">
                    <i class="fas fa-eraser"></i> Reset form
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Loading overlay -->
<div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: white; text-align: center;">
        <i class="fas fa-spinner fa-spin fa-3x"></i>
        <div class="mt-2">Đang xử lý...</div>
    </div>
</div>
@endif

@php
$originalGiayToData = $nhanVien->giayToTuyThan->map(function($giayTo) {
    return [
        'id' => $giayTo->id,
        'loai_giay_to' => $giayTo->loai_giay_to,
        'so_giay_to' => $giayTo->so_giay_to,
        'ngay_cap' => $giayTo->ngay_cap ? $giayTo->ngay_cap->format('Y-m-d') : '',
        'noi_cap' => $giayTo->noi_cap,
        'ngay_het_han' => $giayTo->ngay_het_han ? $giayTo->ngay_het_han->format('Y-m-d') : '',
        'ghi_chu' => $giayTo->ghi_chu,
        'is_temp' => false,
        'is_deleted' => false
    ];
})->toArray();
@endphp

@push('styles')
<style>
.temp-row {
    background-color: #fff3cd !important;
}
.deleted-row {
    background-color: #f8d7da !important;
    opacity: 0.7;
}
.badge-new {
    font-size: 0.7em;
}
.badge-deleted {
    font-size: 0.7em;
}
</style>
@endpush
<script>
// Dữ liệu gốc từ database (không thay đổi)
const originalGiayToData = @json($originalGiayToData);

// Mảng lưu giấy tờ hiện tại (bao gồm cả dữ liệu gốc và tạm thời)
let giayToList = [...originalGiayToData];

// CSRF Token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

function showLoading(show = true) {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.style.display = show ? 'block' : 'none';
    }
}

function showNotification(message, type = 'success') {
    // Tạo notification toast
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 10000; min-width: 300px;';
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(toast);
    
    // Auto hide sau 3 giây
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

function renderGiayToTable() {
    const tbody = document.querySelector('#giaytoTableBody');
    if (!tbody) return;
    
    let html = '';
    const visibleItems = giayToList.filter(item => !item.is_deleted);
    
    if (visibleItems.length === 0) {
        html = `<tr><td colspan="7" class="text-center text-muted">Chưa có giấy tờ tùy thân</td></tr>`;
    } else {
        visibleItems.forEach((item, idx) => {
            const actualIdx = giayToList.indexOf(item);
            let rowClass = '';
            let badges = '';
            
            if (item.is_temp) {
                rowClass = 'temp-row';
                badges += '<span class="badge bg-success text-white ms-1 badge-new">Mới</span>';
            }
            
            html += `<tr class="${rowClass}" data-idx="${actualIdx}">
                <td>${item.loai_giay_to}</td>
                <td>${item.so_giay_to}</td>
                <td>${item.ngay_cap ? new Date(item.ngay_cap).toLocaleDateString('vi-VN') : '-'}</td>
                <td>${item.noi_cap || '-'}</td>
                <td>${item.ngay_het_han ? new Date(item.ngay_het_han).toLocaleDateString('vi-VN') : '-'}</td>
                <td>${item.ghi_chu || '-'}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-warning edit-giayto-btn" data-idx="${actualIdx}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger delete-giayto-btn" data-idx="${actualIdx}">
                        <i class="fas fa-trash"></i>
                    </button>
                    ${badges}
                </td>
            </tr>`;
        });
    }
    tbody.innerHTML = html;
}

function resetForm() {
    const form = document.getElementById('giayToForm');
    const addBtn = document.getElementById('addGiayToBtn');
    
    if (form && addBtn) {
        form.reset();
        form.querySelector('#giayto_id').value = '';
        form.querySelector('#nhan_vien_id').value = {{ $nhanVien->id }};
        form.removeAttribute('data-edit-idx');
        addBtn.innerHTML = '<i class="fas fa-plus"></i> Thêm giấy tờ';
    }
}

function resetToOriginalData() {
    giayToList = [...originalGiayToData];
    renderGiayToTable();
    resetForm();
    showNotification('Đã reset về dữ liệu gốc', 'info');
}

// AJAX Functions
async function saveGiayToItem(item, isUpdate = false) {
    try {
        const url = isUpdate ? `/admin/giay-to-tuy-than/${item.id}` : '/admin/giay-to-tuy-than';
        const method = isUpdate ? 'PUT' : 'POST';
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                nhan_vien_id: {{ $nhanVien->id }},
                loai_giay_to: item.loai_giay_to,
                so_giay_to: item.so_giay_to,
                ngay_cap: item.ngay_cap,
                noi_cap: item.noi_cap,
                ngay_het_han: item.ngay_het_han,
                ghi_chu: item.ghi_chu
            })
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error saving giay to:', error);
        throw error;
    }
}

async function deleteGiayToItem(id) {
    try {
        const response = await fetch(`/admin/giay-to-tuy-than/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return await response.json();
    } catch (error) {
        console.error('Error deleting giay to:', error);
        throw error;
    }
}

async function saveAllChanges() {
    showLoading(true);
    
    try {
        const tempItems = giayToList.filter(item => item.is_temp && !item.is_deleted);
        const deletedItems = giayToList.filter(item => !item.is_temp && item.is_deleted);
        
        // Lưu items mới
        for (const item of tempItems) {
            const result = await saveGiayToItem(item);
            // Cập nhật item với ID mới từ server
            const index = giayToList.indexOf(item);
            giayToList[index] = {
                ...result.data,
                is_temp: false,
                is_deleted: false
            };
        }
        
        // Xóa items đã đánh dấu deleted
        for (const item of deletedItems) {
            await deleteGiayToItem(item.id);
            // Loại bỏ khỏi array
            const index = giayToList.indexOf(item);
            giayToList.splice(index, 1);
        }
        
        renderGiayToTable();
        showNotification('Lưu thành công tất cả thay đổi!', 'success');
        
    } catch (error) {
        console.error('Error saving all changes:', error);
        showNotification('Có lỗi xảy ra khi lưu dữ liệu!', 'danger');
    } finally {
        showLoading(false);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    renderGiayToTable();

    const form = document.getElementById('giayToForm');
    const addBtn = document.getElementById('addGiayToBtn');
    const resetBtn = document.getElementById('giaytoResetBtn');
    const clearAllBtn = document.getElementById('clearAllBtn');
    const saveAllBtn = document.getElementById('saveAllBtn');
    const tbody = document.querySelector('#giaytoTableBody');

    if (form && addBtn && resetBtn && tbody) {
        // Thêm / Sửa giấy tờ (chỉ lưu vào mảng, chưa gọi API)
        addBtn.addEventListener('click', function() {
            const idx = form.hasAttribute('data-edit-idx') ? form.getAttribute('data-edit-idx') : null;
            const loai_giay_to = form.loai_giay_to.value.trim();
            const so_giay_to = form.so_giay_to.value.trim();

            if (!loai_giay_to || !so_giay_to) {
                showNotification('Vui lòng nhập loại giấy tờ và số giấy tờ!', 'warning');
                return;
            }

            // Kiểm tra trùng số giấy tờ (trừ trường hợp đang sửa)
            const isDuplicate = giayToList.some((item, itemIdx) => {
                return item.so_giay_to === so_giay_to && itemIdx != idx && !item.is_deleted;
            });

            if (isDuplicate) {
                showNotification('Số giấy tờ đã tồn tại!', 'warning');
                return;
            }

            const obj = {
                id: form.giayto_id.value || null,
                loai_giay_to,
                so_giay_to,
                ngay_cap: form.ngay_cap.value,
                noi_cap: form.noi_cap.value,
                ngay_het_han: form.ngay_het_han.value,
                ghi_chu: form.ghi_chu.value,
                is_temp: !form.giayto_id.value,
                is_deleted: false
            };

            if (idx !== null && idx !== undefined && idx !== '') {
                // Cập nhật item đang sửa
                giayToList[parseInt(idx)] = obj;
                showNotification('Đã cập nhật giấy tờ. Nhấn "Lưu tất cả thay đổi" để lưu vào database.', 'info');
            } else {
                // Thêm mới
                giayToList.push(obj);
                showNotification('Đã thêm giấy tờ. Nhấn "Lưu tất cả thay đổi" để lưu vào database.', 'info');
            }

            renderGiayToTable();
            resetForm();
        });

        // Reset form
        resetBtn.addEventListener('click', function() {
            resetForm();
        });

        // Reset về dữ liệu gốc
        if (clearAllBtn) {
            clearAllBtn.addEventListener('click', function() {
                if (confirm('Bạn có chắc chắn muốn xóa tất cả thay đổi và reset về dữ liệu gốc?')) {
                    resetToOriginalData();
                }
            });
        }

        // Lưu tất cả thay đổi vào database
        if (saveAllBtn) {
            saveAllBtn.addEventListener('click', function() {
                const hasChanges = giayToList.some(item => item.is_temp || item.is_deleted);
                
                if (!hasChanges) {
                    showNotification('Không có thay đổi nào để lưu!', 'info');
                    return;
                }
                
                if (confirm('Bạn có chắc chắn muốn lưu tất cả thay đổi vào database?')) {
                    saveAllChanges();
                }
            });
        }

        // Sửa / Xóa item
        tbody.addEventListener('click', function(e) {
            if (e.target.closest('.edit-giayto-btn')) {
                const btn = e.target.closest('.edit-giayto-btn');
                const idx = btn.getAttribute('data-idx');
                const item = giayToList[idx];
                
                form.loai_giay_to.value = item.loai_giay_to;
                form.so_giay_to.value = item.so_giay_to;
                form.ngay_cap.value = item.ngay_cap || '';
                form.noi_cap.value = item.noi_cap || '';
                form.ngay_het_han.value = item.ngay_het_han || '';
                form.ghi_chu.value = item.ghi_chu || '';
                form.giayto_id.value = item.id || '';
                form.setAttribute('data-edit-idx', idx);
                addBtn.innerHTML = '<i class="fas fa-save"></i> Cập nhật giấy tờ';
                
                // Scroll to form
                form.scrollIntoView({ behavior: 'smooth' });
            }

            if (e.target.closest('.delete-giayto-btn')) {
                const btn = e.target.closest('.delete-giayto-btn');
                const idx = btn.getAttribute('data-idx');
                const item = giayToList[idx];
                
                let message;
                if (item.is_temp) {
                    message = 'Bạn có chắc chắn muốn xóa giấy tờ này?';
                } else {
                    message = 'Giấy tờ sẽ được đánh dấu xóa và sẽ bị xóa khỏi database khi bạn nhấn "Lưu tất cả thay đổi". Bạn có chắc chắn?';
                }
                
                if (confirm(message)) {
                    if (item.is_temp) {
                        // Xóa ngay khỏi mảng nếu là item tạm thời
                        giayToList.splice(idx, 1);
                        showNotification('Đã xóa giấy tờ tạm thời!', 'success');
                    } else {
                        // Đánh dấu xóa nếu là item từ database
                        giayToList[idx].is_deleted = true;
                        showNotification('Đã đánh dấu xóa. Nhấn "Lưu tất cả thay đổi" để xóa khỏi database.', 'warning');
                    }
                    
                    renderGiayToTable();
                    
                    // Reset form nếu đang sửa item bị xóa
                    if (form.getAttribute('data-edit-idx') == idx) {
                        resetForm();
                    }
                }
            }
        });
    }
});
</script>