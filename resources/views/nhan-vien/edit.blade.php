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
                            Xem chi tiết
                        </a>
                        <a href="{{ route('nhan-vien.index') }}" class="btn btn-outline-secondary">
                            Quay lại danh sách
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
                                        <ul class="nav nav-tabs flex-nowrap nav-tabs-scroll" id="employeeTabs" role="tablist">
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
                                                    data-bs-target="#myFile" type="button" role="tab">Giấy tờ & chứng chỉ</button>   
                                            </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="insurance-tab" data-bs-toggle="tab"
                                                        data-bs-target="#insurance" type="button" role="tab">Thông tin bảo hiểm</button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="documents-tab" data-bs-toggle="tab"
                                                        data-bs-target="#documents" type="button" role="tab">Tài liệu</button>
                                                </li>
                                        </ul>

                                        <div class="tab-content" id="employeeTabContent">
                                            <!-- Basic Info Tab -->
                                            <div class="tab-pane fade show active" id="basic" role="tabpanel">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <div class="card border-0 bg-light mb-4">
                                                            <div class="card-header bg-primary text-white">
                                                                <h5 class="mb-0">Thông tin cơ bản</h5>
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
                                                    <div class="col-md-7">
                                                        <div class="card border-0 bg-light mb-4">
                                                            <div class="card-header bg-info text-white">
                                                                <h5 class="mb-0">Thông tin bổ sung</h5>
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
                                                                <h5 class="mb-0">Thông tin công việc</h5>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="mb-3">
                                                                    <label for="phong_ban_id" class="form-label">Phòng ban</label>
                                                                    <select class="form-select" id="phong_ban_id"
                                                                        name="phong_ban_id">
                                                                        <option value="">Chọn phòng ban</option>
                                                                        @php
                                                                            function renderPhongBanOptions($phongBans, $selectedId, $level = 0) {
                                                                                foreach ($phongBans as $phongBan) {
                                                                                    $indent = str_repeat('&nbsp;&nbsp;&nbsp;', $level);
                                                                                    $selected = $selectedId == $phongBan->id ? 'selected' : '';
                                                                                    echo '<option value="' . $phongBan->id . '" ' . $selected . '>' . $indent . e($phongBan->ten_phong_ban) . '</option>';
                                                                                    if (!empty($phongBan->phongBanCon) && count($phongBan->phongBanCon)) {
                                                                                        renderPhongBanOptions($phongBan->phongBanCon, $selectedId, $level + 1);
                                                                                    }
                                                                                }
                                                                            }
                                                                        @endphp
                                                                        @php renderPhongBanOptions($phongBans, $nhanVien->phong_ban_id); @endphp
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
                                                                <h5 class="mb-0">Trạng thái &amp; Hợp đồng</h5>
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
                                                    <!-- Quá trình công tác Tab -->
                                                    @if(isset($nhanVien))
                                                        <div class="mb-3">
                                                            <div class="card-header">
                                                                <h5 class="mb-0">Quá trình công tác</h5>
                                                            </div>
                                                            <div class="card-body p-0">
                                                                <table class="table table-bordered mb-0" id="congTacTable">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Chức vụ</th>
                                                                            <th>Phòng ban</th>
                                                                            <th>Mô tả</th>
                                                                            <th>Từ ngày - Đến ngày</th>
                                                                            <th></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="congTacTableBody">
                                                                        @foreach(($nhanVien->quaTrinhCongTac ?? []) as $qt)
                                                                        <tr>
                                                                            <td>
                                                                                <select name="cong_tac_existing_chucvu[]" class="form-select">
                                                                                    <option value="">-- Chọn chức vụ --</option>
                                                                                    @foreach($chucVus as $cv)
                                                                                        <option value="{{ $cv->id }}" {{ $qt->chucvu_id == $cv->id ? 'selected' : '' }}>{{ $cv->ten_chuc_vu }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <select name="cong_tac_existing_phongban[]" class="form-select">
                                                                                    <option value="">-- Chọn phòng ban --</option>
                                                                                    @foreach($phongBans as $pb)
                                                                                        <option value="{{ $pb->id }}" {{ $qt->phongban_id == $pb->id ? 'selected' : '' }}>{{ $pb->ten_phong_ban }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </td>
                                                                            <td><input type="text" name="cong_tac_existing_mo_ta[]" class="form-control" value="{{ $qt->mo_ta }}"></td>
                                                                            <td>
                                                                                <div class="d-flex gap-2">
                                                                                    <input type="date" name="cong_tac_existing_ngay_bat_dau[]" class="form-control" value="{{ $qt->ngay_bat_dau }}">
                                                                                    <input type="date" name="cong_tac_existing_ngay_ket_thuc[]" class="form-control" value="{{ $qt->ngay_ket_thuc }}">
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <button type="button" class="btn btn-sm btn-danger delete-cong-tac">Xóa</button>
                                                                                <input type="hidden" name="cong_tac_existing[]" value="{{ $qt->id }}">
                                                                            </td>
                                                                        </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <tr>
                                                                            <td>
                                                                                <select id="new_chucvu_id" class="form-select">
                                                                                    <option value="">-- Chọn chức vụ --</option>
                                                                                    @foreach($chucVus as $cv)
                                                                                        <option value="{{ $cv->id }}">{{ $cv->ten_chuc_vu }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <select id="new_phongban_id" class="form-select">
                                                                                    <option value="">-- Chọn phòng ban --</option>
                                                                                    @foreach($phongBans as $pb)
                                                                                        <option value="{{ $pb->id }}">{{ $pb->ten_phong_ban }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </td>
                                                                            <td>
                                                                                <input type="text" id="new_mo_ta" class="form-control" placeholder="Mô tả">
                                                                            </td>
                                                                            <td>
                                                                                <div class="d-flex gap-2">
                                                                                    <input type="date" id="new_ngay_bat_dau" class="form-control">
                                                                                    <input type="date" id="new_ngay_ket_thuc" class="form-control">
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <button type="button" class="btn btn-sm btn-success" id="addCongTacRow">Thêm</button>
                                                                            </td>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <!-- Thông tin lương -->
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="card border-0 bg-light mb-4">
                                                            <div class="card-header bg-warning text-dark">
                                                                <h5 class="mb-0">Thông tin lương</h5>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-3">
                                                                        <div class="mb-3">
                                                                            <label for="luong_co_ban" class="form-label">Lương cơ bản</label>
                                                                            <input type="text" class="form-control bg-light" id="luong_co_ban" name="luong_co_ban" value="{{ optional($nhanVien->thongTinLuong) && optional($nhanVien->thongTinLuong)->luong_co_ban ? number_format(optional($nhanVien->thongTinLuong)->luong_co_ban, 0, ',', '.') : '' }}" readonly>
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
                                                                <h5 class="mb-0">Số điện thoại</h5>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="mb-3">
                                                                    <label for="dien_thoai_di_dong" class="form-label">Điện thoại di động</label>
                                                                    <input type="text" class="form-control" id="dien_thoai_di_dong" name="dien_thoai_di_dong" value="{{ $nhanVien->thongTinLienHe->dien_thoai_di_dong ?? '' }}">
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
                                                                <h5 class="mb-0">Email</h5>
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
                                                                    <h5 class="mb-0">Địa chỉ</h5>
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
                                                                <h5 class="mb-0">Liên hệ khẩn cấp</h5>
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
                                                        Thêm thành viên
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
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Quan hệ</th>
                                                                        <th>Họ tên</th>
                                                                        <th>Ngày sinh</th>
                                                                        <th>Nghề nghiệp</th>
                                                                        <th>Điện thoại</th>
                                                                        <th>Địa chỉ liên hệ</th>
                                                                        <th>Ghi chú</th>
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
                                                                            <td>{{ $member->ghi_chu }}</td>
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
                                                        <h5>Giấy tờ & chứng chỉ</h5>
                                                        <button type="button" class="btn btn-primary btn-sm mb-3" id="showAddMyFileForm">
                                                            Thêm giấy tờ
                                                        </button>
                                                    </div>

                                                    <!-- Form thêm/sửa -->
                                                    <div id="addMyFileFormContainer" style="display:none;">
                                                        <div class="border rounded p-3 mb-4 bg-light">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Loại giấy tờ <span class="text-danger">*</span></label>
                                                                        <select class="form-select" id="loai_giay_to">
                                                                            <option value="">Chọn loại giấy tờ</option>
                                                                            <option value="giay_to_tuy_than">Giấy tờ tùy thân</option>
                                                                            <option value="chung_chi">Chứng chỉ</option>
                                                                            <option value="bang_cap">Bằng cấp</option>
                                                                            <option value="khac">Khác</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Số giấy tờ <span class="text-danger">*</span></label>
                                                                        <input type="text" class="form-control" id="so_giay_to">
                                                                    </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Tên</label>
                                                                    <textarea class="form-control" id="ghi_chu" rows="2"></textarea>
                                                                </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Ngày cấp</label>
                                                                        <input type="date" class="form-control" id="ngay_cap">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Ngày hết hạn</label>
                                                                        <input type="date" class="form-control" id="ngay_het_han">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Nơi cấp</label>
                                                                        <input type="text" class="form-control" id="noi_cap">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Tệp đính kèm (PDF, ảnh)</label>
                                                                <input type="file" class="form-control" id="tep_tin" accept=".pdf,image/*">
                                                            </div>
                                                            <div class="d-flex gap-2">
                                                                <button type="button" class="btn btn-secondary" id="cancelAddMyFile">Hủy</button>
                                                                <button type="button" class="btn btn-primary" id="addMyFile">Lưu</button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Bảng giấy tờ -->
                                                    <div id="myFileTableContainer" class="card shadow-sm border-0 rounded-3">
                                                        <div class="card-header bg-white py-2">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <h6 class="mb-0 text-dark">
                                                                    Danh sách giấy tờ
                                                                </h6>
                                                                <div class="text-muted small">
                                                                    Tổng số: {{ $nhanVien->GiayToTuyThan->count() }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="table-responsive" style="max-height: 340px; overflow-y: auto;">
                                                            <table class="table table-hover align-middle mb-0" id="myFileTable" style="font-size: 0.97rem;">
                                                                <thead class="bg-light">
                                                                    <tr>
                                                                        <th class="px-3 py-2">Loại giấy tờ</th>
                                                                        <th class="px-3 py-2">Tên</th>
                                                                        <th class="px-3 py-2">Số giấy tờ</th>
                                                                        <th class="px-3 py-2">Ngày cấp</th>
                                                                        <th class="px-3 py-2">Ngày hết hạn</th>
                                                                        <th class="px-3 py-2">Nơi cấp</th>
                                                                        <th class="px-3 py-2">Tệp đính kèm</th>
                                                                        <th class="px-3 py-2">Thao tác</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($nhanVien->GiayToTuyThan as $idx => $file)
                                                                        <tr>
                                                                            <td class="px-4">
                                                                                <div class="d-flex align-items-center">
                                                                                    <i class="fas fa-file-alt text-primary me-2"></i>
                                                                                    <span>{{ ucfirst(str_replace('_', ' ', $file->loai_giay_to)) }}</span>
                                                                                </div>
                                                                            </td>
                                                                            <td class="px-4">{{ $file->ghi_chu }}</td>
                                                                            <td class="px-4">
                                                                                <span class="badge bg-light text-dark">
                                                                                    {{ $file->so_giay_to }}
                                                                                </span>
                                                                            </td>
                                                                            <td class="px-4">
                                                                                @if($file->ngay_cap)
                                                                                    <span class="text-muted">
                                                                                        <i class="far fa-calendar-alt me-1"></i>
                                                                                        {{ \Carbon\Carbon::parse($file->ngay_cap)->format('d/m/Y') }}
                                                                                    </span>
                                                                                @endif
                                                                            </td>
                                                                            <td class="px-4">
                                                                                @if($file->ngay_het_han)
                                                                                    <span class="text-muted">
                                                                                        <i class="far fa-calendar-times me-1"></i>
                                                                                        {{ \Carbon\Carbon::parse($file->ngay_het_han)->format('d/m/Y') }}
                                                                                    </span>
                                                                                @endif
                                                                            </td>
                                                                            <td class="px-4">{{ $file->noi_cap }}</td>
                                                                            <td class="px-4">
                                                                                <div class="upload-section">
                                                                                    <div class="custom-file-upload mb-2">
                                                                                        <label for="file-{{ $idx }}" class="btn btn-outline-primary btn-sm w-100">
                                                                                            <i class="fas fa-upload me-1"></i> Chọn file
                                                                                        </label>
                                                                                        <input type="file" 
                                                                                            id="file-{{ $idx }}"
                                                                                            name="giay_to[{{ $idx }}][tep_tin]" 
                                                                                            accept=".pdf,image/*" 
                                                                                            class="d-none"
                                                                                            onchange="showFileName(this, {{ $idx }})">
                                                                                    </div>

                                                                                    @if(!empty($file->tepTin))
                                                                                        <div class="current-file">
                                                                                            <a href="{{ asset('storage/' . $file->tepTin->duong_dan_tep) }}" 
                                                                                                class="btn btn-link btn-sm text-decoration-none p-0"
                                                                                                target="_blank">
                                                                                                <i class="fas fa-file-download me-1"></i>
                                                                                                {{ $file->tepTin->ten_tep }}
                                                                                            </a>
                                                                                        </div>
                                                                                        <input type="hidden" name="giay_to[{{ $idx }}][tep_tin_id]" value="{{ $file->tep_tin_id }}">
                                                                                    @endif

                                                                                <div class="selected-file mt-1">
                                                                                    <small id="file-name-{{ $idx }}" class="text-primary"></small>
                                                                                </div>

                                                                                {{-- Hidden fields giữ dữ liệu cũ --}}
                                                                                <input type="hidden" name="giay_to[{{ $idx }}][id]" value="{{ $file->id }}">
                                                                                <input type="hidden" name="giay_to[{{ $idx }}][loai_giay_to]" value="{{ $file->loai_giay_to }}">
                                                                                <input type="hidden" name="giay_to[{{ $idx }}][so_giay_to]" value="{{ $file->so_giay_to }}">
                                                                                <input type="hidden" name="giay_to[{{ $idx }}][ngay_cap]" value="{{ $file->ngay_cap }}">
                                                                                <input type="hidden" name="giay_to[{{ $idx }}][ngay_het_han]" value="{{ $file->ngay_het_han }}">
                                                                                <input type="hidden" name="giay_to[{{ $idx }}][noi_cap]" value="{{ $file->noi_cap }}">
                                                                                <input type="hidden" name="giay_to[{{ $idx }}][ghi_chu]" value="{{ $file->ghi_chu }}">
                                                                            </td>

                                                                            <td class="px-4">
                                                                                <div class="d-flex gap-1">
                                                                                    <button type="button" class="btn btn-outline-danger btn-sm delete-my-file">
                                                                                        <i class="fas fa-trash-alt"></i>
                                                                                    </button>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>


                                                <!-- Insurance Tab -->
                                                <div class="tab-pane fade" id="insurance" role="tabpanel">
                                                    <div class="card border-0 bg-light mb-4">
                                                        <div class="card-header bg-info text-white">
                                                            <h5 class="mb-0">Thông tin bảo hiểm</h5>
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

                                                <!-- Documents Tab -->
                                                <div class="tab-pane fade" id="documents" role="tabpanel">
                                                    <div class="card border-0 shadow-sm">
                                                        <div class="card-header bg-primary text-white">
                                                            <h5 class="mb-0">Tài liệu đính kèm</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            @if($nhanVien->tepTin && $nhanVien->tepTin->count() > 0)
                                                                <div class="table-responsive">
                                                                    <table class="table table-hover">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>Loại tệp</th>
                                                                                <th>Tên tệp</th>
                                                                                <th>Ngày tải lên</th>
                                                                                <th>Thao tác</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach($nhanVien->tepTin as $file)
                                                                            <tr>
                                                                                <td>
                                                                                    <span class="badge bg-secondary">{{ $file->loai_tep ?? 'Khác' }}</span>
                                                                                </td>
                                                                                <td>{{ $file->ten_tep }}</td>
                                                                                <td>{{ \Carbon\Carbon::parse($file->created_at)->format('d/m/Y H:i') }}</td>
                                                                                <td>
                                                                                    @if($file->duong_dan_tep)
                                                                                        <a href="{{ asset('storage/' . $file->duong_dan_tep) }}" 
                                                                                           class="btn btn-sm btn-primary" 
                                                                                           target="_blank">
                                                                                            <i class="fas fa-eye"></i> Xem
                                                                                        </a>
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            @else
                                                                <div class="text-center py-5">
                                                                    <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                                                                    <p class="text-muted fs-5">Chưa có tài liệu nào được upload</p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4 px-4">
                                <a href="{{ route('nhan-vien.show', $nhanVien->id) }}" class="btn btn-outline-secondary">
                                    Hủy
                                </a>
                                <button type="submit" class="btn btn-success">
                                    Lưu thay đổi
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
            // Xóa dòng quá trình công tác đã có trong DB (existing rows)
            $('#congTacTableBody').on('click', '.delete-cong-tac', function () {
                var $tr = $(this).closest('tr');
                var id = $tr.find('input[name="cong_tac_existing[]"]').val();
                // Xóa toàn bộ input/select trong dòng này để không submit lên backend
                $tr.find('input, select, textarea').remove();
                $tr.remove();
                if (id) {
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'cong_tac_delete[]',
                        value: id
                    }).appendTo('#employeeForm');
                }
            });

            let tempCongTac = [];

            // Hàm render lại bảng quá trình công tác mới
            function renderCongTacTable() {
                const $tbody = $('#congTacTableBody');
                $tbody.find('.temp-cong-tac-row').remove();
                // Re-render all temp rows with correct data-idx
                for (let i = 0; i < tempCongTac.length; i++) {
                    const row = tempCongTac[i];
                    const chucvuText = $('#new_chucvu_id option[value="' + row.chucvu_id + '"]').text();
                    const phongbanText = $('#new_phongban_id option[value="' + row.phongban_id + '"]').text();
                    $tbody.append(`
                        <tr class="temp-cong-tac-row">
                            <td>${chucvuText}</td>
                            <td>${phongbanText}</td>
                            <td>${row.mo_ta || ''}</td>
                            <td>${row.ngay_bat_dau} / ${row.ngay_ket_thuc || '...'}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-danger delete-temp-cong-tac" data-idx="${i}">Xóa</button>
                            </td>
                        </tr>
                    `);
                }
            }

            // Thêm dòng mới
            $('#addCongTacRow').click(function () {
                const chucvu_id = $('#new_chucvu_id').val();
                const phongban_id = $('#new_phongban_id').val();
                const mo_ta = $('#new_mo_ta').val();
                const ngay_bat_dau = $('#new_ngay_bat_dau').val();
                const ngay_ket_thuc = $('#new_ngay_ket_thuc').val();

                if (!chucvu_id || !phongban_id || !ngay_bat_dau) {
                    alert('Vui lòng chọn đầy đủ chức vụ, phòng ban, ngày bắt đầu');
                    return;
                }

                tempCongTac.push({ chucvu_id, phongban_id, mo_ta, ngay_bat_dau, ngay_ket_thuc });
                renderCongTacTable();
                console.log("Sau khi thêm:", tempCongTac);

                // reset input
                $('#new_chucvu_id').val('');
                $('#new_phongban_id').val('');
                $('#new_mo_ta').val('');
                $('#new_ngay_bat_dau').val('');
                $('#new_ngay_ket_thuc').val('');
            });

            // Xóa dòng tạm
            $('#congTacTableBody').on('click', '.delete-temp-cong-tac', function () {
                const idx = $(this).data('idx');
                tempCongTac.splice(idx, 1);
                renderCongTacTable();
                console.log("Sau khi xóa:", tempCongTac);
            });

            // Submit form
            $('#employeeForm').submit(function (e) {
                // Xóa input cũ
                $('input[name^="cong_tac_temp"]').remove();

                // Append input mới
                tempCongTac.forEach(function (row, i) {
                    $('<input>', {
                        type: 'hidden',
                        name: `cong_tac_temp[${i}][chucvu_id]`,
                        value: row.chucvu_id
                    }).appendTo(e.target);

                    $('<input>', {
                        type: 'hidden',
                        name: `cong_tac_temp[${i}][phongban_id]`,
                        value: row.phongban_id
                    }).appendTo(e.target);

                    $('<input>', {
                        type: 'hidden',
                        name: `cong_tac_temp[${i}][mo_ta]`,
                        value: row.mo_ta
                    }).appendTo(e.target);

                    $('<input>', {
                        type: 'hidden',
                        name: `cong_tac_temp[${i}][ngay_bat_dau]`,
                        value: row.ngay_bat_dau
                    }).appendTo(e.target);

                    $('<input>', {
                        type: 'hidden',
                        name: `cong_tac_temp[${i}][ngay_ket_thuc]`,
                        value: row.ngay_ket_thuc
                    }).appendTo(e.target);
                });

                // Log ra trước khi submit
                console.log("tempCongTac trước submit:", tempCongTac);
                console.log("Form data serialize:", $(this).serializeArray());

                return true; // cho form gửi tiếp
            });

            let familyMembers = @json($nhanVien->thongTinGiaDinh ?? []);

 let idxCounter = $("#myFileTable tbody tr").length; // bắt đầu từ số row hiện có

// Hiển thị form thêm
$("#showAddMyFileForm").click(function () {
    $("#addMyFileFormContainer").show();
    $("#addMyFileFormContainer input, #addMyFileFormContainer textarea, #addMyFileFormContainer select").val("");
});

// Ẩn form thêm
$("#cancelAddMyFile").click(function () {
    $("#addMyFileFormContainer").hide();
});

// Thêm giấy tờ
$("#addMyFile").click(function () {
    const loai_giay_to = $("#loai_giay_to").val();
    const so_giay_to   = $("#so_giay_to").val();
    const ngay_cap     = $("#ngay_cap").val();
    const ngay_het_han = $("#ngay_het_han").val();
    const noi_cap      = $("#noi_cap").val();
    const ghi_chu      = $("#ghi_chu").val();
    const $fileInput   = $("#tep_tin");

    if (!loai_giay_to || !so_giay_to) {
        alert("Vui lòng nhập Loại giấy tờ và Số giấy tờ");
        return;
    }

    const currentIdx = idxCounter++;

    // Tạo 1 row mới
    const $tr = $(`
        <tr>
            <td>${loai_giay_to}</td>
            <td>${so_giay_to}</td>
            <td>${ngay_cap}</td>
            <td>${ngay_het_han}</td>
            <td>${noi_cap}</td>
            <td>${ghi_chu}</td>
            <td class="file-cell"></td>
            <td><button type="button" class="btn btn-sm btn-outline-danger delete-my-file">Xóa</button></td>
        </tr>
    `);

    // Move input file thật vào trong row
    $fileInput.attr("name", `giay_to[${currentIdx}][tep_tin]`);
    $tr.find(".file-cell").append($fileInput);

    // Thêm hidden input để submit metadata
    $tr.append(`<input type="hidden" name="giay_to[${currentIdx}][loai_giay_to]" value="${loai_giay_to}">`);
    $tr.append(`<input type="hidden" name="giay_to[${currentIdx}][so_giay_to]" value="${so_giay_to}">`);
    $tr.append(`<input type="hidden" name="giay_to[${currentIdx}][ngay_cap]" value="${ngay_cap}">`);
    $tr.append(`<input type="hidden" name="giay_to[${currentIdx}][ngay_het_han]" value="${ngay_het_han}">`);
    $tr.append(`<input type="hidden" name="giay_to[${currentIdx}][noi_cap]" value="${noi_cap}">`);
    $tr.append(`<input type="hidden" name="giay_to[${currentIdx}][ghi_chu]" value="${ghi_chu}">`);

    $("#myFileTable tbody").append($tr);

    // Reset form và tạo lại input file mới
    $("#addMyFileFormContainer input, #addMyFileFormContainer textarea, #addMyFileFormContainer select").val("");
    $("#addMyFileFormContainer .mb-3:has(#tep_tin)").html(`
        <label class="form-label">Tệp đính kèm (PDF, ảnh)</label>
        <input type="file" class="form-control" id="tep_tin" accept=".pdf,image/*">
    `);

    $("#addMyFileFormContainer").hide();
});

// Xóa giấy tờ
$(document).on("click", ".delete-my-file", function () {
    $(this).closest("tr").remove();
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


            // Function to render the my file table (preview newly added files)
            function renderMyFileTable() {
                // You can implement preview logic here if needed, or leave empty if not required
                // For now, this is a stub to prevent JS error
            }

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

            // Quá trình công tác script
            // ...existing code...
        });
    </script>
@endsection