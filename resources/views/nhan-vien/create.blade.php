@extends('layouts.app')

@section('title', 'Thêm nhân viên mới')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Thêm nhân viên mới</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('nhan-vien.index') }}">Nhân viên</a></li>
                            <li class="breadcrumb-item active">Thêm mới</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('nhan-vien.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Quay lại danh sách
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Employee Create Form -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('nhan-vien.store') }}" method="POST" enctype="multipart/form-data" id="employeeForm">
                        @csrf
                        <div class="row g-0">
                            <!-- Left Column -->
                            <div class="col-md-3">
                                <div class="p-4 border-end">
                                    <!-- Avatar Section -->
                                    <div class="text-center mb-4">
                                        <div class="position-relative d-inline-block">
                                            <div id="avatarPreview" class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto shadow"
                                                 style="width: 120px; height: 120px; font-size: 2.5rem;">
                                            </div>
                                            <label for="anh_dai_dien" class="btn btn-sm btn-outline-primary position-absolute" style="bottom: 0; right: 0;">
                                                <i class="fas fa-camera"></i>
                                            </label>
                                            <input type="file" id="anh_dai_dien" name="anh_dai_dien" class="d-none" accept="image/*" onchange="previewAvatar(this)">
                                        </div>
                                    </div>

                                    <!-- Basic Info Fields -->
                                    <div class="mb-3">
                                        <label for="ma_nhanvien" class="form-label">Mã nhân viên <span class="text-danger">*</span></label>
                                        <input type="text" 
                                            class="form-control" 
                                            id="ma_nhanvien" 
                                            name="ma_nhanvien" 
                                            value="{{ old('ma_nhanvien', $nextCode) }}" 
                                            required readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email">
                                    </div>

                                    <div class="mb-3">
                                        <label for="so_dien_thoai" class="form-label">Điện thoại</label>
                                        <input type="text" class="form-control" id="so_dien_thoai" name="so_dien_thoai">
                                    </div>

                                    <div class="mb-3">
                                        <label for="ngay_sinh" class="form-label">Ngày sinh<span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="ngay_sinh" name="ngay_sinh">
                                    </div>

                                    <div class="mb-3">
                                        <label for="gioi_tinh" class="form-label">Giới tính</label>
                                        <select class="form-select" id="gioi_tinh" name="gioi_tinh">
                                            <option value="">Chọn giới tính</option>
                                            <option value="nam">Nam</option>
                                            <option value="nu">Nữ</option>
                                            <option value="khac">Khác</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-9">
                                <div class="p-4">
                                    <!-- Tabs -->
                                    <ul class="nav nav-tabs mb-4" id="employeeTabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab">Thông tin cơ bản</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="work-tab" data-bs-toggle="tab" data-bs-target="#work" type="button" role="tab">Thông tin công việc</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab">Thông tin liên hệ</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="family-tab" data-bs-toggle="tab" data-bs-target="#family" type="button" role="tab">Gia đình</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="giayto-tab" data-bs-toggle="tab" data-bs-target="#giayto" type="button" role="tab">Giấy tờ tùy thân</button>
                                        </li>
                                    </ul>

                                    <div class="tab-content" id="employeeTabContent">
                                        <!-- Basic Info Tab -->
                                        <div class="tab-pane fade show active" id="basic" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="card border-0 bg-light mb-4">
                                                        <div class="card-header bg-primary text-white">
                                                            <h5 class="mb-0"><i class="fas fa-user me-2"></i>Thông tin cơ bản</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="mb-3">
                                                                <label for="ho" class="form-label">Họ <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" id="ho" name="ho" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="ten" class="form-label">Tên <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" id="ten" name="ten" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="tinh_trang_hon_nhan" class="form-label">Tình trạng hôn nhân</label>
                                                                <select class="form-select" id="tinh_trang_hon_nhan" name="tinh_trang_hon_nhan">
                                                                    <option value="">Chọn tình trạng</option>
                                                                    <option value="doc_than">Độc thân</option>
                                                                    <option value="da_ket_hon">Đã kết hôn</option>
                                                                    <option value="ly_hon">Ly hôn</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card border-0 bg-light mb-4">
                                                        <div class="card-header bg-info text-white">
                                                            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin bổ sung</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="mb-3">
                                                                <label for="quoc_tich" class="form-label">Quốc tịch</label>
                                                                <input type="text" class="form-control" id="quoc_tich" name="quoc_tich" value="Việt Nam">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="dan_toc" class="form-label">Dân tộc</label>
                                                                <input type="text" class="form-control" id="dan_toc" name="dan_toc" value="Kinh">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="ton_giao" class="form-label">Tôn giáo</label>
                                                                <input type="text" class="form-control" id="ton_giao" name="ton_giao">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="dia_chi" class="form-label">Địa chỉ</label>
                                                                <textarea class="form-control" id="dia_chi" name="dia_chi" rows="2"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Work Info Tab -->
                                        <div class="tab-pane fade" id="work" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="card border-0 bg-light mb-4">
                                                        <div class="card-header bg-success text-white">
                                                            <h5 class="mb-0"><i class="fas fa-briefcase me-2"></i>Thông tin công việc</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="mb-3">
                                                                <label for="phong_ban_id" class="form-label">Phòng ban<span class="text-danger">*</span></label>
                                                                <select class="form-select" id="phong_ban_id" name="phong_ban_id">
                                                                    <option value="">Chọn phòng ban</option>
                                                                    @foreach($phongBans as $phongBan)
                                                                        <option value="{{ $phongBan->id }}">{{ $phongBan->ten_phong_ban }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="chuc_vu_id" class="form-label">Chức vụ<span class="text-danger">*</span></label>
                                                                <select class="form-select" id="chuc_vu_id" name="chuc_vu_id">
                                                                    <option value="">Chọn chức vụ</option>
                                                                    @foreach($chucVus as $chucVu)
                                                                        <option value="{{ $chucVu->id }}">{{ $chucVu->ten_chuc_vu }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="ngay_vao_lam" class="form-label">Ngày vào làm</label>
                                                                <input type="date" class="form-control" id="ngay_vao_lam" name="ngay_vao_lam">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="ngay_thu_viec" class="form-label">Ngày thử việc</label>
                                                                <input type="date" class="form-control" id="ngay_thu_viec" name="ngay_thu_viec">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card border-0 bg-light mb-4">
                                                        <div class="card-header bg-secondary text-white">
                                                            <h5 class="mb-0"><i class="fas fa-file-contract me-2"></i>Trạng thái &amp; Hợp đồng</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="mb-3">
                                                                <label for="trang_thai" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                                                <select class="form-select" id="trang_thai" name="trang_thai" required>
                                                                    <option value="">Chọn trạng thái</option>
                                                                    <option value="thu_viec">Thử việc</option>
                                                                    <option value="nhan_vien_chinh_thuc">Nhân viên chính thức</option>
                                                                    <option value="thai_san">Thai sản</option>
                                                                    <option value="nghi_viec">Nghỉ việc</option>
                                                                    <option value="khac">Khác</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Thông tin lương -->
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="card border-0 bg-light mb-4">
                                                        <div class="card-header bg-warning text-dark">
                                                            <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Thông tin lương</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-3">
                                                                    <div class="mb-3">
                                                                        <label for="luong_co_ban" class="form-label">Lương cơ bản</label>
                                                                        <input type="number" step="0.01" class="form-control" id="luong_co_ban" name="luong_co_ban" value="{{ old('luong_co_ban') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="mb-3">
                                                                        <label for="so_tai_khoan" class="form-label">Số tài khoản ngân hàng</label>
                                                                        <input type="text" class="form-control" id="so_tai_khoan" name="so_tai_khoan" value="{{ old('so_tai_khoan') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="mb-3">
                                                                        <label for="ten_ngan_hang" class="form-label">Tên ngân hàng</label>
                                                                        <input type="text" class="form-control" id="ten_ngan_hang" name="ten_ngan_hang" value="{{ old('ten_ngan_hang') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="mb-3">
                                                                        <label for="chi_nhanh_ngan_hang" class="form-label">Chi nhánh ngân hàng</label>
                                                                        <input type="text" class="form-control" id="chi_nhanh_ngan_hang" name="chi_nhanh_ngan_hang" value="{{ old('chi_nhanh_ngan_hang') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Contact Tab -->
                                        <div class="tab-pane fade" id="contact" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="card border-0 bg-light mb-4">
                                                        <div class="card-header bg-primary text-white">
                                                            <h5 class="mb-0"><i class="fas fa-phone me-2"></i>Số điện thoại</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="mb-3">
                                                                <label for="dien_thoai_di_dong" class="form-label">Điện thoại di động</label>
                                                                <input type="text" class="form-control" id="dien_thoai_di_dong" name="dien_thoai_di_dong">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="dien_thoai_co_quan" class="form-label">Điện thoại cơ quan</label>
                                                                <input type="text" class="form-control" id="dien_thoai_co_quan" name="dien_thoai_co_quan">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="dien_thoai_nha_rieng" class="form-label">Điện thoại nhà riêng</label>
                                                                <input type="text" class="form-control" id="dien_thoai_nha_rieng" name="dien_thoai_nha_rieng">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="dien_thoai_khac" class="form-label">Điện thoại khác</label>
                                                                <input type="text" class="form-control" id="dien_thoai_khac" name="dien_thoai_khac">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card border-0 bg-light mb-4">
                                                        <div class="card-header bg-info text-white">
                                                            <h5 class="mb-0"><i class="fas fa-envelope me-2"></i>Email</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="mb-3">
                                                                <label for="email_co_quan" class="form-label">Email cơ quan</label>
                                                                <input type="email" class="form-control" id="email_co_quan" name="email_co_quan">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="email_ca_nhan" class="form-label">Email cá nhân</label>
                                                                <input type="email" class="form-control" id="email_ca_nhan" name="email_ca_nhan">
                                                            </div>
                                                            <div class="card-header bg-secondary text-white mt-3">
                                                                <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Địa chỉ</h5>
                                                            </div>
                                                            <div class="mb-3 mt-2">
                                                                <label for="dia_chi_thuong_tru" class="form-label">Địa chỉ thường trú</label>
                                                                <textarea class="form-control" id="dia_chi_thuong_tru" name="dia_chi_thuong_tru" rows="2"></textarea>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="dia_chi_hien_tai" class="form-label">Địa chỉ hiện tại</label>
                                                                <textarea class="form-control" id="dia_chi_hien_tai" name="dia_chi_hien_tai" rows="2"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card border-0 bg-light mb-4">
                                                        <div class="card-header bg-warning text-dark">
                                                            <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Liên hệ khẩn cấp</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="mb-3">
                                                                        <label for="lien_he_khan_cap_ten" class="form-label">Tên</label>
                                                                        <input type="text" class="form-control" id="lien_he_khan_cap_ten" name="lien_he_khan_cap_ten">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="mb-3">
                                                                        <label for="lien_he_khan_cap_quan_he" class="form-label">Quan hệ</label>
                                                                        <input type="text" class="form-control" id="lien_he_khan_cap_quan_he" name="lien_he_khan_cap_quan_he">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="mb-3">
                                                                        <label for="lien_he_khan_cap_dien_thoai" class="form-label">Điện thoại</label>
                                                                        <input type="text" class="form-control" id="lien_he_khan_cap_dien_thoai" name="lien_he_khan_cap_dien_thoai">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Family Tab -->
                                        <div class="tab-pane fade" id="family" role="tabpanel">
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle me-2"></i>
                                                Thông tin gia đình có thể được thêm sau khi tạo nhân viên.
                                            </div>
                                            <p class="text-muted">Sau khi tạo nhân viên thành công, bạn có thể thêm thông tin thành viên gia đình từ trang chỉnh sửa.</p>
                                        </div>

                                        <!-- Documents Tab -->
                                        <div class="tab-pane fade" id="documents" role="tabpanel">
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle me-2"></i>
                                                Tài liệu có thể được upload sau khi tạo nhân viên.
                                            </div>
                                            <p class="text-muted">Sau khi tạo nhân viên thành công, bạn có thể upload tài liệu từ trang chỉnh sửa.</p>
                                        </div>

                                        <!-- Giấy tờ tùy thân Tab -->
                                        <div class="tab-pane fade" id="giayto" role="tabpanel">
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle me-2"></i>
                                                Giấy tờ tùy thân có thể được thêm sau khi tạo nhân viên.
                                            </div>
                                            <p class="text-muted">Sau khi tạo nhân viên thành công, bạn có thể thêm giấy tờ tùy thân từ trang chỉnh sửa.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4 px-4">
                            <a href="{{ route('nhan-vien.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i>Lưu nhân viên
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatarPreview');
            preview.innerHTML = `<img src="${e.target.result}" alt="Avatar" class="rounded-circle shadow" width="120" height="120" style="object-fit: cover;">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// AJAX submit for employee create form

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('employeeForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Đang lưu...';

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(async response => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>Lưu nhân viên';
            if (response.ok) {
                const data = await response.json();
                if (data.success && data.id) {
                    window.location.href = `/nhan-vien/${data.id}`;
                    return;
                }
                showAlert('Thêm nhân viên thành công!', 'success');
                form.reset();
                document.getElementById('avatarPreview').innerHTML = '';
            } else if (response.status === 422) {
                const data = await response.json();
                let errorMsg = 'Vui lòng kiểm tra lại thông tin.';
                if (data.errors) {
                    errorMsg += '<ul>' + Object.values(data.errors).map(arr => `<li>${arr[0]}</li>`).join('') + '</ul>';
                }
                showAlert(errorMsg, 'danger');
            } else {
                showAlert('Đã có lỗi xảy ra. Vui lòng thử lại!', 'danger');
            }
        })
        .catch(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-save me-1"></i>Lưu nhân viên';
            showAlert('Đã có lỗi xảy ra. Vui lòng thử lại!', 'danger');
        });
    });
});

function showAlert(message, type = 'info') {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.custom-alert');
    existingAlerts.forEach(alert => alert.remove());

    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible custom-alert fade show`;
    alertDiv.setAttribute('role', 'alert');
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    alertDiv.style.cssText = 'position: fixed; bottom: 20px; right: 20px; z-index: 9999; max-width: 400px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
    document.body.appendChild(alertDiv);
    setTimeout(() => {
        if (alertDiv.parentNode) alertDiv.remove();
    }, 5000);
}
</script>
@endsection