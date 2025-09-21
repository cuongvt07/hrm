<form id="employeeForm" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <!-- Thông tin cơ bản -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-user me-2"></i>Thông tin cơ bản</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ma_nhanvien" class="form-label">Mã nhân viên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ma_nhanvien" name="ma_nhanvien" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="trang_thai" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                            <select class="form-select" id="trang_thai" name="trang_thai" required>
                                <option value="">Chọn trạng thái</option>
                                <option value="nhan_vien_chinh_thuc">Nhân viên chính thức</option>
                                <option value="thu_viec">Thử việc</option>
                                <option value="thai_san">Thai sản</option>
                                <option value="nghi_viec">Nghỉ việc</option>
                                <option value="khac">Khác</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ho" class="form-label">Họ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ho" name="ho" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ten" class="form-label">Tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ten" name="ten" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ngay_sinh" class="form-label">Ngày sinh</label>
                            <input type="date" class="form-control" id="ngay_sinh" name="ngay_sinh">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="gioi_tinh" class="form-label">Giới tính</label>
                            <select class="form-select" id="gioi_tinh" name="gioi_tinh">
                                <option value="">Chọn giới tính</option>
                                <option value="nam">Nam</option>
                                <option value="nu">Nữ</option>
                                <option value="khac">Khác</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tinh_trang_hon_nhan" class="form-label">Tình trạng hôn nhân</label>
                            <select class="form-select" id="tinh_trang_hon_nhan" name="tinh_trang_hon_nhan">
                                <option value="">Chọn tình trạng</option>
                                <option value="doc_than">Độc thân</option>
                                <option value="da_ket_hon">Đã kết hôn</option>
                                <option value="ly_hon">Ly hôn</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="quoc_tich" class="form-label">Quốc tịch</label>
                            <input type="text" class="form-control" id="quoc_tich" name="quoc_tich" value="Việt Nam">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="dan_toc" class="form-label">Dân tộc</label>
                            <input type="text" class="form-control" id="dan_toc" name="dan_toc">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ton_giao" class="form-label">Tôn giáo</label>
                            <input type="text" class="form-control" id="ton_giao" name="ton_giao">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Thông tin liên hệ -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-phone me-2"></i>Thông tin liên hệ</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="so_dien_thoai" class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" id="so_dien_thoai" name="so_dien_thoai">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="dia_chi" class="form-label">Địa chỉ</label>
                        <textarea class="form-control" id="dia_chi" name="dia_chi" rows="3"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Thông tin công việc -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-briefcase me-2"></i>Thông tin công việc</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="phong_ban_id" class="form-label">Phòng ban</label>
                            <select class="form-select" id="phong_ban_id" name="phong_ban_id">
                                <option value="">Chọn phòng ban</option>
                                @foreach($phongBans as $phongBan)
                                    <option value="{{ $phongBan->id }}">{{ $phongBan->ten_phong_ban }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="chuc_vu_id" class="form-label">Chức vụ</label>
                            <select class="form-select" id="chuc_vu_id" name="chuc_vu_id">
                                <option value="">Chọn chức vụ</option>
                                @foreach($chucVus as $chucVu)
                                    <option value="{{ $chucVu->id }}">{{ $chucVu->ten_chuc_vu }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="ngay_vao_lam" class="form-label">Ngày vào làm</label>
                            <input type="date" class="form-control" id="ngay_vao_lam" name="ngay_vao_lam">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Ảnh đại diện -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-image me-2"></i>Ảnh đại diện</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="anh_dai_dien" class="form-label">Chọn ảnh</label>
                                <input type="file" class="form-control" id="anh_dai_dien" name="anh_dai_dien" accept="image/*">
                                <div class="form-text">Chọn file ảnh (JPG, PNG, GIF) - Tối đa 2MB</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center">
                                <div id="imagePreview" class="border rounded p-3" style="min-height: 150px; display: none;">
                                    <img id="previewImg" src="" alt="Preview" class="img-fluid rounded" style="max-height: 150px;">
                                </div>
                                <div id="noImagePreview" class="border rounded p-3 d-flex align-items-center justify-content-center" style="min-height: 150px;">
                                    <div class="text-muted">
                                        <i class="fas fa-image fa-2x mb-2"></i>
                                        <p class="mb-0">Chưa có ảnh</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Form Actions -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Hủy
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>Lưu thông tin
                </button>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form submission
    document.getElementById('employeeForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Clear previous validation
        document.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });
        
        // Validate form
        if (validateForm()) {
            const formData = new FormData(this);
            saveEmployee(formData);
        }
    });
    
    // Image preview
    document.getElementById('anh_dai_dien').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
                document.getElementById('noImagePreview').style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    });
});

function validateForm() {
    let isValid = true;
    
    // Required fields
    const requiredFields = ['ma_nhanvien', 'ho', 'ten', 'trang_thai'];
    requiredFields.forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        }
    });
    
    // Email validation
    const email = document.getElementById('email');
    if (email.value && !isValidEmail(email.value)) {
        email.classList.add('is-invalid');
        isValid = false;
    }
    
    // Phone validation
    const phone = document.getElementById('so_dien_thoai');
    if (phone.value && !isValidPhone(phone.value)) {
        phone.classList.add('is-invalid');
        isValid = false;
    }
    
    return isValid;
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function isValidPhone(phone) {
    const phoneRegex = /^[0-9+\-\s()]+$/;
    return phoneRegex.test(phone) && phone.length >= 10;
}
</script>
