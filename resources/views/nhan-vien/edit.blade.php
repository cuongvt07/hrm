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
                                                                        name="ngay_vao_lam" value="{{ $nhanVien->ngay_vao_lam ? \Carbon\Carbon::parse($nhanVien->ngay_vao_lam)->format('Y-m-d') : '' }}">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="ngay_thu_viec" class="form-label">Ngày thử
                                                                        việc</label>
                                                                    <input type="date" class="form-control" id="ngay_thu_viec"
                                                                        name="ngay_thu_viec" value="{{ $nhanVien->ngay_thu_viec ? \Carbon\Carbon::parse($nhanVien->ngay_thu_viec)->format('Y-m-d') : '' }}">
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
                                                                            <td>
                                                                                <div class="d-flex gap-2">
                                                                                    @php
                                                                                        $moTaArr = [];
                                                                                        try {
                                                                                            $moTaArr = is_string($qt->mo_ta) ? json_decode($qt->mo_ta, true) ?? [] : (is_array($qt->mo_ta) ? $qt->mo_ta : []);
                                                                                        } catch (\Throwable $e) {
                                                                                            $moTaArr = [];
                                                                                        }
                                                                                    @endphp
                                                                                    <input type="text" class="form-control existing_vi_tri" placeholder="Vị trí" value="{{ $moTaArr['vi_tri'] ?? '' }}">
                                                                                    <input type="text" class="form-control existing_luong" placeholder="Lương" value="{{ $moTaArr['luong'] ?? '' }}">
                                                                                </div>
                                                                                @php
                                                                                    $moTaValue = is_array($qt->mo_ta) ? json_encode($qt->mo_ta, JSON_UNESCAPED_UNICODE) : ($qt->mo_ta ?? '');
                                                                                @endphp
                                                                                <input type="hidden" name="cong_tac_existing_mo_ta[]" class="cong_tac_existing_mo_ta_hidden" value="{{ $moTaValue }}">
                                                                            </td>
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
                                                                                <div class="d-flex gap-2">
                                                                                    <input type="text" id="new_vi_tri" class="form-control" placeholder="Vị trí">
                                                                                    <input type="text" id="new_luong" class="form-control" placeholder="Lương">
                                                                                </div>
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
                                                                            <input type="text" class="form-control bg-light" id="luong_co_ban" name="luong_co_ban" value="{{ optional($nhanVien->thongTinLuong) && optional($nhanVien->thongTinLuong)->luong_co_ban ? number_format((float) optional($nhanVien->thongTinLuong)->luong_co_ban, 0, ',', '.') : '' }}" readonly>
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
                                                                    @foreach($nhanVien->thongTinGiaDinh as $idx => $member)
                                                                        <tr data-id="{{ $member->id }}" data-idx="{{ $idx }}">
                                                                            <td>{{ $member->quan_he }}</td>
                                                                            <td>{{ $member->ho_ten }}</td>
                                                                            <td>{{ $member->ngay_sinh ? \Carbon\Carbon::parse($member->ngay_sinh)->format('d/m/Y') : '' }}</td>
                                                                            <td>{{ $member->nghe_nghiep }}</td>
                                                                            <td>{{ $member->dien_thoai }}</td>
                                                                            <td>{{ $member->dia_chi_lien_he }}</td>
                                                                            <td><span class="badge {{ $member->la_nguoi_phu_thuoc ? 'bg-success' : 'bg-secondary' }}">{{ $member->la_nguoi_phu_thuoc ? 'Có' : 'Không' }}</span></td>
                                                                            <td>{{ $member->ghi_chu }}</td>
                                                                            <td>
                                                                                <button type="button" class="btn btn-sm btn-outline-primary edit-family" data-idx="{{ $idx }}" data-id="{{ $member->id }}">Sửa</button>
                                                                                <button type="button" class="btn btn-sm btn-outline-danger delete-family" data-idx="{{ $idx }}" data-id="{{ $member->id }}">Xóa</button>
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
                                                                            <option value="chung_nhan">Chứng nhận</option>
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
                                                                    <tr class="text-center">
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
                                                                                    <span>
                                                                                        @switch($file->loai_giay_to)
                                                                                            @case('giay_to_tuy_than')
                                                                                                Giấy tờ tùy thân
                                                                                                @break
                                                                                            @case('chung_chi')
                                                                                                Chứng chỉ
                                                                                                @break
                                                                                            @case('bang_cap')
                                                                                                Bằng cấp
                                                                                                @break
                                                                                            @case('chung_nhan')
                                                                                                Chứng nhận
                                                                                                @break
                                                                                            @case('khac')
                                                                                                Khác
                                                                                                @break
                                                                                            @default
                                                                                                {{ ucfirst(str_replace('_', ' ', $file->loai_giay_to)) }}
                                                                                        @endswitch
                                                                                    </span>
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
                                                                                        {{ \Carbon\Carbon::parse($file->ngay_cap)->format('d/m/Y') }}
                                                                                    </span>
                                                                                @endif
                                                                            </td>
                                                                            <td class="px-4">
                                                                                @if($file->ngay_het_han)
                                                                                    <span class="text-muted">
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
    console.log('=== SCRIPT KHỞI TẠO ===');

    // ============================================================
    // 1. XỬ LÝ QUÁ TRÌNH CÔNG TÁC
    // ============================================================
    
    // Xóa dòng quá trình công tác đã có trong DB (existing rows)
    $('#congTacTableBody').on('click', '.delete-cong-tac', function () {
        var $tr = $(this).closest('tr');
        var id = $tr.find('input[name="cong_tac_existing[]"]').val();
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
        
        for (let i = 0; i < tempCongTac.length; i++) {
            const row = tempCongTac[i];
            const chucvuText = $('#new_chucvu_id option[value="' + row.chucvu_id + '"]').text();
            const phongbanText = $('#new_phongban_id option[value="' + row.phongban_id + '"]').text();
            
            let moTaDisplay = '';
            try {
                if (row.mo_ta) {
                    const parsed = typeof row.mo_ta === 'string' ? JSON.parse(row.mo_ta) : row.mo_ta;
                    if (parsed) {
                        moTaDisplay = (parsed.vi_tri || '') + (parsed.luong ? (' / ' + parsed.luong) : '');
                    }
                }
            } catch (e) {
                moTaDisplay = row.mo_ta || '';
            }

            $tbody.append(`
                <tr class="temp-cong-tac-row">
                    <td>${chucvuText}</td>
                    <td>${phongbanText}</td>
                    <td>${moTaDisplay}</td>
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
        const vi_tri = $('#new_vi_tri').val();
        const luong = $('#new_luong').val();
        const mo_ta = JSON.stringify({ vi_tri: vi_tri || '', luong: luong || '' });
        const ngay_bat_dau = $('#new_ngay_bat_dau').val();
        const ngay_ket_thuc = $('#new_ngay_ket_thuc').val();

        if (!chucvu_id || !phongban_id || !ngay_bat_dau) {
            alert('Vui lòng chọn đầy đủ chức vụ, phòng ban, ngày bắt đầu');
            return;
        }

        tempCongTac.push({ chucvu_id, phongban_id, mo_ta, ngay_bat_dau, ngay_ket_thuc });
        renderCongTacTable();
        console.log("Sau khi thêm công tác:", tempCongTac);

        // reset input
        $('#new_chucvu_id').val('');
        $('#new_phongban_id').val('');
        $('#new_vi_tri').val('');
        $('#new_luong').val('');
        $('#new_ngay_bat_dau').val('');
        $('#new_ngay_ket_thuc').val('');
    });

    // Xóa dòng tạm
    $('#congTacTableBody').on('click', '.delete-temp-cong-tac', function () {
        const idx = $(this).data('idx');
        tempCongTac.splice(idx, 1);
        renderCongTacTable();
        console.log("Sau khi xóa công tác:", tempCongTac);
    });

    // ============================================================
    // 2. XỬ LÝ GIA ĐÌNH
    // ============================================================
    
    let familyMembers = @json($nhanVien->thongTinGiaDinh ?? []);
    console.log('familyMembers ban đầu:', familyMembers);

    // Function to render the family members table
    function renderFamilyTable() {
        console.log('=== RENDER FAMILY TABLE ===');
        console.log('familyMembers hiện tại:', familyMembers);
        
        const $tableBody = $('#familyTableBody');
        const $tableContainer = $('#familyTableContainer');
        const $noFamilyMessage = $('#noFamilyMessage');

        if (familyMembers.length === 0) {
            console.log('Không có thành viên nào');
            if ($noFamilyMessage.length) {
                $noFamilyMessage.show();
            } else {
                $tableContainer.html('<p class="text-muted" id="noFamilyMessage">Chưa có thông tin thành viên gia đình</p>');
            }
            $tableBody.closest('.table-responsive').hide();
            return;
        }

        console.log('Có', familyMembers.length, 'thành viên');
        
        let html = '';
        familyMembers.forEach(function (member, idx) {
            const ngaySinh = member.ngay_sinh ? new Date(member.ngay_sinh).toLocaleDateString('vi-VN') : '';
            html += `<tr data-id="${member.id || ''}" data-idx="${idx}">
                <td>${member.quan_he || ''}</td>
                <td>${member.ho_ten || ''}</td>
                <td>${ngaySinh}</td>
                <td>${member.nghe_nghiep || ''}</td>
                <td>${member.dien_thoai || ''}</td>
                <td>${member.dia_chi_lien_he || ''}</td>
                <td><span class="badge ${member.la_nguoi_phu_thuoc ? 'bg-success' : 'bg-secondary'}">${member.la_nguoi_phu_thuoc ? 'Có' : 'Không'}</span></td>
                <td>${member.ghi_chu || ''}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-primary edit-family" data-idx="${idx}">Sửa</button>
                    <button type="button" class="btn btn-sm btn-outline-danger delete-family" data-idx="${idx}">Xóa</button>
                </td>
            </tr>`;
        });

        if ($tableBody.length) {
            $tableBody.html(html);
            $tableBody.closest('.table-responsive').show();
            if ($noFamilyMessage.length) $noFamilyMessage.hide();
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

    // Show add family member form
    $('#showAddFamilyForm').click(function () {
        console.log('=== HIỂN THỊ FORM THÊM GIA ĐÌNH ===');
        $('#addFamilyFormContainer').show();
        $('#family_quan_he').val('');
        $('#family_ho_ten').val('');
        $('#family_ngay_sinh').val('');
        $('#family_nghe_nghiep').val('');
        $('#family_dien_thoai').val('');
        $('#family_dia_chi').val('');
        $('#family_ghi_chu').val('');
        $('#family_la_nguoi_phu_thuoc').prop('checked', false);
        $('#family_id').val('');
        $('#addFamilyMember').text('Lưu');
        $('#addFamilyFormContainer').removeData('edit-idx');
    });

    // Hide add family member form
    $('#cancelAddFamily').click(function () {
        $('#addFamilyFormContainer').hide();
    });

    // Add/Update family member
    $('#addFamilyMember').click(function () {
        console.log('=== CLICK LƯU THÀNH VIÊN GIA ĐÌNH ===');
        
        const quan_he = $('#family_quan_he').val();
        const ho_ten = $('#family_ho_ten').val();
        const family_id = $('#family_id').val();
        const editIdx = $('#addFamilyFormContainer').data('edit-idx');

        console.log('Quan hệ:', quan_he);
        console.log('Họ tên:', ho_ten);
        console.log('Family ID:', family_id);
        console.log('Edit Index:', editIdx);

        if (!quan_he || !ho_ten) {
            alert('Vui lòng điền đầy đủ Quan hệ và Họ tên.');
            return;
        }

        const member = {
            id: family_id || null,
            quan_he: quan_he,
            ho_ten: ho_ten,
            ngay_sinh: $('#family_ngay_sinh').val() || null,
            nghe_nghiep: $('#family_nghe_nghiep').val() || null,
            dien_thoai: $('#family_dien_thoai').val() || null,
            dia_chi_lien_he: $('#family_dia_chi').val() || null,
            ghi_chu: $('#family_ghi_chu').val() || null,
            la_nguoi_phu_thuoc: $('#family_la_nguoi_phu_thuoc').is(':checked'),
            is_temp: !family_id // Nếu có ID từ DB thì is_temp = false, nếu mới thêm thì is_temp = true
        };

        console.log('Member data:', member);

        if (editIdx !== undefined && editIdx !== null) {
            console.log('Đang update member tại index', editIdx);
            familyMembers[editIdx] = member;
        } else {
            console.log('Đang thêm member mới');
            familyMembers.push(member);
        }

        console.log('familyMembers sau khi xử lý:', familyMembers);
        
        renderFamilyTable();
        $('#addFamilyFormContainer').hide();
        
        // Reset form
        $('#family_quan_he').val('');
        $('#family_ho_ten').val('');
        $('#family_ngay_sinh').val('');
        $('#family_nghe_nghiep').val('');
        $('#family_dien_thoai').val('');
        $('#family_dia_chi').val('');
        $('#family_ghi_chu').val('');
        $('#family_la_nguoi_phu_thuoc').prop('checked', false);
        $('#family_id').val('');
        $('#addFamilyMember').text('Lưu');
        $('#addFamilyFormContainer').removeData('edit-idx');
    });

    // Edit family member
    $(document).on('click', '.edit-family', function () {
        console.log('=== CLICK SỬA GIA ĐÌNH ===');
        const idx = $(this).data('idx');
        console.log('Edit index:', idx);
        
        const member = familyMembers[idx];
        console.log('Member data:', member);

        $('#family_quan_he').val(member.quan_he || '');
        $('#family_ho_ten').val(member.ho_ten || '');
        $('#family_ngay_sinh').val(member.ngay_sinh || '');
        $('#family_nghe_nghiep').val(member.nghe_nghiep || '');
        $('#family_dien_thoai').val(member.dien_thoai || '');
        $('#family_dia_chi').val(member.dia_chi_lien_he || '');
        $('#family_ghi_chu').val(member.ghi_chu || '');
        $('#family_la_nguoi_phu_thuoc').prop('checked', member.la_nguoi_phu_thuoc || false);
        $('#family_id').val(member.id || '');
        $('#addFamilyMember').text('Cập nhật');
        $('#addFamilyFormContainer').data('edit-idx', idx);

        $('#addFamilyFormContainer').show();
    });

    // Delete family member
    $(document).on('click', '.delete-family', function () {
        console.log('=== CLICK XÓA GIA ĐÌNH ===');
        const idx = $(this).data('idx');
        const member = familyMembers[idx];
        console.log('Delete index:', idx);
        console.log('Member:', member);

        if (!confirm('Bạn có chắc chắn muốn xóa thành viên gia đình này?')) {
            return;
        }

        if (member.id) {
            console.log('Xóa member từ DB, ID:', member.id);
            $.ajax({
                url: '{{ route("nhan-vien.deleteFamilyMember", [$nhanVien->id, ":memberId"]) }}'.replace(':memberId', member.id),
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function (data) {
                    console.log('AJAX success:', data);
                    if (data.success) {
                        familyMembers.splice(idx, 1);
                        renderFamilyTable();
                        console.log('Đã xóa thành công');
                    } else {
                        alert('Có lỗi xảy ra: ' + (data.message || 'Không thể xóa thành viên gia đình'));
                    }
                },
                error: function (error) {
                    console.error('AJAX error:', error);
                    alert('Có lỗi xảy ra khi xóa thành viên gia đình');
                }
            });
        } else {
            console.log('Xóa member tạm thời');
            familyMembers.splice(idx, 1);
            renderFamilyTable();
        }
    });

    // ============================================================
    // 3. XỬ LÝ GIẤY TỜ (MY FILE)
    // ============================================================
    
    let idxCounter = $("#myFileTable tbody tr").length;

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

        const $tr = $(`
            <tr>
                <td>${loai_giay_to}</td>
                <td>${ghi_chu}</td>
                <td>${so_giay_to}</td>
                <td>${ngay_cap}</td>
                <td>${ngay_het_han}</td>
                <td>${noi_cap}</td>
                <td class="file-cell"></td>
                <td><button type="button" class="btn btn-sm btn-outline-danger delete-my-file">Xóa</button></td>
            </tr>
        `);

        $fileInput.attr("name", `giay_to[${currentIdx}][tep_tin]`);
        $tr.find(".file-cell").append($fileInput);

        $tr.append(`<input type="hidden" name="giay_to[${currentIdx}][loai_giay_to]" value="${loai_giay_to}">`);
        $tr.append(`<input type="hidden" name="giay_to[${currentIdx}][so_giay_to]" value="${so_giay_to}">`);
        $tr.append(`<input type="hidden" name="giay_to[${currentIdx}][ngay_cap]" value="${ngay_cap}">`);
        $tr.append(`<input type="hidden" name="giay_to[${currentIdx}][ngay_het_han]" value="${ngay_het_han}">`);
        $tr.append(`<input type="hidden" name="giay_to[${currentIdx}][noi_cap]" value="${noi_cap}">`);
        $tr.append(`<input type="hidden" name="giay_to[${currentIdx}][ghi_chu]" value="${ghi_chu}">`);

        $("#myFileTable tbody").append($tr);

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

    // ============================================================
    // 4. SUBMIT FORM - QUAN TRỌNG
    // ============================================================
    
    $('#employeeForm').submit(function (e) {
        console.log('=== FORM SUBMIT ===');
        
        // 1. Xử lý công tác existing
        $('.cong_tac_existing_mo_ta_hidden').each(function () {
            const $hidden = $(this);
            const $tr = $hidden.closest('tr');
            const vi_tri = $tr.find('.existing_vi_tri').val()?.trim() || '';
            const luong = $tr.find('.existing_luong').val()?.trim() || '';
            $hidden.val(JSON.stringify({ vi_tri: vi_tri, luong: luong }));
        });

        // 2. Xử lý công tác temp
        $('input[name^="cong_tac_temp"]').remove();
        tempCongTac.forEach(function (row, i) {
            $('<input>', { type: 'hidden', name: `cong_tac_temp[${i}][chucvu_id]`, value: row.chucvu_id }).appendTo(e.target);
            $('<input>', { type: 'hidden', name: `cong_tac_temp[${i}][phongban_id]`, value: row.phongban_id }).appendTo(e.target);
            $('<input>', { type: 'hidden', name: `cong_tac_temp[${i}][mo_ta]`, value: row.mo_ta }).appendTo(e.target);
            $('<input>', { type: 'hidden', name: `cong_tac_temp[${i}][ngay_bat_dau]`, value: row.ngay_bat_dau }).appendTo(e.target);
            $('<input>', { type: 'hidden', name: `cong_tac_temp[${i}][ngay_ket_thuc]`, value: row.ngay_ket_thuc }).appendTo(e.target);
        });
        console.log("tempCongTac trước submit:", tempCongTac);

        // 3. Xử lý family members - QUAN TRỌNG
        $('#addFamilyFormContainer').hide();
        $('#tempFamilyMembersInput').remove();
        
        console.log('=== XỬ LÝ FAMILY MEMBERS ===');
        console.log('familyMembers trước khi stringify:', familyMembers);
        console.log('Số lượng members:', familyMembers.length);
        
        const familyJSON = JSON.stringify(familyMembers);
        console.log('familyMembers khi submit:', familyMembers);
        console.log('JSON đã stringify:', familyJSON);
        console.log('JSON length:', familyJSON.length);
        
        const $hiddenInput = $('<input>')
            .attr({
                type: 'hidden',
                name: 'temp_family_members',
                id: 'tempFamilyMembersInput',
                value: familyJSON
            });
        
        $(this).append($hiddenInput);
        
        console.log('✅ Đã append hidden input temp_family_members');
        console.log('Hidden input value:', $hiddenInput.val());
        console.log('Hidden input trong form:', $('input[name="temp_family_members"]').length);

        // 4. Log toàn bộ form data
        const formData = new FormData(this);
        console.log('=== FULL FORM DATA ===');
        for (let [key, value] of formData.entries()) {
            if (key === 'temp_family_members' || key.includes('cong_tac')) {
                console.log(key + ':', value);
            }
        }

        return true;
    });

    // ============================================================
    // 5. AVATAR PREVIEW
    // ============================================================
    
    window.previewAvatar = function (input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#avatarPreview').html(`<img src="${e.target.result}" alt="Avatar" class="rounded-circle shadow" width="120" height="120" style="object-fit: cover;">`);
            };
            reader.readAsDataURL(input.files[0]);
        }
    };

    // ============================================================
    // 6. KHỞI TẠO
    // ============================================================
    
    console.log('=== KHỞI TẠO TABLES ===');
    renderFamilyTable();
});

// ============================================================
// 7. HELPER FUNCTION - FILE NAME DISPLAY
// ============================================================

function showFileName(input, idx) {
    const fileName = input.files[0]?.name || '';
    $(`#file-name-${idx}`).text(fileName ? `Đã chọn: ${fileName}` : '');
}
</script>
@endsection