<!-- Employee Edit Overlay -->
<div class="employee-overlay" id="employeeEditOverlay">
    <div class="employee-detail">
        <div class="employee-header">
            <h4 class="employee-title">Chỉnh sửa nhân viên</h4>
            <div class="employee-actions">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="closeEmployeeEdit()">
                    <i class="fas fa-times me-1"></i>Đóng
                </button>
                <button type="button" class="btn btn-success btn-sm" onclick="saveEmployeeForm()">
                    <i class="fas fa-save me-1"></i>Lưu thay đổi
                </button>
            </div>
        </div>
        <div class="employee-content">
                    <form id="employeeForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row g-0">
                            <!-- Left Column - Basic Info (3 parts) -->
                            <div class="col-md-3">
                                <div class="p-4 border-end">
                                    <!-- Avatar Section -->
                                    <div class="text-center mb-4">
                                        <div class="position-relative d-inline-block">
                                            @if($nhanVien->anh_dai_dien)
                                                <img id="avatarPreview" src="{{ asset('storage/' . $nhanVien->anh_dai_dien) }}" 
                                                     alt="Avatar" class="rounded-circle shadow" width="120" height="120" style="object-fit: cover;">
                                            @else
                                                <div id="avatarPreview" class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto shadow" 
                                                     style="width: 120px; height: 120px; font-size: 2.5rem;">
                                                    {{ strtoupper(substr($nhanVien->ten, 0, 1)) }}
                                                </div>
                                            @endif
                                            <label for="anh_dai_dien" class="btn btn-sm btn-outline-primary position-absolute" 
                                                   style="bottom: 0; right: 0; border-radius: 50%; width: 30px; height: 30px; padding: 0;">
                                                <i class="fas fa-camera"></i>
                                            </label>
                                            <input type="file" id="anh_dai_dien" name="anh_dai_dien" class="d-none" accept="image/*" onchange="previewAvatar(this)">
                                        </div>
                                        <h4 class="mt-3 mb-1">{{ $nhanVien->ho }} {{ $nhanVien->ten }}</h4>
                                        <p class="text-muted mb-0">{{ $nhanVien->ma_nhanvien }}</p>
                                    </div>

                                    <!-- Basic Information -->
                                    <div class="row align-items-center mb-3">
                                        <div class="col-4">
                                            <small class="text-muted">Email</small>
                                        </div>
                                        <div class="col-8">
                                            <input type="email" class="form-control form-control-sm" name="email" value="{{ $nhanVien->email }}">
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-4">
                                            <small class="text-muted">Điện thoại</small>
                                        </div>
                                        <div class="col-8">
                                            <input type="tel" class="form-control form-control-sm" name="so_dien_thoai" value="{{ $nhanVien->so_dien_thoai }}">
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-4">
                                            <small class="text-muted">Ngày sinh</small>
                                        </div>
                                        <div class="col-8">
                                            <input type="date" class="form-control form-control-sm" name="ngay_sinh" value="{{ $nhanVien->ngay_sinh }}">
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-4">
                                            <small class="text-muted">Giới tính</small>
                                        </div>
                                        <div class="col-8">
                                            <select class="form-select form-select-sm" name="gioi_tinh">
                                                <option value="nam" {{ $nhanVien->gioi_tinh == 'nam' ? 'selected' : '' }}>Nam</option>
                                                <option value="nu" {{ $nhanVien->gioi_tinh == 'nu' ? 'selected' : '' }}>Nữ</option>
                                                <option value="khac" {{ $nhanVien->gioi_tinh == 'khac' ? 'selected' : '' }}>Khác</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-4">
                                            <small class="text-muted">Phòng ban</small>
                                        </div>
                                        <div class="col-8">
                                            <select class="form-select form-select-sm" name="phong_ban_id">
                                                <option value="">Chọn phòng ban</option>
                                                @foreach($phongBans as $phongBan)
                                                    <option value="{{ $phongBan->id }}" {{ $nhanVien->phong_ban_id == $phongBan->id ? 'selected' : '' }}>
                                                        {{ $phongBan->ten_phong_ban }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-4">
                                            <small class="text-muted">Chức vụ</small>
                                        </div>
                                        <div class="col-8">
                                            <select class="form-select form-select-sm" name="chuc_vu_id">
                                                <option value="">Chọn chức vụ</option>
                                                @foreach($chucVus as $chucVu)
                                                    <option value="{{ $chucVu->id }}" {{ $nhanVien->chuc_vu_id == $chucVu->id ? 'selected' : '' }}>
                                                        {{ $chucVu->ten_chuc_vu }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mb-3">
                                        <div class="col-4">
                                            <small class="text-muted">Trạng thái</small>
                                        </div>
                                        <div class="col-8">
                                            <select class="form-select form-select-sm" name="trang_thai">
                                                <option value="nhan_vien_chinh_thuc" {{ $nhanVien->trang_thai == 'nhan_vien_chinh_thuc' ? 'selected' : '' }}>Đang làm việc</option>
                                                <option value="thu_viec" {{ $nhanVien->trang_thai == 'thu_viec' ? 'selected' : '' }}>Thử việc</option>
                                                <option value="thai_san" {{ $nhanVien->trang_thai == 'thai_san' ? 'selected' : '' }}>Thai sản</option>
                                                <option value="nghi_viec" {{ $nhanVien->trang_thai == 'nghi_viec' ? 'selected' : '' }}>Đã nghỉ việc</option>
                                                <option value="khac" {{ $nhanVien->trang_thai == 'khac' ? 'selected' : '' }}>Khác</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column - Tab Navigation (9 parts) -->
                            <div class="col-md-9">
                                <div class="p-4">
                                    <!-- Tab Navigation -->
                                    <ul class="nav nav-tabs mb-4" id="employeeTabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab">
                                                <i class="fas fa-user me-2"></i>Thông tin cơ bản
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="work-tab" data-bs-toggle="tab" data-bs-target="#work" type="button" role="tab">
                                                <i class="fas fa-briefcase me-2"></i>Công việc
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="contract-tab" data-bs-toggle="tab" data-bs-target="#contract" type="button" role="tab">
                                                <i class="fas fa-file-contract me-2"></i>Hợp đồng
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="family-tab" data-bs-toggle="tab" data-bs-target="#family" type="button" role="tab">
                                                <i class="fas fa-users me-2"></i>Gia đình
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab">
                                                <i class="fas fa-file-alt me-2"></i>Tài liệu
                                            </button>
                                        </li>
                                    </ul>

                                    <!-- Tab Content -->
                                    <div class="tab-content" id="employeeTabContent">
                                        <!-- Basic Information Tab -->
                                        <div class="tab-pane fade show active" id="basic" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6 class="text-muted mb-3">Thông tin cá nhân</h6>
                                                    <div class="mb-3">
                                                        <label class="form-label">Họ</label>
                                                        <input type="text" class="form-control" name="ho" value="{{ $nhanVien->ho }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Tên</label>
                                                        <input type="text" class="form-control" name="ten" value="{{ $nhanVien->ten }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Mã nhân viên</label>
                                                        <input type="text" class="form-control" name="ma_nhanvien" value="{{ $nhanVien->ma_nhanvien }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Email</label>
                                                        <input type="email" class="form-control" name="email" value="{{ $nhanVien->email }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Điện thoại</label>
                                                        <input type="tel" class="form-control" name="so_dien_thoai" value="{{ $nhanVien->so_dien_thoai }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Ngày sinh</label>
                                                        <input type="date" class="form-control" name="ngay_sinh" value="{{ $nhanVien->ngay_sinh }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="text-muted mb-3">Thông tin liên hệ</h6>
                                                    <div class="mb-3">
                                                        <label class="form-label">Địa chỉ</label>
                                                        <textarea class="form-control" name="dia_chi" rows="3">{{ $nhanVien->dia_chi }}</textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">CMND/CCCD</label>
                                                        <input type="text" class="form-control" name="so_cmnd" value="{{ $nhanVien->so_cmnd }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Ngày cấp</label>
                                                        <input type="date" class="form-control" name="ngay_cap_cmnd" value="{{ $nhanVien->ngay_cap_cmnd }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Nơi cấp</label>
                                                        <input type="text" class="form-control" name="noi_cap_cmnd" value="{{ $nhanVien->noi_cap_cmnd }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Work Information Tab -->
                                        <div class="tab-pane fade" id="work" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6 class="text-muted mb-3">Thông tin công việc</h6>
                                                    <div class="mb-3">
                                                        <label class="form-label">Phòng ban</label>
                                                        <select class="form-select" name="phong_ban_id">
                                                            <option value="">Chọn phòng ban</option>
                                                            @foreach($phongBans as $phongBan)
                                                                <option value="{{ $phongBan->id }}" {{ $nhanVien->phong_ban_id == $phongBan->id ? 'selected' : '' }}>
                                                                    {{ $phongBan->ten_phong_ban }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Chức vụ</label>
                                                        <select class="form-select" name="chuc_vu_id">
                                                            <option value="">Chọn chức vụ</option>
                                                            @foreach($chucVus as $chucVu)
                                                                <option value="{{ $chucVu->id }}" {{ $nhanVien->chuc_vu_id == $chucVu->id ? 'selected' : '' }}>
                                                                    {{ $chucVu->ten_chuc_vu }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Trạng thái</label>
                                                        <select class="form-select" name="trang_thai">
                                                            <option value="nhan_vien_chinh_thuc" {{ $nhanVien->trang_thai == 'nhan_vien_chinh_thuc' ? 'selected' : '' }}>Đang làm việc</option>
                                                            <option value="thu_viec" {{ $nhanVien->trang_thai == 'thu_viec' ? 'selected' : '' }}>Thử việc</option>
                                                            <option value="thai_san" {{ $nhanVien->trang_thai == 'thai_san' ? 'selected' : '' }}>Thai sản</option>
                                                            <option value="nghi_viec" {{ $nhanVien->trang_thai == 'nghi_viec' ? 'selected' : '' }}>Đã nghỉ việc</option>
                                                            <option value="khac" {{ $nhanVien->trang_thai == 'khac' ? 'selected' : '' }}>Khác</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="text-muted mb-3">Ngày tham gia</h6>
                                                    <div class="mb-3">
                                                        <label class="form-label">Ngày thử việc</label>
                                                        <input type="date" class="form-control" name="ngay_thu_viec" value="{{ $nhanVien->ngay_thu_viec }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Ngày vào làm chính thức</label>
                                                        <input type="date" class="form-control" name="ngay_vao_lam" value="{{ $nhanVien->ngay_vao_lam }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Contract Information Tab -->
                                        <div class="tab-pane fade" id="contract" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6 class="text-muted mb-3">Thông tin hợp đồng</h6>
                                                    <div class="mb-3">
                                                        <label class="form-label">Loại hợp đồng</label>
                                                        <input type="text" class="form-control" name="loai_hop_dong" value="{{ $nhanVien->loai_hop_dong }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Ngày bắt đầu hợp đồng</label>
                                                        <input type="date" class="form-control" name="ngay_bat_dau_hop_dong" value="{{ $nhanVien->ngay_bat_dau_hop_dong }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Ngày kết thúc hợp đồng</label>
                                                        <input type="date" class="form-control" name="ngay_ket_thuc_hop_dong" value="{{ $nhanVien->ngay_ket_thuc_hop_dong }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="text-muted mb-3">Thông tin lương</h6>
                                                    <div class="mb-3">
                                                        <label class="form-label">Lương cơ bản (VNĐ)</label>
                                                        <input type="number" class="form-control" name="luong_co_ban" value="{{ $nhanVien->luong_co_ban }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Family Information Tab -->
                                        <div class="tab-pane fade" id="family" role="tabpanel">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h6 class="text-muted mb-3">Thông tin gia đình</h6>
                                                    <p class="text-muted">Thông tin gia đình sẽ được cập nhật trong module quản lý gia đình.</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Documents Tab -->
                                        <div class="tab-pane fade" id="documents" role="tabpanel">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h6 class="text-muted mb-3">Tài liệu</h6>
                                                    <p class="text-muted">Tài liệu sẽ được cập nhật trong module quản lý tài liệu.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
        </div>
    </div>
</div>

<script>
function closeModal() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('employeeModal'));
    if (modal) {
        modal.hide();
    }
}

function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatarPreview');
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                preview.innerHTML = `<img src="${e.target.result}" alt="Avatar" class="rounded-circle shadow" width="120" height="120" style="object-fit: cover;">`;
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function saveEmployeeForm() {
    const form = document.getElementById('employeeForm');
    const formData = new FormData(form);
    
    // Show loading
    const saveBtn = document.querySelector('button[onclick="saveEmployeeForm()"]');
    const originalText = saveBtn.innerHTML;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Đang lưu...';
    saveBtn.disabled = true;
    
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            closeModal();
            // Refresh the employee list
            if (typeof filterEmployees === 'function') {
                filterEmployees();
            }
        } else {
            showAlert(data.message || 'Có lỗi xảy ra', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Có lỗi xảy ra khi lưu dữ liệu', 'error');
    })
    .finally(() => {
        // Reset button
        saveBtn.innerHTML = originalText;
        saveBtn.disabled = false;
    });
}

// Close edit overlay
function closeEmployeeEdit() {
    const overlay = document.getElementById('employeeEditOverlay');
    if (overlay) {
        overlay.style.display = 'none';
    }
}

// Show alert function
function showAlert(message, type = 'info') {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: type === 'success' ? 'Thành công!' : 'Lỗi!',
            text: message,
            icon: type,
            confirmButtonText: 'OK'
        });
    } else {
        alert(message);
    }
}

// Close on overlay click
document.getElementById('employeeEditOverlay').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEmployeeEdit();
    }
});

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeEmployeeEdit();
    }
});
</script>