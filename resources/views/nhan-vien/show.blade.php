@extends('layouts.app')

@section('title', 'Chi tiết nhân viên - ' . $nhanVien->ho . ' ' . $nhanVien->ten)

@section('content')
@php
    $statusConfig = [
        'nhan_vien_chinh_thuc' => ['class' => 'success', 'text' => 'Đang làm việc'],
        'thu_viec' => ['class' => 'warning', 'text' => 'Thử việc'],
        'thai_san' => ['class' => 'info', 'text' => 'Thai sản'],
        'nghi_viec' => ['class' => 'danger', 'text' => 'Đã nghỉ việc'],
        'khac' => ['class' => 'secondary', 'text' => 'Khác']
    ];
@endphp
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Chi tiết nhân viên</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('nhan-vien.index') }}">Nhân viên</a></li>
                            <li class="breadcrumb-item active">{{ $nhanVien->ho }} {{ $nhanVien->ten }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('nhan-vien.edit', $nhanVien->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i>Chỉnh sửa
                    </a>
                    <a href="{{ route('nhan-vien.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Quay lại danh sách
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Employee Detail Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row g-0">
                        <!-- Left Column -->
                        <div class="col-md-3">
                            <div class="p-4 border-end bg-light">
                                <!-- Avatar -->
                                <div class="text-center mb-4">
                                    @if($nhanVien->anh_dai_dien)
                                        <img src="{{ asset('storage/' . $nhanVien->anh_dai_dien) }}"
                                             alt="Avatar" class="rounded-circle shadow border" width="120" height="120" style="object-fit: cover;">
                                    @else
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto shadow border"
                                             style="width: 120px; height: 120px; font-size: 2.5rem;">
                                            {{ strtoupper(substr($nhanVien->ten, 0, 1)) }}
                                        </div>
                                    @endif
                                    <h4 class="mt-3 mb-1">{{ $nhanVien->ho }} {{ $nhanVien->ten }}</h4>
                                    <p class="mb-3">Mã NV: {{ $nhanVien->ma_nhanvien }}</p>
                                    <div class="mb-3">
                                        @php
                                            $config = $statusConfig[$nhanVien->trang_thai] ?? $statusConfig['khac'];
                                        @endphp
                                        <span class="badge bg-{{ $config['class'] }} fs-6 px-3 py-2">{{ $config['text'] }}</span>
                                    </div>
                                </div>

                                <!-- Quick Info -->
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0"><i class="fas fa-info-circle me-1"></i>Thông tin nhanh</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <small class="text-dark"><i class="fas fa-envelope me-1"></i>Email:</small>
                                            </div>
                                            <div class="fw-bold small">{{ $nhanVien->email ?? '-' }}</div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <small class="text-dark"><i class="fas fa-phone me-1"></i>Điện thoại:</small>
                                            </div>
                                            <div class="fw-bold small">{{ $nhanVien->so_dien_thoai ?? '-' }}</div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <small class="text-dark"><i class="fas fa-birthday-cake me-1"></i>Ngày sinh:</small>
                                            </div>
                                            <div class="fw-bold small">{{ $nhanVien->ngay_sinh ? \Carbon\Carbon::parse($nhanVien->ngay_sinh)->format('d/m/Y') : '-' }}</div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <small class="text-dark"><i class="fas fa-venus-mars me-1"></i>Giới tính:</small>
                                            </div>
                                            <div class="fw-bold small">{{ $nhanVien->gioi_tinh == 'nam' ? 'Nam' : 'Nữ' }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Work Info Summary -->
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0"><i class="fas fa-briefcase me-1"></i>Công việc</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <small class="text-dark"><i class="fas fa-building me-1"></i>Phòng ban:</small>
                                            </div>
                                            <div class="fw-bold small">{{ $nhanVien->phongBan ? $nhanVien->phongBan->ten_phong_ban : '-' }}</div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <small class="text-dark"><i class="fas fa-user-tie me-1"></i>Chức vụ:</small>
                                            </div>
                                            <div class="fw-bold small">{{ $nhanVien->chucVu ? $nhanVien->chucVu->ten_chuc_vu : '-' }}</div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <small class="text-dark"><i class="fas fa-calendar-plus me-1"></i>Ngày vào làm:</small>
                                            </div>
                                            <div class="fw-bold small">{{ $nhanVien->ngay_vao_lam ? \Carbon\Carbon::parse($nhanVien->ngay_vao_lam)->format('d/m/Y') : '-' }}</div>
                                        </div>
                                        @if(in_array($nhanVien->trang_thai, ['nhan_vien_chinh_thuc', 'thai_san']))
                                            <div class="mb-0">
                                                <div class="d-flex justify-content-between align-items-center py-1">
                                                    <small class="text-dark"><i class="fas fa-calendar-alt me-1"></i>Thâm niên:</small>
                                                </div>
                                                <div class="fw-bold small">
                                                    @if($nhanVien->ngay_vao_lam)
                                                        @php
                                                            $startDate = \Carbon\Carbon::parse($nhanVien->ngay_vao_lam);
                                                            $now = \Carbon\Carbon::now();
                                                            $totalMonths = $startDate->diffInMonths($now);
                                                            $years = floor($totalMonths / 12);
                                                            $months = $totalMonths % 12;
                                                            $seniority = match(true) {
                                                                $years == 0 && $months > 0 => $months . ' tháng',
                                                                $years > 0 && $months == 0 => $years . ' năm',
                                                                $years > 0 && $months > 0 => $years . ' năm ' . $months . ' tháng',
                                                                default => '< 1 tháng'
                                                            };
                                                        @endphp
                                                        {{ $seniority }}
                                                    @else
                                                        -
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <div class="mb-0">
                                                <div class="d-flex justify-content-between align-items-center py-1">
                                                    <small class="text-dark"><i class="fas fa-clock me-1"></i>Ngày thử việc:</small>
                                                </div>
                                                <div class="fw-bold small">{{ $nhanVien->ngay_thu_viec ? \Carbon\Carbon::parse($nhanVien->ngay_thu_viec)->format('d/m/Y') : '-' }}</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Right Column -->
                        <div class="col-md-9">
                            <div class="p-4">
                                <ul class="nav nav-tabs mb-4" id="employeeTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" id="basic-tab" data-bs-toggle="tab" href="#basic" role="tab" aria-controls="basic" aria-selected="true">Thông tin cơ bản</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="work-tab" data-bs-toggle="tab" href="#work" role="tab" aria-controls="work" aria-selected="false">Thông tin công việc</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="contact-tab" data-bs-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Thông tin liên hệ</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="family-tab" data-bs-toggle="tab" href="#family" role="tab" aria-controls="family" aria-selected="false">Gia đình</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="giayto-tab" data-bs-toggle="tab" href="#giayto" role="tab" aria-controls="giayto" aria-selected="false">Giấy tờ tùy thân</a>
                                    </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" id="insurance-tab" data-bs-toggle="tab" href="#insurance" role="tab" aria-controls="insurance" aria-selected="false">Thông tin bảo hiểm</a>
                                        </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="documents-tab" data-bs-toggle="tab" href="#documents" role="tab" aria-controls="documents" aria-selected="false">Tài liệu</a>
                                    </li>
                                </ul>

                                <!-- Tab Content -->
                                <div class="tab-content" id="employeeTabContent">
                                    <!-- Basic Info Tab -->
                                    <div class="tab-pane fade show active" id="basic" role="tabpanel" aria-labelledby="basic-tab">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card border-0 bg-light mb-4">
                                                    <div class="card-header bg-primary text-white">
                                                        <h5 class="mb-0"><i class="fas fa-user me-2"></i>Thông tin cơ bản</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row g-3">
                                                            <div class="col-12">
                                                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                                    <strong class="text-dark">Họ:</strong>
                                                                    <span class="fw-bold">{{ $nhanVien->ho }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                                    <strong class="text-dark">Tên:</strong>
                                                                    <span class="fw-bold">{{ $nhanVien->ten }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                                    <strong class="text-dark">Tình trạng hôn nhân:</strong>
                                                                    <span class="fw-bold">
                                                                        @switch($nhanVien->tinh_trang_hon_nhan)
                                                                            @case('doc_than')
                                                                                <span class="badge bg-info">Độc thân</span>
                                                                                @break
                                                                            @case('da_ket_hon')
                                                                                <span class="badge bg-success">Đã kết hôn</span>
                                                                                @break
                                                                            @case('ly_hon')
                                                                                <span class="badge bg-warning">Ly hôn</span>
                                                                                @break
                                                                            @default
                                                                                <span class="text-dark">-</span>
                                                                        @endswitch
                                                                    </span>
                                                                </div>
                                                            </div>
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
                                                        <div class="row g-3">
                                                            <div class="col-12">
                                                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                                    <strong class="text-dark">Quốc tịch:</strong>
                                                                    <span class="fw-bold">{{ $nhanVien->quoc_tich ?? 'Việt Nam' }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                                    <strong class="text-dark">Dân tộc:</strong>
                                                                    <span class="fw-bold">{{ $nhanVien->dan_toc ?? 'Kinh' }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                                    <strong class="text-dark">Tôn giáo:</strong>
                                                                    <span class="fw-bold">{{ $nhanVien->ton_giao ?? '-' }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="d-flex justify-content-between align-items-center py-2">
                                                                    <strong class="text-dark">Địa chỉ:</strong>
                                                                    <span class="fw-bold">{{ $nhanVien->dia_chi ?? '-' }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Work Info Tab -->
                                    <div class="tab-pane fade" id="work" role="tabpanel" aria-labelledby="work-tab">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card border-0 bg-light mb-4">
                                                    <div class="card-header bg-success text-white">
                                                        <h5 class="mb-0"><i class="fas fa-briefcase me-2"></i>Thông tin công việc</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row g-3">
                                                            <div class="col-12">
                                                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                                    <strong class="text-dark"><i class="fas fa-building me-1"></i>Phòng ban:</strong>
                                                                    <span class="fw-bold">{{ $nhanVien->phongBan ? $nhanVien->phongBan->ten_phong_ban : '-' }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                                    <strong class="text-dark"><i class="fas fa-user-tie me-1"></i>Chức vụ:</strong>
                                                                    <span class="fw-bold">{{ $nhanVien->chucVu ? $nhanVien->chucVu->ten_chuc_vu : '-' }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                                    <strong class="text-dark"><i class="fas fa-user me-1"></i>Quản lý trực tiếp:</strong>
                                                                    <span class="fw-bold">
                                                                        @if($nhanVien->quanLyTrucTiep)
                                                                            {{ $nhanVien->quanLyTrucTiep->ho }} {{ $nhanVien->quanLyTrucTiep->ten }}
                                                                        @else
                                                                            <span class="text-muted">Không có</span>
                                                                        @endif
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                                    <strong class="text-dark"><i class="fas fa-users me-1"></i>Nhân viên cấp dưới:</strong>
                                                                    <span class="fw-bold">
                                                                        @if($nhanVien->capDuoi && $nhanVien->capDuoi->count() > 0)
                                                                            {!! $nhanVien->capDuoi->map(function($nv){
                                                                                return $nv->ho . ' ' . $nv->ten . ' - ' . $nv->ma_nhanvien;
                                                                            })->implode('<br>') !!}
                                                                        @else
                                                                            <span class="text-muted">Không có</span>
                                                                        @endif
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                                    <strong class="text-dark"><i class="fas fa-calendar-plus me-1"></i>Ngày vào làm:</strong>
                                                                    <span class="fw-bold">{{ $nhanVien->ngay_vao_lam ? \Carbon\Carbon::parse($nhanVien->ngay_vao_lam)->format('d/m/Y') : '-' }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="d-flex justify-content-between align-items-center py-2">
                                                                    <strong class="text-dark"><i class="fas fa-clock me-1"></i>Ngày thử việc:</strong>
                                                                    <span class="fw-bold">{{ $nhanVien->ngay_thu_viec ? \Carbon\Carbon::parse($nhanVien->ngay_thu_viec)->format('d/m/Y') : '-' }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card border-0 bg-light mb-4">
                                                    <div class="card-header bg-warning text-dark">
                                                        <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Trạng thái & Hợp đồng</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row g-3">
                                                            <div class="col-12">
                                                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                                    <strong class="text-dark"><i class="fas fa-info-circle me-1"></i>Trạng thái:</strong>
                                                                    <span class="fw-bold">
                                                                        @php
                                                                            $config = $statusConfig[$nhanVien->trang_thai] ?? $statusConfig['khac'];
                                                                        @endphp
                                                                        <span class="badge bg-{{ $config['class'] }} fs-6">{{ $config['text'] }}</span>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            @if($nhanVien->hopDongLaoDong && $nhanVien->hopDongLaoDong->count() > 0)
                                                                <div class="col-12">
                                                                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                                        <strong class="text-dark"><i class="fas fa-file-contract me-1"></i>Loại hợp đồng:</strong>
                                                                        <span class="fw-bold">
                                                                            {{ $nhanVien->hopDongLaoDong->last()->loai_hop_dong ?? '-' }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <div class="col-12">
                                                                <div class="d-flex justify-content-between align-items-center py-2">
                                                                    <strong class="text-dark"><i class="fas fa-calendar-alt me-1"></i>Thâm niên:</strong>
                                                                    <span class="fw-bold">
                                                                        @if($nhanVien->ngay_vao_lam)
                                                                            @php
                                                                                $startDate = \Carbon\Carbon::parse($nhanVien->ngay_vao_lam);
                                                                                $now = \Carbon\Carbon::now();
                                                                                $totalMonths = $startDate->diffInMonths($now);
                                                                                $years = floor($totalMonths / 12);
                                                                                $months = $totalMonths % 12;

                                                                                $seniority = match(true) {
                                                                                    $years == 0 && $months > 0 => $months . ' tháng',
                                                                                    $years > 0 && $months == 0 => $years . ' năm',
                                                                                    $years > 0 && $months > 0 => $years . ' năm ' . $months . ' tháng',
                                                                                    default => '< 1 tháng'
                                                                                };
                                                                            @endphp
                                                                            {{ $seniority }}
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>

                                        <!-- Contract Information -->
                                        @if($nhanVien->hopDongLaoDong && $nhanVien->hopDongLaoDong->count() > 0)
                                            <div class="row mt-4">
                                                <div class="col-12">
                                                    <div class="card border-0 shadow-sm">
                                                        <div class="card-header bg-dark text-white">
                                                            <h5 class="mb-0"><i class="fas fa-file-signature me-2"></i>Hợp đồng lao động</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            @foreach($nhanVien->hopDongLaoDong as $hopDong)
                                                                <div class="border rounded p-3 mb-3 bg-white">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="mb-2">
                                                                                <strong class="text-primary"><i class="fas fa-hashtag me-1"></i>Số hợp đồng:</strong>
                                                                                <span class="ms-2">{{ $hopDong->so_hop_dong }}</span>
                                                                            </div>
                                                                            <div class="mb-2">
                                                                                <strong class="text-primary"><i class="fas fa-calendar-check me-1"></i>Ngày ký:</strong>
                                                                                <span class="ms-2">{{ $hopDong->ngay_ky ? \Carbon\Carbon::parse($hopDong->ngay_ky)->format('d/m/Y') : '-' }}</span>
                                                                            </div>
                                                                            <div class="mb-2">
                                                                                <strong class="text-primary"><i class="fas fa-play-circle me-1"></i>Ngày bắt đầu:</strong>
                                                                                <span class="ms-2">{{ $hopDong->ngay_bat_dau ? \Carbon\Carbon::parse($hopDong->ngay_bat_dau)->format('d/m/Y') : '-' }}</span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="mb-2">
                                                                                <strong class="text-primary"><i class="fas fa-stop-circle me-1"></i>Ngày kết thúc:</strong>
                                                                                <span class="ms-2">{{ $hopDong->ngay_ket_thuc ? \Carbon\Carbon::parse($hopDong->ngay_ket_thuc)->format('d/m/Y') : '-' }}</span>
                                                                            </div>
                                                                            <div class="mb-2">
                                                                                <strong class="text-primary"><i class="fas fa-file-contract me-1"></i>Loại hợp đồng:</strong>
                                                                                <span class="ms-2">{{ $hopDong->loai_hop_dong }}</span>
                                                                            </div>
                                                                            <div class="mb-2">
                                                                                <strong class="text-primary"><i class="fas fa-info-circle me-1"></i>Trạng thái:</strong>
                                                                                <span class="ms-2">
                                                                                    @if($hopDong->trang_thai == 'active')
                                                                                        <span class="badge bg-success">Đang hoạt động</span>
                                                                                    @elseif($hopDong->trang_thai == 'expired')
                                                                                        <span class="badge bg-warning">Đã hết hạn</span>
                                                                                    @elseif($hopDong->trang_thai == 'terminated')
                                                                                        <span class="badge bg-danger">Đã chấm dứt</span>
                                                                                    @else
                                                                                        <span class="badge bg-secondary">{{ $hopDong->trang_thai }}</span>
                                                                                    @endif
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Thông tin lương -->
                                        @if($nhanVien->thongTinLuong)
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
                                                                    <label class="form-label">Lương cơ bản</label>
                                                                    <div class="fw-bold">{{ number_format($nhanVien->thongTinLuong->luong_co_ban, 0, ',', '.') }} VNĐ</div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Số tài khoản ngân hàng</label>
                                                                    <div class="fw-bold">{{ $nhanVien->thongTinLuong->so_tai_khoan ?? '-' }}</div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Tên ngân hàng</label>
                                                                    <div class="fw-bold">{{ $nhanVien->thongTinLuong->ten_ngan_hang ?? '-' }}</div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Chi nhánh ngân hàng</label>
                                                                    <div class="fw-bold">{{ $nhanVien->thongTinLuong->chi_nhanh_ngan_hang ?? '-' }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-money-bill-wave fa-2x text-muted mb-2"></i>
                                            <p class="text-muted">Chưa có thông tin lương</p>
                                        </div>
                                        @endif
                                    </div>
                                    <!-- Contact Tab -->
                                    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                        @if($nhanVien->thongTinLienHe)
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="card border-0 bg-light mb-4">
                                                        <div class="card-header bg-secondary text-white">
                                                            <h5 class="mb-0"><i class="fas fa-phone me-2"></i>Số điện thoại</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row g-3">
                                                                <div class="col-12">
                                                                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                                        <strong class="text-dark"><i class="fas fa-mobile-alt me-1"></i>Điện thoại di động:</strong>
                                                                        <span class="fw-bold">{{ $nhanVien->thongTinLienHe->dien_thoai_di_dong ?? '-' }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                                        <strong class="text-dark"><i class="fas fa-building me-1"></i>Điện thoại cơ quan:</strong>
                                                                        <span class="fw-bold">{{ $nhanVien->thongTinLienHe->dien_thoai_co_quan ?? '-' }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                                        <strong class="text-dark"><i class="fas fa-home me-1"></i>Điện thoại nhà riêng:</strong>
                                                                        <span class="fw-bold">{{ $nhanVien->thongTinLienHe->dien_thoai_nha_rieng ?? '-' }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="d-flex justify-content-between align-items-center py-2">
                                                                        <strong class="text-dark"><i class="fas fa-phone-square me-1"></i>Điện thoại khác:</strong>
                                                                        <span class="fw-bold">{{ $nhanVien->thongTinLienHe->dien_thoai_khac ?? '-' }}</span>
                                                                    </div>
                                                                </div>
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
                                                            <div class="row g-3">
                                                                <div class="col-12">
                                                                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                                        <strong class="text-dark"><i class="fas fa-envelope-open me-1"></i>Email cơ quan:</strong>
                                                                        <span class="fw-bold">{{ $nhanVien->thongTinLienHe->email_co_quan ?? '-' }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="d-flex justify-content-between align-items-center py-2">
                                                                        <strong class="text-dark"><i class="fas fa-envelope me-1"></i>Email cá nhân:</strong>
                                                                        <span class="fw-bold">{{ $nhanVien->thongTinLienHe->email_ca_nhan ?? '-' }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="card border-0 bg-light mb-4">
                                                        <div class="card-header bg-warning text-dark">
                                                            <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Địa chỉ</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row g-3">
                                                                <div class="col-12">
                                                                    <div class="d-flex justify-content-between align-items-start py-2 border-bottom">
                                                                        <strong class="text-dark"><i class="fas fa-home me-1"></i>Địa chỉ thường trú:</strong>
                                                                        <span class="fw-bold text-end">{{ $nhanVien->thongTinLienHe->dia_chi_thuong_tru ?? '-' }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="d-flex justify-content-between align-items-start py-2">
                                                                        <strong class="text-dark"><i class="fas fa-map-pin me-1"></i>Địa chỉ hiện tại:</strong>
                                                                        <span class="fw-bold text-end">{{ $nhanVien->thongTinLienHe->dia_chi_hien_tai ?? '-' }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card border-0 shadow-sm">
                                                        <div class="card-header bg-danger text-white">
                                                            <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Liên hệ khẩn cấp</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="text-center p-3 bg-light rounded">
                                                                        <i class="fas fa-user fa-2x text-primary mb-2"></i>
                                                                        <h6 class="text-muted">Tên</h6>
                                                                        <strong class="d-block">{{ $nhanVien->thongTinLienHe->lien_he_khan_cap_ten ?? '-' }}</strong>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="text-center p-3 bg-light rounded">
                                                                        <i class="fas fa-users fa-2x text-success mb-2"></i>
                                                                        <h6 class="text-muted">Quan hệ</h6>
                                                                        <strong class="d-block">{{ $nhanVien->thongTinLienHe->lien_he_khan_cap_quan_he ?? '-' }}</strong>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="text-center p-3 bg-light rounded">
                                                                        <i class="fas fa-phone fa-2x text-warning mb-2"></i>
                                                                        <h6 class="text-muted">Điện thoại</h6>
                                                                        <strong class="d-block">{{ $nhanVien->thongTinLienHe->lien_he_khan_cap_dien_thoai ?? '-' }}</strong>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-center py-5">
                                                <i class="fas fa-address-book fa-3x text-muted mb-3"></i>
                                                <p class="text-muted fs-5">Chưa có thông tin liên hệ bổ sung</p>
                                            </div>
                                        @endif
                                    </div>
                                    <!-- Family Tab -->
                                    <div class="tab-pane fade" id="family" role="tabpanel" aria-labelledby="family-tab">
                                        @if($nhanVien->thongTinGiaDinh && $nhanVien->thongTinGiaDinh->count() > 0)
                                            <div class="card border-0 shadow-sm">
                                                <div class="card-header bg-success text-white">
                                                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Thành viên gia đình</h5>
                                                </div>
                                                <div class="card-body p-0">
                                                    @foreach($nhanVien->thongTinGiaDinh as $member)
                                                        <div class="border-bottom p-3 {{ $loop->last ? '' : 'border-bottom' }}">
                                                            <div class="row align-items-center">
                                                                <div class="col-md-3">
                                                                    <div class="d-flex align-items-center">
                                                                        <i class="fas fa-user-circle fa-2x text-primary me-3"></i>
                                                                        <div>
                                                                            <h6 class="mb-0 fw-bold">{{ $member->quan_he }}</h6>
                                                                            <small class="text-muted">{{ $member->ho_ten }}</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="text-center">
                                                                        <i class="fas fa-birthday-cake text-info mb-1"></i>
                                                                        <div>
                                                                            <small class="text-muted d-block">Ngày sinh</small>
                                                                            <strong>{{ $member->ngay_sinh ? $member->ngay_sinh->format('d/m/Y') : '-' }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="text-center">
                                                                        <i class="fas fa-briefcase text-warning mb-1"></i>
                                                                        <div>
                                                                            <small class="text-muted d-block">Nghề nghiệp</small>
                                                                            <strong>{{ $member->nghe_nghiep ?? '-' }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="text-center">
                                                                        <i class="fas fa-phone text-success mb-1"></i>
                                                                        <div>
                                                                            <small class="text-muted d-block">Điện thoại</small>
                                                                            <strong>{{ $member->dien_thoai ?? '-' }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row mt-2">
                                                                <div class="col-12 text-end">
                                                                    @if($member->la_nguoi_phu_thuoc)
                                                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>Người phụ thuộc</span>
                                                                    @else
                                                                        <span class="badge bg-secondary"><i class="fas fa-times me-1"></i>Không phải người phụ thuộc</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-center py-5">
                                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                                <p class="text-muted fs-5">Chưa có thông tin thành viên gia đình</p>
                                            </div>
                                        @endif
                                    </div>
                                    <!-- Documents Tab -->
                                    <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                                        @if($nhanVien->tepTin && $nhanVien->tepTin->count() > 0)
                                            <div class="card border-0 shadow-sm">
                                                <div class="card-header bg-primary text-white">
                                                    <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Tài liệu đính kèm</h5>
                                                </div>
                                                <div class="card-body p-0">
                                                    @foreach($nhanVien->tepTin as $file)
                                                        <div class="d-flex align-items-center p-3 border-bottom {{ $loop->last ? '' : 'border-bottom' }}">
                                                            <div class="flex-shrink-0 me-3">
                                                                @if(str_contains($file->kieu_mime, 'pdf'))
                                                                    <i class="fas fa-file-pdf fa-2x text-danger"></i>
                                                                @elseif(str_contains($file->kieu_mime, 'image'))
                                                                    <i class="fas fa-file-image fa-2x text-success"></i>
                                                                @elseif(str_contains($file->kieu_mime, 'word') || str_contains($file->kieu_mime, 'document'))
                                                                    <i class="fas fa-file-word fa-2x text-primary"></i>
                                                                @elseif(str_contains($file->kieu_mime, 'excel') || str_contains($file->kieu_mime, 'spreadsheet'))
                                                                    <i class="fas fa-file-excel fa-2x text-success"></i>
                                                                @else
                                                                    <i class="fas fa-file fa-2x text-secondary"></i>
                                                                @endif
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-1">{{ $file->ten_tep }}</h6>
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <small class="text-muted">
                                                                            <i class="fas fa-tag me-1"></i>
                                                                            <span class="badge bg-info">{{ ucfirst($file->loai_tep) }}</span>
                                                                        </small>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <small class="text-muted">
                                                                            <i class="fas fa-weight me-1"></i>{{ $file->kieu_mime }}
                                                                        </small>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <small class="text-muted">
                                                                            <i class="fas fa-calendar me-1"></i>{{ $file->created_at->format('d/m/Y H:i') }}
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="flex-shrink-0">
                                                                @if($file->duong_dan_tep)
                                                                    <a href="{{ asset('storage/' . $file->duong_dan_tep) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                                        <i class="fas fa-download me-1"></i>Tải xuống
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-center py-5">
                                                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                                <p class="text-muted fs-5">Chưa có tài liệu nào được upload</p>
                                            </div>
                                        @endif
                                    </div>
                                    <!-- Insurance Tab -->
                                    <div class="tab-pane fade" id="insurance" role="tabpanel" aria-labelledby="insurance-tab">
                                        <div class="card border-0 bg-light mb-4">
                                            <div class="card-header bg-info text-white">
                                                <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Thông tin bảo hiểm</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label class="form-label">Ngày tham gia BH</label>
                                                            <div>{{ optional($nhanVien->baoHiem)->ngay_tham_gia_bh ? \Carbon\Carbon::parse($nhanVien->baoHiem->ngay_tham_gia_bh)->format('d/m/Y') : '-' }}</div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Tỷ lệ đóng BH (%)</label>
                                                            <div>{{ optional($nhanVien->baoHiem)->ty_le_dong_bh ?? '-' }}</div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Tỷ lệ đóng BHXH (%)</label>
                                                            <div>{{ optional($nhanVien->baoHiem)->ty_le_bhxh ?? '-' }}</div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Tỷ lệ đóng BHYT (%)</label>
                                                            <div>{{ optional($nhanVien->baoHiem)->ty_le_bhyt ?? '-' }}</div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Tỷ lệ đóng BHTN (%)</label>
                                                            <div>{{ optional($nhanVien->baoHiem)->ty_le_bhtn ?? '-' }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label class="form-label">Số sổ BHXH</label>
                                                            <div>{{ optional($nhanVien->baoHiem)->so_so_bhxh ?? '-' }}</div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Mã số BHXH</label>
                                                            <div>{{ optional($nhanVien->baoHiem)->ma_so_bhxh ?? '-' }}</div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Tham gia bảo hiểm</label>
                                                            <div>{{ optional($nhanVien->baoHiem)->tham_gia_bao_hiem ? 'Có' : 'Không' }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label class="form-label">Tỉnh cấp</label>
                                                            <div>{{ optional($nhanVien->baoHiem)->tinh_cap ?? '-' }}</div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Mã Tỉnh cấp</label>
                                                            <div>{{ optional($nhanVien->baoHiem)->ma_tinh_cap ?? '-' }}</div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Số thẻ BHYT</label>
                                                            <div>{{ optional($nhanVien->baoHiem)->so_the_bhyt ?? '-' }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Giấy tờ tùy thân Tab -->
                                    <div class="tab-pane fade" id="giayto" role="tabpanel" aria-labelledby="giayto-tab">
                                        @if($nhanVien->thongTinGiayTo && $nhanVien->thongTinGiayTo->count() > 0)
                                            <div class="card border-0 shadow-sm">
                                                <div class="card-header bg-success text-white">
                                                    <h5 class="mb-0"><i class="fas fa-id-card me-2"></i>Giấy tờ tùy thân</h5>
                                                </div>
                                                <div class="card-body p-0">
                                                    <table class="table table-bordered mb-0">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Loại giấy tờ</th>
                                                                <th>Số giấy tờ</th>
                                                                <th>Ngày cấp</th>
                                                                <th>Nơi cấp</th>
                                                                <th>Ngày hết hạn</th>
                                                                <th>Ghi chú</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($nhanVien->thongTinGiayTo as $giayTo)
                                                                <tr>
                                                                    <td>{{ $giayTo->loai_giay_to }}</td>
                                                                    <td>{{ $giayTo->so_giay_to }}</td>
                                                                    <td>{{ $giayTo->ngay_cap ? \Carbon\Carbon::parse($giayTo->ngay_cap)->format('d/m/Y') : '-' }}</td>
                                                                    <td>{{ $giayTo->noi_cap ?? '-' }}</td>
                                                                    <td>{{ $giayTo->ngay_het_han ? \Carbon\Carbon::parse($giayTo->ngay_het_han)->format('d/m/Y') : '-' }}</td>
                                                                    <td>{{ $giayTo->ghi_chu ?? '-' }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-center py-5">
                                                <i class="fas fa-id-card fa-3x text-muted mb-3"></i>
                                                <p class="text-muted fs-5">Chưa có thông tin giấy tờ tùy thân</p>
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
    </div>
@endsection