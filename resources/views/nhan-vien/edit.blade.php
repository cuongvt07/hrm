@extends('layouts.app')

@section('title', 'Chỉnh sửa nhân viên - ' . $nhanVien->ho . ' ' . $nhanVien->ten)

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0">Chỉnh sửa nhân viên</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('nhan-vien.index') }}">Nhân viên</a></li>
                                <li class="breadcrumb-item"><a
                                        href="{{ route('nhan-vien.show', $nhanVien->id) }}">{{ $nhanVien->ho }}
                                        {{ $nhanVien->ten }}</a></li>
                                <li class="breadcrumb-item active">Chỉnh sửa</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('nhan-vien.show', $nhanVien->id) }}" class="btn btn-outline-info">
                            <i class="fas fa-eye me-1"></i>Xem chi tiết
                        </a>
                        <a href="{{ route('nhan-vien.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Quay lại danh sách
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employee Edit Form -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('nhan-vien.update', $nhanVien->id) }}" method="POST"
                            enctype="multipart/form-data" id="employeeForm">
                            @csrf
                            @method('PUT')
                            <div class="row g-0">
                                <!-- Left Column -->
                                <div class="col-md-3">
                                    <div class="p-4 border-end">
                                        <!-- Avatar Section -->
                                        <div class="text-center mb-4">
                                            <div class="position-relative d-inline-block">
                                                @if($nhanVien->anh_dai_dien)
                                                    <img id="avatarPreview"
                                                        src="{{ asset('storage/' . $nhanVien->anh_dai_dien) }}" alt="Avatar"
                                                        class="rounded-circle shadow" width="120" height="120"
                                                        style="object-fit: cover;">
                                                @else
                                                    <div id="avatarPreview"
                                                        class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto shadow"
                                                        style="width: 120px; height: 120px; font-size: 2.5rem;">
                                                        {{ strtoupper(substr($nhanVien->ten, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <label for="anh_dai_dien"
                                                    class="btn btn-sm btn-outline-primary position-absolute"
                                                    style="bottom: 0; right: 0;">
                                                    <i class="fas fa-camera"></i>
                                                </label>
                                                <input type="file" id="anh_dai_dien" name="anh_dai_dien" class="d-none"
                                                    accept="image/*" onchange="previewAvatar(this)">
                                            </div>
                                        </div>

                                        <!-- Basic Info Fields -->
                                        <div class="mb-3">
                                            <label for="ma_nhanvien" class="form-label">Mã nhân viên <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="ma_nhanvien" name="ma_nhanvien"
                                                value="{{ $nhanVien->ma_nhanvien }}" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                value="{{ $nhanVien->email }}">
                                        </div>

                                        <div class="mb-3">
                                            <label for="so_dien_thoai" class="form-label">Điện thoại</label>
                                            <input type="text" class="form-control" id="so_dien_thoai" name="so_dien_thoai"
                                                value="{{ $nhanVien->so_dien_thoai }}">
                                        </div>

                                        <div class="mb-3">
                                            <label for="ngay_sinh" class="form-label">Ngày sinh</label>
                                            <input type="date" class="form-control" id="ngay_sinh" name="ngay_sinh"
                                                value="{{ $nhanVien->ngay_sinh ? \Carbon\Carbon::parse($nhanVien->ngay_sinh)->format('Y-m-d') : '' }}">
                                        </div>

                                        <div class="mb-3">
                                            <label for="gioi_tinh" class="form-label">Giới tính</label>
                                            <select class="form-select" id="gioi_tinh" name="gioi_tinh">
                                                <option value="">Chọn giới tính</option>
                                                <option value="nam" {{ $nhanVien->gioi_tinh == 'nam' ? 'selected' : '' }}>Nam
                                                </option>
                                                <option value="nu" {{ $nhanVien->gioi_tinh == 'nu' ? 'selected' : '' }}>Nữ
                                                </option>
                                                <option value="khac" {{ $nhanVien->gioi_tinh == 'khac' ? 'selected' : '' }}>
                                                    Khác</option>
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
                                                <button class="nav-link active" id="basic-tab" data-bs-toggle="tab"
                                                    data-bs-target="#basic" type="button" role="tab">Thông tin cơ
                                                    bản</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="work-tab" data-bs-toggle="tab"
                                                    data-bs-target="#work" type="button" role="tab">Thông tin công
                                                    việc</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="contact-tab" data-bs-toggle="tab"
                                                    data-bs-target="#contact" type="button" role="tab">Thông tin liên
                                                    hệ</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="family-tab" data-bs-toggle="tab"
                                                    data-bs-target="#family" type="button" role="tab">Gia đình</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="myFile-tab" data-bs-toggle="tab"
                                                    data-bs-target="#myFile" type="button" role="tab">Giấy tờ tùy
                                                    thân</button>   
                                            </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="insurance-tab" data-bs-toggle="tab"
                                                        data-bs-target="#insurance" type="button" role="tab">Thông tin bảo hiểm</button>
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
                                                                    <input type="text" class="form-control" id="ho" name="ho" value="{{ $nhanVien->ho }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="ten" class="form-label">Tên <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" id="ten" name="ten" value="{{ $nhanVien->ten }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="tinh_trang_hon_nhan" class="form-label">Tình trạng hôn nhân</label>
                                                                    <select class="form-select" id="tinh_trang_hon_nhan" name="tinh_trang_hon_nhan">
                                                                        <option value="">Chọn tình trạng</option>
                                                                        <option value="doc_than" {{ $nhanVien->tinh_trang_hon_nhan == 'doc_than' ? 'selected' : '' }}>Độc thân</option>
                                                                        <option value="da_ket_hon" {{ $nhanVien->tinh_trang_hon_nhan == 'da_ket_hon' ? 'selected' : '' }}>Đã kết hôn</option>
                                                                        <option value="ly_hon" {{ $nhanVien->tinh_trang_hon_nhan == 'ly_hon' ? 'selected' : '' }}>Ly hôn</option>
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
                                                                    <input type="text" class="form-control" id="quoc_tich" name="quoc_tich" value="{{ $nhanVien->quoc_tich ?? 'Việt Nam' }}">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="dan_toc" class="form-label">Dân tộc</label>
                                                                    <input type="text" class="form-control" id="dan_toc" name="dan_toc" value="{{ $nhanVien->dan_toc ?? 'Kinh' }}">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="ton_giao" class="form-label">Tôn giáo</label>
                                                                    <input type="text" class="form-control" id="ton_giao" name="ton_giao" value="{{ $nhanVien->ton_giao }}">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="dia_chi" class="form-label">Địa chỉ</label>
                                                                    <textarea class="form-control" id="dia_chi" name="dia_chi" rows="2">{{ $nhanVien->dia_chi }}</textarea>
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
                                                                    <label for="phong_ban_id" class="form-label">Phòng ban</label>
                                                                    <select class="form-select" id="phong_ban_id"
                                                                        name="phong_ban_id">
                                                                        <option value="">Chọn phòng ban</option>
                                                                        @foreach($phongBans as $phongBan)
                                                                            <option value="{{ $phongBan->id }}" {{ $nhanVien->phong_ban_id == $phongBan->id ? 'selected' : '' }}>{{ $phongBan->ten_phong_ban }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="chuc_vu_id" class="form-label">Chức vụ</label>
                                                                    <select class="form-select" id="chuc_vu_id" name="chuc_vu_id">
                                                                        <option value="">Chọn chức vụ</option>
                                                                        @foreach($chucVus as $chucVu)
                                                                            <option value="{{ $chucVu->id }}" {{ $nhanVien->chuc_vu_id == $chucVu->id ? 'selected' : '' }}>
                                                                                {{ $chucVu->ten_chuc_vu }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="ngay_vao_lam" class="form-label">Ngày vào
                                                                        làm</label>
                                                                    <input type="date" class="form-control" id="ngay_vao_lam"
                                                                        name="ngay_vao_lam" value="{{ $nhanVien->ngay_vao_lam }}">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="ngay_thu_viec" class="form-label">Ngày thử
                                                                        việc</label>
                                                                    <input type="date" class="form-control" id="ngay_thu_viec"
                                                                        name="ngay_thu_viec" value="{{ $nhanVien->ngay_thu_viec }}">
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
                                                                        <option value="thu_viec" {{ $nhanVien->trang_thai == 'thu_viec' ? 'selected' : '' }}>
                                                                            Thử việc</option>
                                                                        <option value="nhan_vien_chinh_thuc" {{ $nhanVien->trang_thai == 'nhan_vien_chinh_thuc' ? 'selected' : '' }}>Nhân viên chính thức</option>
                                                                        <option value="thai_san" {{ $nhanVien->trang_thai == 'thai_san' ? 'selected' : '' }}>
                                                                            Thai sản</option>
                                                                        <option value="nghi_viec" {{ $nhanVien->trang_thai == 'nghi_viec' ? 'selected' : '' }}>
                                                                            Nghỉ việc</option>
                                                                        <option value="khac" {{ $nhanVien->trang_thai == 'khac' ? 'selected' : '' }}>Khác</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="quan_ly_truc_tiep_id" class="form-label">Quản lý trực tiếp</label>
                                                                    <select class="form-select" id="quan_ly_truc_tiep_id" name="quan_ly_truc_tiep_id">
                                                                        <option value="">Chọn quản lý trực tiếp</option>
                                                                        @foreach($managers as $manager)
                                                                            <option value="{{ $manager->id }}" {{ $nhanVien->quan_ly_truc_tiep_id == $manager->id ? 'selected' : '' }}>
                                                                                {{ $manager->ho }} {{ $manager->ten }} - {{ $manager->chucVu->ten_chuc_vu }}</option>
                                                                        @endforeach
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
                                                                            <input type="number" step="0.01" class="form-control" id="luong_co_ban" name="luong_co_ban" value="{{ optional($nhanVien->thongTinLuong)->luong_co_ban }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="mb-3">
                                                                            <label for="so_tai_khoan" class="form-label">Số tài khoản ngân hàng</label>
                                                                            <input type="text" class="form-control" id="so_tai_khoan" name="so_tai_khoan" value="{{ optional($nhanVien->thongTinLuong)->so_tai_khoan }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="mb-3">
                                                                            <label for="ten_ngan_hang" class="form-label">Tên ngân hàng</label>
                                                                            <input type="text" class="form-control" id="ten_ngan_hang" name="ten_ngan_hang" value="{{ optional($nhanVien->thongTinLuong)->ten_ngan_hang }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <div class="mb-3">
                                                                            <label for="chi_nhanh_ngan_hang" class="form-label">Chi nhánh ngân hàng</label>
                                                                            <input type="text" class="form-control" id="chi_nhanh_ngan_hang" name="chi_nhanh_ngan_hang" value="{{ optional($nhanVien->thongTinLuong)->chi_nhanh_ngan_hang }}">
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
                                                                    <input type="text" class="form-control" id="dien_thoai_di_dong" name="dien_thoai_di_dong" value="{{ $nhanVien->thongTinLienHe->dien_thoai_di_dong ?? '' }}">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="dien_thoai_co_quan" class="form-label">Điện thoại cơ quan</label>
                                                                    <input type="text" class="form-control" id="dien_thoai_co_quan" name="dien_thoai_co_quan" value="{{ $nhanVien->thongTinLienHe->dien_thoai_co_quan ?? '' }}">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="dien_thoai_nha_rieng" class="form-label">Điện thoại nhà riêng</label>
                                                                    <input type="text" class="form-control" id="dien_thoai_nha_rieng" name="dien_thoai_nha_rieng" value="{{ $nhanVien->thongTinLienHe->dien_thoai_nha_rieng ?? '' }}">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="dien_thoai_khac" class="form-label">Điện thoại khác</label>
                                                                    <input type="text" class="form-control" id="dien_thoai_khac" name="dien_thoai_khac" value="{{ $nhanVien->thongTinLienHe->dien_thoai_khac ?? '' }}">
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
                                                                    <input type="email" class="form-control" id="email_co_quan" name="email_co_quan" value="{{ $nhanVien->thongTinLienHe->email_co_quan ?? '' }}">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="email_ca_nhan" class="form-label">Email cá nhân</label>
                                                                    <input type="email" class="form-control" id="email_ca_nhan" name="email_ca_nhan" value="{{ $nhanVien->thongTinLienHe->email_ca_nhan ?? '' }}">
                                                                </div>
                                                                <div class="card-header bg-secondary text-white mt-3">
                                                                    <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Địa chỉ</h5>
                                                                </div>
                                                                <div class="mb-3 mt-2">
                                                                    <label for="dia_chi_thuong_tru" class="form-label">Địa chỉ thường trú</label>
                                                                    <textarea class="form-control" id="dia_chi_thuong_tru" name="dia_chi_thuong_tru" rows="2">{{ $nhanVien->thongTinLienHe->dia_chi_thuong_tru ?? '' }}</textarea>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="dia_chi_hien_tai" class="form-label">Địa chỉ hiện tại</label>
                                                                    <textarea class="form-control" id="dia_chi_hien_tai" name="dia_chi_hien_tai" rows="2">{{ $nhanVien->thongTinLienHe->dia_chi_hien_tai ?? '' }}</textarea>
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
                                                                            <input type="text" class="form-control" id="lien_he_khan_cap_ten" name="lien_he_khan_cap_ten" value="{{ $nhanVien->thongTinLienHe->lien_he_khan_cap_ten ?? '' }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label for="lien_he_khan_cap_quan_he" class="form-label">Quan hệ</label>
                                                                            <input type="text" class="form-control" id="lien_he_khan_cap_quan_he" name="lien_he_khan_cap_quan_he" value="{{ $nhanVien->thongTinLienHe->lien_he_khan_cap_quan_he ?? '' }}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="mb-3">
                                                                            <label for="lien_he_khan_cap_dien_thoai" class="form-label">Điện thoại</label>
                                                                            <input type="text" class="form-control" id="lien_he_khan_cap_dien_thoai" name="lien_he_khan_cap_dien_thoai" value="{{ $nhanVien->thongTinLienHe->lien_he_khan_cap_dien_thoai ?? '' }}">
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
                                                <div class="mb-4">
                                                    <h5>Thành viên gia đình</h5>
                                                    <button type="button" class="btn btn-primary btn-sm mb-3"
                                                        id="showAddFamilyForm">
                                                        <i class="fas fa-plus me-1"></i>Thêm thành viên
                                                    </button>
                                                </div>
                                                <div id="addFamilyFormContainer" style="display:none;">
                                                    <div class="border rounded p-3 mb-4 bg-light">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="family_quan_he" class="form-label">Quan hệ
                                                                        <span class="text-danger">*</span></label>
                                                                    <select class="form-select" id="family_quan_he"
                                                                        name="quan_he">
                                                                        <option value="">Chọn quan hệ</option>
                                                                        <option value="cha">Cha</option>
                                                                        <option value="me">Mẹ</option>
                                                                        <option value="vo">Vợ</option>
                                                                        <option value="chong">Chồng</option>
                                                                        <option value="con">Con</option>
                                                                        <option value="anh">Anh</option>
                                                                        <option value="chi">Chị</option>
                                                                        <option value="em">Em</option>
                                                                        <option value="ong">Ông</option>
                                                                        <option value="ba">Bà</option>
                                                                        <option value="khac">Khác</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="family_ho_ten" class="form-label">Họ tên
                                                                        <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control"
                                                                        id="family_ho_ten" name="ho_ten">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="family_ngay_sinh" class="form-label">Ngày
                                                                        sinh</label>
                                                                    <input type="date" class="form-control"
                                                                        id="family_ngay_sinh" name="ngay_sinh">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="family_nghe_nghiep" class="form-label">Nghề
                                                                        nghiệp</label>
                                                                    <input type="text" class="form-control"
                                                                        id="family_nghe_nghiep" name="nghe_nghiep">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="family_dien_thoai" class="form-label">Điện
                                                                        thoại</label>
                                                                    <input type="text" class="form-control"
                                                                        id="family_dien_thoai" name="dien_thoai">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="family_dia_chi" class="form-label">Địa chỉ
                                                                        liên hệ</label>
                                                                    <textarea class="form-control" id="family_dia_chi"
                                                                        name="dia_chi_lien_he" rows="2"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="family_ghi_chu" class="form-label">Ghi chú</label>
                                                            <textarea class="form-control" id="family_ghi_chu"
                                                                name="ghi_chu" rows="2"></textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="family_la_nguoi_phu_thuoc" class="form-label">Người
                                                                phụ thuộc</label>
                                                            <input type="checkbox" class="form-check-input"
                                                                id="family_la_nguoi_phu_thuoc" name="la_nguoi_phu_thuoc">
                                                        </div>
                                                        <input type="hidden" id="family_id" name="family_id">
                                                        <div class="d-flex gap-2">
                                                            <button type="button" class="btn btn-secondary"
                                                                id="cancelAddFamily">Hủy</button>
                                                            <button type="button" class="btn btn-primary"
                                                                id="addFamilyMember">Lưu</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="familyTableContainer">
                                                    @if($nhanVien->thongTinGiaDinh && $nhanVien->thongTinGiaDinh->count() > 0)
                                                        <div class="table-responsive">
                                                            <table class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Quan hệ</th>
                                                                        <th>Họ tên</th>
                                                                        <th>Ngày sinh</th>
                                                                        <th>Nghề nghiệp</th>
                                                                        <th>Điện thoại</th>
                                                                        <th>Địa chỉ liên hệ</th>
                                                                        <th>Người phụ thuộc</th>
                                                                        <th>Ghi chú</th>
                                                                        <th>Thao tác</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="familyTableBody">
                                                                    @foreach($nhanVien->thongTinGiaDinh as $member)
                                                                        <tr data-id="{{ $member->id }}">
                                                                            <td>{{ $member->quan_he }}</td>
                                                                            <td>{{ $member->ho_ten }}</td>
                                                                            <td>{{ $member->ngay_sinh ? \Carbon\Carbon::parse($member->ngay_sinh)->format('d/m/Y') : '' }}
                                                                            </td>
                                                                            <td>{{ $member->nghe_nghiep }}</td>
                                                                            <td>{{ $member->dien_thoai }}</td>
                                                                            <td>{{ $member->dia_chi_lien_he }}</td>
                                                                            <td>
                                                                                @if($member->la_nguoi_phu_thuoc)
                                                                                    <span class="badge bg-success">Có</span>
                                                                                @else
                                                                                    <span class="badge bg-secondary">Không</span>
                                                                                @endif
                                                                            </td>
                                                                            <td>{{ $member->ghi_chu }}</td>
                                                                            <td>
                                                                                <button type="button"
                                                                                    class="btn btn-sm btn-outline-primary me-1 edit-family"
                                                                                    data-id="{{ $member->id }}">
                                                                                    <i class="fas fa-edit"></i>
                                                                                </button>
                                                                                <button type="button"
                                                                                    class="btn btn-sm btn-outline-danger delete-family"
                                                                                    data-id="{{ $member->id }}">
                                                                                    <i class="fas fa-trash"></i>
                                                                                </button>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    @else
                                                        <p class="text-muted" id="noFamilyMessage">Chưa có thông tin thành viên
                                                            gia đình</p>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- My file Tab -->
                                            <div class="tab-pane fade" id="myFile" role="tabpanel">
                                                <div class="mb-4">
                                                    <h5>Giấy tờ tùy thân</h5>
                                                    <button type="button" class="btn btn-primary btn-sm mb-3" id="showAddMyFileForm">
                                                        <i class="fas fa-plus me-1"></i>Thêm giấy tờ
                                                    </button>
                                                </div>
                                                <div id="addMyFileFormContainer" style="display:none;">
                                                    <div class="border rounded p-3 mb-4 bg-light">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="loai_giay_to" class="form-label">Loại giấy tờ <span class="text-danger">*</span></label>
                                                                    <select class="form-select" id="loai_giay_to" name="loai_giay_to">
                                                                        <option value="">Chọn loại giấy tờ</option>
                                                                        <option value="CMND">CMND</option>
                                                                        <option value="CCCD">CCCD</option>
                                                                        <option value="Ho_chieu">Hộ chiếu</option>
                                                                        <option value="Ma_so_thue">Mã số thuế</option>
                                                                        <option value="Khac">Khác</option>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="so_giay_to" class="form-label">Số giấy tờ <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control" id="so_giay_to" name="so_giay_to">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="ngay_cap" class="form-label">Ngày cấp</label>
                                                                    <input type="date" class="form-control" id="ngay_cap" name="ngay_cap">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label for="ngay_het_han" class="form-label">Ngày hết hạn</label>
                                                                    <input type="date" class="form-control" id="ngay_het_han" name="ngay_het_han">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="noi_cap" class="form-label">Nơi cấp</label>
                                                                    <input type="text" class="form-control" id="noi_cap" name="noi_cap">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="ghi_chu" class="form-label">Ghi chú</label>
                                                            <textarea class="form-control" id="ghi_chu" name="ghi_chu" rows="2"></textarea>
                                                        </div>
                                                        <input type="hidden" id="giay_to_id" name="giay_to_id">
                                                        <div class="d-flex gap-2">
                                                            <button type="button" class="btn btn-secondary" id="cancelAddMyFile">Hủy</button>
                                                            <button type="button" class="btn btn-primary" id="addMyFile">Lưu</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                </div>

                                                <!-- Insurance Tab -->
                                                <div class="tab-pane fade" id="insurance" role="tabpanel">
                                                    <div class="card border-0 bg-light mb-4">
                                                        <div class="card-header bg-info text-white">
                                                            <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Thông tin bảo hiểm</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="mb-3">
                                                                        <label for="ngay_tham_gia_bh" class="form-label">Ngày tham gia BH</label>
                                                                        <input type="date" class="form-control" id="ngay_tham_gia_bh" name="ngay_tham_gia_bh" value="{{ optional($nhanVien->baoHiem)->ngay_tham_gia_bh }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="ty_le_dong_bh" class="form-label">Tỷ lệ đóng BH (%)</label>
                                                                        <input type="number" step="0.01" class="form-control" id="ty_le_dong_bh" name="ty_le_dong_bh" value="{{ optional($nhanVien->baoHiem)->ty_le_dong_bh }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="ty_le_bhxh" class="form-label">Tỷ lệ đóng BHXH (%)</label>
                                                                        <input type="number" step="0.01" class="form-control" id="ty_le_bhxh" name="ty_le_bhxh" value="{{ optional($nhanVien->baoHiem)->ty_le_bhxh }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="ty_le_bhyt" class="form-label">Tỷ lệ đóng BHYT (%)</label>
                                                                        <input type="number" step="0.01" class="form-control" id="ty_le_bhyt" name="ty_le_bhyt" value="{{ optional($nhanVien->baoHiem)->ty_le_bhyt }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="ty_le_bhtn" class="form-label">Tỷ lệ đóng BHTN (%)</label>
                                                                        <input type="number" step="0.01" class="form-control" id="ty_le_bhtn" name="ty_le_bhtn" value="{{ optional($nhanVien->baoHiem)->ty_le_bhtn }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="mb-3">
                                                                        <label for="so_so_bhxh" class="form-label">Số sổ BHXH</label>
                                                                        <input type="text" class="form-control" id="so_so_bhxh" name="so_so_bhxh" value="{{ optional($nhanVien->baoHiem)->so_so_bhxh }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="ma_so_bhxh" class="form-label">Mã số BHXH</label>
                                                                        <input type="text" class="form-control" id="ma_so_bhxh" name="ma_so_bhxh" value="{{ optional($nhanVien->baoHiem)->ma_so_bhxh }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="tham_gia_bao_hiem" class="form-label">Tham gia bảo hiểm</label>
                                                                        <select class="form-select" id="tham_gia_bao_hiem" name="tham_gia_bao_hiem">
                                                                            <option value="1" {{ optional($nhanVien->baoHiem)->tham_gia_bao_hiem ? 'selected' : '' }}>Có</option>
                                                                            <option value="0" {{ optional($nhanVien->baoHiem)->tham_gia_bao_hiem === 0 ? 'selected' : '' }}>Không</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="mb-3">
                                                                        <label for="tinh_cap" class="form-label">Tỉnh cấp</label>
                                                                        <input type="text" class="form-control" id="tinh_cap" name="tinh_cap" value="{{ optional($nhanVien->baoHiem)->tinh_cap }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="ma_tinh_cap" class="form-label">Mã Tỉnh cấp</label>
                                                                        <input type="text" class="form-control" id="ma_tinh_cap" name="ma_tinh_cap" value="{{ optional($nhanVien->baoHiem)->ma_tinh_cap }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="so_the_bhyt" class="form-label">Số thẻ BHYT</label>
                                                                        <input type="text" class="form-control" id="so_the_bhyt" name="so_the_bhyt" value="{{ optional($nhanVien->baoHiem)->so_the_bhyt }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="myFileTableContainer">
                                                    @if($nhanVien->GiayToTuyThan && $nhanVien->GiayToTuyThan->count())
                                                        <div class="table-responsive">
                                                            <table class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Loại giấy tờ</th>
                                                                        <th>Số giấy tờ</th>
                                                                        <th>Ngày cấp</th>
                                                                        <th>Ngày hết hạn</th>
                                                                        <th>Nơi cấp</th>
                                                                        <th>Ghi chú</th>
                                                                        <th>Thao tác</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="myFileTableBody">
                                                                    @foreach($nhanVien->GiayToTuyThan as $file)
                                                                        <tr data-id="{{ $file->id }}">
                                                                            <td>{{ $file->loai_giay_to }}</td>
                                                                            <td>{{ $file->so_giay_to }}</td>
                                                                            <td>{{ $file->ngay_cap ? \Carbon\Carbon::parse($file->ngay_cap)->format('d/m/Y') : '' }}</td>
                                                                            <td>{{ $file->ngay_het_han ? \Carbon\Carbon::parse($file->ngay_het_han)->format('d/m/Y') : '' }}</td>
                                                                            <td>{{ $file->noi_cap }}</td>
                                                                            <td>{{ $file->ghi_chu }}</td>
                                                                            <td>
                                                                                <button type="button" class="btn btn-sm btn-outline-primary me-1 edit-my-file"
                                                                                    data-id="{{ $file->id }}">
                                                                                    <i class="fas fa-edit"></i>
                                                                                </button>
                                                                                <button type="button" class="btn btn-sm btn-outline-danger delete-my-file"
                                                                                    data-id="{{ $file->id }}">
                                                                                    <i class="fas fa-trash"></i>
                                                                                </button>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    @else
                                                        <p class="text-muted" id="noFamilyMessage">Chưa có thông tin giấy tờ tùy thân</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4 px-4">
                                <a href="{{ route('nhan-vien.show', $nhanVien->id) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Hủy
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i>Lưu thay đổi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function () {
            // Array to store all family members (database + temporary)
            let familyMembers = @json($nhanVien->thongTinGiaDinh ?? []);

            // Array to store all my files (database + temporary)
            let myFiles = @json($nhanVien->GiayToTuyThan ?? []);

            // Function to render the my file table
            function renderMyFileTable() {
                const $tableBody = $('#myFileTableBody');
                const $tableContainer = $('#myFileTableContainer');
                const $noFileMessage = $('#noFamilyMessage');

                if (myFiles.length === 0) {
                    if ($noFileMessage.length) {
                        $noFileMessage.show();
                    } else {
                        $tableContainer.html('<p class="text-muted" id="noFamilyMessage">Chưa có thông tin giấy tờ tùy thân</p>');
                    }
                    $tableBody.closest('.table-responsive').hide();
                    return;
                }

                let html = '';
                myFiles.forEach(function (file, idx) {
                    html += `<tr data-id="${file.id || ''}" data-idx="${idx}">
                        <td>${file.loai_giay_to}</td>
                        <td>${file.so_giay_to}</td>
                        <td>${file.ngay_cap ? new Date(file.ngay_cap).toLocaleDateString('vi-VN') : ''}</td>
                        <td>${file.ngay_het_han ? new Date(file.ngay_het_han).toLocaleDateString('vi-VN') : ''}</td>
                        <td>${file.noi_cap || ''}</td>
                        <td>${file.ghi_chu || ''}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-primary me-1 edit-my-file" data-idx="${idx}"><i class="fas fa-edit"></i></button>
                            <button type="button" class="btn btn-sm btn-outline-danger delete-my-file" data-idx="${idx}" data-id="${file.id || ''}"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>`;
                });

                if ($tableBody.length) {
                    $tableBody.html(html);
                    $tableBody.closest('.table-responsive').show();
                    $noFileMessage.hide();
                } else {
                    $tableContainer.html(`
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Loại giấy tờ</th>
                                        <th>Số giấy tờ</th>
                                        <th>Ngày cấp</th>
                                        <th>Ngày hết hạn</th>
                                        <th>Nơi cấp</th>
                                        <th>Ghi chú</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody id="myFileTableBody">${html}</tbody>
                            </table>
                        </div>
                    `);
                }
            }

            // Show/hide add my file form
            $('#showAddMyFileForm').click(function () {
                $('#addMyFileFormContainer').show();
                $('#addMyFileFormContainer').find('input, select, textarea').val('');
                $('#giay_to_id').val('');
                $('#addMyFile').text('Lưu');
            });

            $('#cancelAddMyFile').click(function () {
                $('#addMyFileFormContainer').hide();
            });

            // Handle add/edit my file button click
            $('#addMyFile').click(function () {
                const $form = $('#addMyFileFormContainer');
                const loai_giay_to = $form.find('[name="loai_giay_to"]').val();
                const so_giay_to = $form.find('[name="so_giay_to"]').val();
                const giay_to_id = $form.find('[name="giay_to_id"]').val();

                // Basic validation
                if (!loai_giay_to || !so_giay_to) {
                    alert('Vui lòng điền đầy đủ Loại giấy tờ và Số giấy tờ.');
                    return;
                }

                const file = {
                    id: giay_to_id || null,
                    loai_giay_to: loai_giay_to,
                    so_giay_to: so_giay_to,
                    ngay_cap: $form.find('[name="ngay_cap"]').val() || null,
                    ngay_het_han: $form.find('[name="ngay_het_han"]').val() || null,
                    noi_cap: $form.find('[name="noi_cap"]').val() || null,
                    ghi_chu: $form.find('[name="ghi_chu"]').val() || null,
                    is_temp: !giay_to_id // New files are marked as temporary
                };

                if (giay_to_id) {
                    // Update existing file in array
                    const idx = myFiles.findIndex(f => (f.id && f.id == giay_to_id) || (f.is_temp && f === myFiles[parseInt($form.data('edit-idx'))]));
                    if (idx !== -1) {
                        myFiles[idx] = file;
                    }
                } else {
                    // Add new file
                    myFiles.push(file);
                }

                renderMyFileTable();
                $('#addMyFileFormContainer').hide();
                $form.find('input, select, textarea').val('');
                $('#giay_to_id').val('');
            });

            // Handle edit my file
            $(document).on('click', '.edit-my-file', function () {
                const idx = $(this).data('idx');
                const file = myFiles[idx];
                const $form = $('#addMyFileFormContainer');

                $form.find('[name="loai_giay_to"]').val(file.loai_giay_to);
                $form.find('[name="so_giay_to"]').val(file.so_giay_to);
                $form.find('[name="ngay_cap"]').val(file.ngay_cap || '');
                $form.find('[name="ngay_het_han"]').val(file.ngay_het_han || '');
                $form.find('[name="noi_cap"]').val(file.noi_cap || '');
                $form.find('[name="ghi_chu"]').val(file.ghi_chu || '');
                $form.find('[name="giay_to_id"]').val(file.id || '');
                $form.data('edit-idx', idx); // Store index for editing temporary files
                $('#addMyFile').text('Cập nhật');

                $('#addMyFileFormContainer').show();
            });

            // Handle delete my file (client-side for temporary, AJAX for database)
            $(document).on('click', '.delete-my-file', function () {
                const idx = $(this).data('idx');
                const fileId = $(this).data('id');

                if (fileId && !myFiles[idx].is_temp) {
                    // Database file: use AJAX (implement if needed)
                    if (confirm('Bạn có chắc chắn muốn xóa giấy tờ này?')) {
                        // TODO: AJAX delete logic here
                        myFiles.splice(idx, 1);
                        renderMyFileTable();
                    }
                } else {
                    // Temporary file: remove from array
                    if (confirm('Bạn có chắc chắn muốn xóa giấy tờ này?')) {
                        myFiles.splice(idx, 1);
                        renderMyFileTable();
                    }
                }
            });

            // Append my files to main form on submit
            $('#employeeForm').submit(function () {
                $('#addMyFileFormContainer').hide();
                let $input = $('#tempMyFilesInput');
                if ($input.length === 0) {
                    $input = $('<input>').attr({
                        type: 'hidden',
                        name: 'temp_my_files',
                        id: 'tempMyFilesInput'
                    });
                    $(this).append($input);
                }
                $input.val(JSON.stringify(myFiles));
            });

            // Initialize my file table on page load
            renderMyFileTable();

            // Function to render the family members table
            function renderFamilyTable() {
                const $tableBody = $('#familyTableBody');
                const $tableContainer = $('#familyTableContainer');
                const $noFamilyMessage = $('#noFamilyMessage');

                if (familyMembers.length === 0) {
                    if ($noFamilyMessage.length) {
                        $noFamilyMessage.show();
                    } else {
                        $tableContainer.html('<p class="text-muted" id="noFamilyMessage">Chưa có thông tin thành viên gia đình</p>');
                    }
                    $tableBody.closest('.table-responsive').hide();
                    return;
                }

                let html = '';
                familyMembers.forEach(function (member, idx) {
                    html += `<tr data-id="${member.id || ''}" data-idx="${idx}">
                    <td>${member.quan_he}</td>
                    <td>${member.ho_ten}</td>
                    <td>${member.ngay_sinh ? new Date(member.ngay_sinh).toLocaleDateString('vi-VN') : ''}</td>
                    <td>${member.nghe_nghiep || ''}</td>
                    <td>${member.dien_thoai || ''}</td>
                    <td>${member.dia_chi_lien_he || ''}</td>
                    <td><span class="badge ${member.la_nguoi_phu_thuoc ? 'bg-success' : 'bg-secondary'}">${member.la_nguoi_phu_thuoc ? 'Có' : 'Không'}</span></td>
                    <td>${member.ghi_chu || ''}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-primary me-1 edit-family" data-idx="${idx}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger delete-family" data-idx="${idx}" data-id="${member.id || ''}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>`;
                });

                if ($tableBody.length) {
                    $tableBody.html(html);
                    $tableBody.closest('.table-responsive').show();
                    $noFamilyMessage.hide();
                } else {
                    $tableContainer.html(`
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Quan hệ</th>
                                    <th>Họ tên</th>
                                    <th>Ngày sinh</th>
                                    <th>Nghề nghiệp</th>
                                    <th>Điện thoại</th>
                                    <th>Địa chỉ liên hệ</th>
                                    <th>Người phụ thuộc</th>
                                    <th>Ghi chú</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="familyTableBody">${html}</tbody>
                        </table>
                    </div>
                `);
                }
            }

            // Show/hide add family member form
            $('#showAddFamilyForm').click(function () {
                $('#addFamilyFormContainer').show();
                $('#addFamilyFormContainer').find('input, select, textarea').val('');
                $('#family_la_nguoi_phu_thuoc').prop('checked', false);
                $('#family_id').val('');
                $('#addFamilyMember').text('Lưu');
            });

            $('#cancelAddFamily').click(function () {
                $('#addFamilyFormContainer').hide();
            });

            // Handle add/edit family member button click
            $('#addFamilyMember').click(function () {
                const $form = $('#addFamilyFormContainer');
                const quan_he = $form.find('[name="quan_he"]').val();
                const ho_ten = $form.find('[name="ho_ten"]').val();
                const family_id = $form.find('[name="family_id"]').val();

                // Basic validation
                if (!quan_he || !ho_ten) {
                    alert('Vui lòng điền đầy đủ Quan hệ và Họ tên.');
                    return;
                }

                const member = {
                    id: family_id || null,
                    quan_he: quan_he,
                    ho_ten: ho_ten,
                    ngay_sinh: $form.find('[name="ngay_sinh"]').val() || null,
                    nghe_nghiep: $form.find('[name="nghe_nghiep"]').val() || null,
                    dien_thoai: $form.find('[name="dien_thoai"]').val() || null,
                    dia_chi_lien_he: $form.find('[name="dia_chi_lien_he"]').val() || null,
                    ghi_chu: $form.find('[name="ghi_chu"]').val() || null,
                    la_nguoi_phu_thuoc: $form.find('[name="la_nguoi_phu_thuoc"]').is(':checked'),
                    is_temp: !family_id // New members are marked as temporary
                };

                if (family_id) {
                    // Update existing member in array
                    const idx = familyMembers.findIndex(m => (m.id && m.id == family_id) || (m.is_temp && m === familyMembers[parseInt($form.data('edit-idx'))]));
                    if (idx !== -1) {
                        familyMembers[idx] = member;
                    }
                } else {
                    // Add new member
                    familyMembers.push(member);
                }

                renderFamilyTable();
                $('#addFamilyFormContainer').hide();
                $form.find('input, select, textarea').val('');
                $('#family_la_nguoi_phu_thuoc').prop('checked', false);
                $('#family_id').val('');
            });

            // Handle edit family member
            $(document).on('click', '.edit-family', function () {
                const idx = $(this).data('idx');
                const member = familyMembers[idx];
                const $form = $('#addFamilyFormContainer');

                $form.find('[name="quan_he"]').val(member.quan_he);
                $form.find('[name="ho_ten"]').val(member.ho_ten);
                $form.find('[name="ngay_sinh"]').val(member.ngay_sinh || '');
                $form.find('[name="nghe_nghiep"]').val(member.nghe_nghiep || '');
                $form.find('[name="dien_thoai"]').val(member.dien_thoai || '');
                $form.find('[name="dia_chi_lien_he"]').val(member.dia_chi_lien_he || '');
                $form.find('[name="ghi_chu"]').val(member.ghi_chu || '');
                $form.find('[name="la_nguoi_phu_thuoc"]').prop('checked', member.la_nguoi_phu_thuoc);
                $form.find('[name="family_id"]').val(member.id || '');
                $form.data('edit-idx', idx); // Store index for editing temporary members
                $('#addFamilyMember').text('Cập nhật');

                $('#addFamilyFormContainer').show();
            });

            // Handle delete family member (client-side for temporary, AJAX for database)
            $(document).on('click', '.delete-family', function () {
                const idx = $(this).data('idx');
                const memberId = $(this).data('id');

                if (memberId && !familyMembers[idx].is_temp) {
                    // Database member: use AJAX
                    if (confirm('Bạn có chắc chắn muốn xóa thành viên gia đình này?')) {
                        $.ajax({
                            url: '{{ route("nhan-vien.deleteFamilyMember", [$nhanVien->id, ":memberId"]) }}'.replace(':memberId', memberId),
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            success: function (data) {
                                if (data.success) {
                                    familyMembers.splice(idx, 1);
                                    renderFamilyTable();
                                } else {
                                    alert('Có lỗi xảy ra: ' + (data.message || 'Không thể xóa thành viên gia đình'));
                                }
                            },
                            error: function (error) {
                                console.error('Error:', error);
                                alert('Có lỗi xảy ra khi xóa thành viên gia đình');
                            }
                        });
                    }
                } else {
                    // Temporary member: remove from array
                    if (confirm('Bạn có chắc chắn muốn xóa thành viên gia đình này?')) {
                        familyMembers.splice(idx, 1);
                        renderFamilyTable();
                    }
                }
            });

            // Append family members to main form on submit
            $('#employeeForm').submit(function () {
                $('#addFamilyFormContainer').hide();
                let $input = $('#tempFamilyMembersInput');
                if ($input.length === 0) {
                    $input = $('<input>').attr({
                        type: 'hidden',
                        name: 'temp_family_members',
                        id: 'tempFamilyMembersInput'
                    });
                    $(this).append($input);
                }
                $input.val(JSON.stringify(familyMembers));
            });

            // Avatar preview
            window.previewAvatar = function (input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        $('#avatarPreview').html(`<img src="${e.target.result}" alt="Avatar" class="rounded-circle shadow" width="120" height="120" style="object-fit: cover;">`);
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            };

            // Initialize table on page load
            renderFamilyTable();
        });
    </script>
@endsection