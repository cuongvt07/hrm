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
                            <li class="breadcrumb-item"><a href="{{ route('nhan-vien.show', $nhanVien->id) }}">{{ $nhanVien->ho }} {{ $nhanVien->ten }}</a></li>
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
                    <form action="{{ route('nhan-vien.update', $nhanVien->id) }}" method="POST" enctype="multipart/form-data" id="employeeForm">
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
                                                <img id="avatarPreview" src="{{ asset('storage/' . $nhanVien->anh_dai_dien) }}"
                                                     alt="Avatar" class="rounded-circle shadow" width="120" height="120" style="object-fit: cover;">
                                            @else
                                                <div id="avatarPreview" class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto shadow"
                                                     style="width: 120px; height: 120px; font-size: 2.5rem;">
                                                    {{ strtoupper(substr($nhanVien->ten, 0, 1)) }}
                                                </div>
                                            @endif
                                            <label for="anh_dai_dien" class="btn btn-sm btn-outline-primary position-absolute" style="bottom: 0; right: 0;">
                                                <i class="fas fa-camera"></i>
                                            </label>
                                            <input type="file" id="anh_dai_dien" name="anh_dai_dien" class="d-none" accept="image/*" onchange="previewAvatar(this)">
                                        </div>
                                    </div>

                                    <!-- Basic Info Fields -->
                                    <div class="mb-3">
                                        <label for="ma_nhanvien" class="form-label">Mã nhân viên <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="ma_nhanvien" name="ma_nhanvien" value="{{ $nhanVien->ma_nhanvien }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ $nhanVien->email }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="so_dien_thoai" class="form-label">Điện thoại</label>
                                        <input type="text" class="form-control" id="so_dien_thoai" name="so_dien_thoai" value="{{ $nhanVien->so_dien_thoai }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="ngay_sinh" class="form-label">Ngày sinh</label>
                                        <input type="date" class="form-control" id="ngay_sinh" name="ngay_sinh" value="{{ $nhanVien->ngay_sinh }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="gioi_tinh" class="form-label">Giới tính</label>
                                        <select class="form-select" id="gioi_tinh" name="gioi_tinh">
                                            <option value="">Chọn giới tính</option>
                                            <option value="nam" {{ $nhanVien->gioi_tinh == 'nam' ? 'selected' : '' }}>Nam</option>
                                            <option value="nu" {{ $nhanVien->gioi_tinh == 'nu' ? 'selected' : '' }}>Nữ</option>
                                            <option value="khac" {{ $nhanVien->gioi_tinh == 'khac' ? 'selected' : '' }}>Khác</option>
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
                                            <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab">Tài liệu</button>
                                        </li>
                                    </ul>

                                    <div class="tab-content" id="employeeTabContent">
                                        <!-- Basic Info Tab -->
                                        <div class="tab-pane fade show active" id="basic" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-6">
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
                                                <div class="col-md-6">
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

                                        <!-- Work Info Tab -->
                                        <div class="tab-pane fade" id="work" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="phong_ban_id" class="form-label">Phòng ban</label>
                                                        <select class="form-select" id="phong_ban_id" name="phong_ban_id">
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
                                                                <option value="{{ $chucVu->id }}" {{ $nhanVien->chuc_vu_id == $chucVu->id ? 'selected' : '' }}>{{ $chucVu->ten_chuc_vu }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="ngay_vao_lam" class="form-label">Ngày vào làm</label>
                                                        <input type="date" class="form-control" id="ngay_vao_lam" name="ngay_vao_lam" value="{{ $nhanVien->ngay_vao_lam }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="ngay_thu_viec" class="form-label">Ngày thử việc</label>
                                                        <input type="date" class="form-control" id="ngay_thu_viec" name="ngay_thu_viec" value="{{ $nhanVien->ngay_thu_viec }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="trang_thai" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                                        <select class="form-select" id="trang_thai" name="trang_thai" required>
                                                            <option value="">Chọn trạng thái</option>
                                                            <option value="thu_viec" {{ $nhanVien->trang_thai == 'thu_viec' ? 'selected' : '' }}>Thử việc</option>
                                                            <option value="nhan_vien_chinh_thuc" {{ $nhanVien->trang_thai == 'nhan_vien_chinh_thuc' ? 'selected' : '' }}>Nhân viên chính thức</option>
                                                            <option value="thai_san" {{ $nhanVien->trang_thai == 'thai_san' ? 'selected' : '' }}>Thai sản</option>
                                                            <option value="nghi_viec" {{ $nhanVien->trang_thai == 'nghi_viec' ? 'selected' : '' }}>Nghỉ việc</option>
                                                            <option value="khac" {{ $nhanVien->trang_thai == 'khac' ? 'selected' : '' }}>Khác</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="loai_hop_dong" class="form-label">Loại hợp đồng</label>
                                                        <input type="text" class="form-control" id="loai_hop_dong" name="loai_hop_dong" value="{{ $nhanVien->loai_hop_dong }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Contact Tab -->
                                        <div class="tab-pane fade" id="contact" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h5 class="mb-3">Số điện thoại</h5>
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
                                                <div class="col-md-6">
                                                    <h5 class="mb-3">Email</h5>
                                                    <div class="mb-3">
                                                        <label for="email_co_quan" class="form-label">Email cơ quan</label>
                                                        <input type="email" class="form-control" id="email_co_quan" name="email_co_quan" value="{{ $nhanVien->thongTinLienHe->email_co_quan ?? '' }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="email_ca_nhan" class="form-label">Email cá nhân</label>
                                                        <input type="email" class="form-control" id="email_ca_nhan" name="email_ca_nhan" value="{{ $nhanVien->thongTinLienHe->email_ca_nhan ?? '' }}">
                                                    </div>
                                                    <h5 class="mb-3">Địa chỉ</h5>
                                                    <div class="mb-3">
                                                        <label for="dia_chi_thuong_tru" class="form-label">Địa chỉ thường trú</label>
                                                        <textarea class="form-control" id="dia_chi_thuong_tru" name="dia_chi_thuong_tru" rows="2">{{ $nhanVien->thongTinLienHe->dia_chi_thuong_tru ?? '' }}</textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="dia_chi_hien_tai" class="form-label">Địa chỉ hiện tại</label>
                                                        <textarea class="form-control" id="dia_chi_hien_tai" name="dia_chi_hien_tai" rows="2">{{ $nhanVien->thongTinLienHe->dia_chi_hien_tai ?? '' }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <h5 class="mb-3">Liên hệ khẩn cấp</h5>
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

                                        <!-- Family Tab -->
                                        <div class="tab-pane fade" id="family" role="tabpanel">
                                            <div class="mb-4">
                                                <h5>Thành viên gia đình</h5>
                                                <button type="button" class="btn btn-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#addFamilyMemberModal">
                                                    <i class="fas fa-plus me-1"></i>Thêm thành viên
                                                </button>
                                            </div>

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
                                                                <th>Người phụ thuộc</th>
                                                                <th>Thao tác</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($nhanVien->thongTinGiaDinh as $member)
                                                                <tr>
                                                                    <td>{{ $member->quan_he }}</td>
                                                                    <td>{{ $member->ho_ten }}</td>
                                                                    <td>{{ $member->ngay_sinh ? $member->ngay_sinh->format('d/m/Y') : '' }}</td>
                                                                    <td>{{ $member->nghe_nghiep }}</td>
                                                                    <td>{{ $member->dien_thoai }}</td>
                                                                    <td>
                                                                        @if($member->la_nguoi_phu_thuoc)
                                                                            <span class="badge bg-success">Có</span>
                                                                        @else
                                                                            <span class="badge bg-secondary">Không</span>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-sm btn-outline-primary me-1" onclick="editFamilyMember({{ $member->id }})">
                                                                            <i class="fas fa-edit"></i>
                                                                        </button>
                                                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteFamilyMember({{ $member->id }})">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <p class="text-muted">Chưa có thông tin thành viên gia đình</p>
                                            @endif
                                        </div>

                                        <!-- Documents Tab -->
                                        <div class="tab-pane fade" id="documents" role="tabpanel">
                                            <div class="mb-4">
                                                <h5>Tài liệu đính kèm</h5>
                                                <button type="button" class="btn btn-primary btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                                                    <i class="fas fa-upload me-1"></i>Upload tài liệu
                                                </button>
                                            </div>

                                            @if($nhanVien->tepTin && $nhanVien->tepTin->count() > 0)
                                                <div class="table-responsive">
                                                    <table class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Loại tài liệu</th>
                                                                <th>Tên file</th>
                                                                <th>Kích thước</th>
                                                                <th>Ngày upload</th>
                                                                <th>Thao tác</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($nhanVien->tepTin as $file)
                                                                <tr>
                                                                    <td>
                                                                        <span class="badge bg-info">{{ ucfirst($file->loai_tep) }}</span>
                                                                    </td>
                                                                    <td>{{ $file->ten_tep }}</td>
                                                                    <td>{{ $file->kieu_mime }}</td>
                                                                    <td>{{ $file->created_at->format('d/m/Y H:i') }}</td>
                                                                    <td>
                                                                        @if($file->duong_dan_tep)
                                                                            <a href="{{ asset('storage/' . $file->duong_dan_tep) }}" target="_blank" class="btn btn-sm btn-outline-primary me-1">
                                                                                <i class="fas fa-download"></i>
                                                                            </a>
                                                                        @endif
                                                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteDocument({{ $file->id }})">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @else
                                                <p class="text-muted">Chưa có tài liệu nào được upload</p>
                                            @endif
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

<!-- Add Family Member Modal -->
<div class="modal fade" id="addFamilyMemberModal" tabindex="-1" aria-labelledby="addFamilyMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFamilyMemberModalLabel">Thêm thành viên gia đình</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="familyMemberForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="family_quan_he" class="form-label">Quan hệ <span class="text-danger">*</span></label>
                                <select class="form-select" id="family_quan_he" name="quan_he" required>
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
                                <label for="family_ho_ten" class="form-label">Họ tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="family_ho_ten" name="ho_ten" required>
                            </div>
                            <div class="mb-3">
                                <label for="family_ngay_sinh" class="form-label">Ngày sinh</label>
                                <input type="date" class="form-control" id="family_ngay_sinh" name="ngay_sinh">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="family_nghe_nghiep" class="form-label">Nghề nghiệp</label>
                                <input type="text" class="form-control" id="family_nghe_nghiep" name="nghe_nghiep">
                            </div>
                            <div class="mb-3">
                                <label for="family_dien_thoai" class="form-label">Điện thoại</label>
                                <input type="text" class="form-control" id="family_dien_thoai" name="dien_thoai">
                            </div>
                            <div class="mb-3">
                                <label for="family_dia_chi" class="form-label">Địa chỉ liên hệ</label>
                                <textarea class="form-control" id="family_dia_chi" name="dia_chi_lien_he" rows="2"></textarea>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="family_la_nguoi_phu_thuoc" name="la_nguoi_phu_thuoc" value="1">
                                    <label class="form-check-label" for="family_la_nguoi_phu_thuoc">
                                        Là người phụ thuộc
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="family_ghi_chu" class="form-label">Ghi chú</label>
                        <textarea class="form-control" id="family_ghi_chu" name="ghi_chu" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </form>
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

// Handle family member form submission
document.getElementById('familyMemberForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('{{ route("nhan-vien.addFamilyMember", $nhanVien->id) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modal and reload page
            const modal = bootstrap.Modal.getInstance(document.getElementById('addFamilyMemberModal'));
            modal.hide();
            location.reload();
        } else {
            alert('Có lỗi xảy ra: ' + (data.message || 'Không thể lưu thành viên gia đình'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi lưu thành viên gia đình');
    });
});

// Reset form when modal is shown
document.getElementById('addFamilyMemberModal').addEventListener('show.bs.modal', function() {
    document.getElementById('familyMemberForm').reset();
});

function editFamilyMember(memberId) {
    // TODO: Implement edit functionality
    alert('Chức năng chỉnh sửa thành viên gia đình đang được phát triển');
}

function deleteFamilyMember(memberId) {
    if (confirm('Bạn có chắc chắn muốn xóa thành viên gia đình này?')) {
        fetch('{{ route("nhan-vien.deleteFamilyMember", [$nhanVien->id, ":memberId"]) }}'.replace(':memberId', memberId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Có lỗi xảy ra: ' + (data.message || 'Không thể xóa thành viên gia đình'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi xóa thành viên gia đình');
        });
    }
}
</script>
@endsection