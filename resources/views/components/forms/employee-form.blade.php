@props(['employee' => null, 'phongBans' => [], 'chucVus' => []])

<form id="employeeForm" enctype="multipart/form-data">
    @csrf
    @if($employee)
        @method('PUT')
    @endif

    <!-- Avatar Section -->
    <div class="text-center mb-4">
        <div class="position-relative d-inline-block">
            @if($employee && $employee->anh_dai_dien)
                <img id="avatarPreview" src="{{ asset('storage/' . $employee->anh_dai_dien) }}"
                     alt="Avatar" class="rounded-circle shadow" width="120" height="120" style="object-fit: cover;">
            @else
                <div id="avatarPreview" class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto shadow"
                     style="width: 120px; height: 120px; font-size: 2.5rem;">
                    {{ $employee ? strtoupper(substr($employee->ten, 0, 1)) : 'U' }}
                </div>
            @endif
            <label for="anh_dai_dien" class="btn btn-sm btn-outline-primary position-absolute" style="bottom: 0; right: 0;">
                <i class="fas fa-camera"></i>
            </label>
            <input type="file" id="anh_dai_dien" name="anh_dai_dien" class="d-none" accept="image/*" onchange="previewAvatar(this)">
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4" id="employeeTabs" role="tablist">
        <li class="nav-item"><button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic">Thông tin cơ bản</button></li>
        <li class="nav-item"><button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact">Liên hệ</button></li>
        <li class="nav-item"><button class="nav-link" id="work-tab" data-bs-toggle="tab" data-bs-target="#work">Công việc</button></li>
        <li class="nav-item"><button class="nav-link" id="family-tab" data-bs-toggle="tab" data-bs-target="#family">Gia đình</button></li>
        <li class="nav-item"><button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents">Tài liệu</button></li>
    </ul>

    <div class="tab-content" id="employeeTabContent">
        <!-- Basic Tab -->
        <div class="tab-pane fade show active" id="basic" role="tabpanel">
            <div class="row">
                <div class="col-md-6">
                    <x-ui.input name="ma_nhanvien" label="Mã nhân viên" value="{{ $employee->ma_nhanvien ?? '' }}" required />
                    <x-ui.input name="ho" label="Họ" value="{{ $employee->ho ?? '' }}" required />
                    <x-ui.input name="ten" label="Tên" value="{{ $employee->ten ?? '' }}" required />
                    <x-ui.input name="ngay_sinh" label="Ngày sinh" type="date" value="{{ $employee->ngay_sinh ?? '' }}" />
                    <x-ui.select name="gioi_tinh" label="Giới tính" :options="['nam' => 'Nam', 'nu' => 'Nữ', 'khac' => 'Khác']" value="{{ $employee->gioi_tinh ?? '' }}" />
                </div>
                <div class="col-md-6">
                    <x-ui.input name="email" label="Email" type="email" value="{{ $employee->email ?? '' }}" />
                    <x-ui.input name="so_dien_thoai" label="Điện thoại" value="{{ $employee->so_dien_thoai ?? '' }}" />
                    <x-ui.input name="dia_chi" label="Địa chỉ" value="{{ $employee->dia_chi ?? '' }}" />
                    <x-ui.select name="quoc_tich" label="Quốc tịch" :options="['Việt Nam' => 'Việt Nam']" value="{{ $employee->quoc_tich ?? 'Việt Nam' }}" />
                    <x-ui.input name="dan_toc" label="Dân tộc" value="{{ $employee->dan_toc ?? '' }}" />
                </div>
            </div>
        </div>

        <!-- Contact Tab -->
        <div class="tab-pane fade" id="contact" role="tabpanel">
            <p class="text-muted">Thông tin liên hệ sẽ được cập nhật trong module liên hệ.</p>
        </div>

        <!-- Work Tab -->
        <div class="tab-pane fade" id="work" role="tabpanel">
            <div class="row">
                <div class="col-md-6">
                    <x-ui.select name="phong_ban_id" label="Phòng ban" :options="$phongBans->pluck('ten_phong_ban', 'id')" value="{{ $employee->phong_ban_id ?? '' }}" />
                    <x-ui.select name="chuc_vu_id" label="Chức vụ" :options="$chucVus->pluck('ten_chuc_vu', 'id')" value="{{ $employee->chuc_vu_id ?? '' }}" />
                </div>
                <div class="col-md-6">
                    <x-ui.input name="ngay_vao_lam" label="Ngày vào làm" type="date" value="{{ $employee->ngay_vao_lam ?? '' }}" />
                    <x-ui.select name="trang_thai" label="Trạng thái" :options="[
                        'nhan_vien_chinh_thuc' => 'Nhân viên chính thức',
                        'thu_viec' => 'Thử việc',
                        'thai_san' => 'Thai sản',
                        'nghi_viec' => 'Nghỉ việc',
                        'khac' => 'Khác'
                    ]" value="{{ $employee->trang_thai ?? 'nhan_vien_chinh_thuc' }}" />
                </div>
            </div>
        </div>

        <!-- Family Tab -->
        <div class="tab-pane fade" id="family" role="tabpanel">
            <p class="text-muted">Thông tin gia đình sẽ được cập nhật trong module gia đình.</p>
        </div>

        <!-- Documents Tab -->
        <div class="tab-pane fade" id="documents" role="tabpanel">
            <p class="text-muted">Tài liệu sẽ được cập nhật trong module tài liệu.</p>
        </div>
    </div>
</form>