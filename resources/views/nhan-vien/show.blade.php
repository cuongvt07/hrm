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
                        Chỉnh sửa
                    </a>
                    <a href="{{ route('nhan-vien.index') }}" class="btn btn-outline-secondary">
                        Quay lại danh sách
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
                                        <span class="badge bg-{{ $config['class'] }} px-2 py-1" style="font-size: 0.75rem;">{{ $config['text'] }}</span>
                                    </div>
                                    <div class="mb-3">
                                        @if($nhanVien->taiKhoan)
                                            <a href="{{ route('tai-khoan.edit', $nhanVien->taiKhoan->id) }}" class="badge bg-success px-2 py-1 text-decoration-none" style="font-size: 0.75rem;">
                                                Đã có tài khoản nhân viên
                                            </a>
                                        @else
                                            <a href="{{ route('tai-khoan.create', ['nhan_vien_id' => $nhanVien->id]) }}" class="badge bg-secondary px-2 py-1 text-decoration-none" style="font-size: 0.75rem;">
                                                Chưa có tài khoản nhân viên
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                <!-- Quick Info -->
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">Thông tin nhanh</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <small class="text-dark">Email:</small>
                                            </div>
                                            <div class="fw-bold small">{{ $nhanVien->email ?? '-' }}</div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <small class="text-dark">Điện thoại:</small>
                                            </div>
                                            <div class="fw-bold small">{{ $nhanVien->so_dien_thoai ?? '-' }}</div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <small class="text-dark">Ngày sinh:</small>
                                            </div>
                                            <div class="fw-bold small">{{ $nhanVien->ngay_sinh ? \Carbon\Carbon::parse($nhanVien->ngay_sinh)->format('d/m/Y') : '-' }}</div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <small class="text-dark">Giới tính:</small>
                                            </div>
                                            <div class="fw-bold small">{{ $nhanVien->gioi_tinh == 'nam' ? 'Nam' : 'Nữ' }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Work Info Summary -->
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0">Công việc</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <small class="text-dark">Phòng ban:</small>
                                            </div>
                                            <div class="fw-bold small">{{ $nhanVien->phongBan ? $nhanVien->phongBan->ten_phong_ban : '-' }}</div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <small class="text-dark">Chức vụ:</small>
                                            </div>
                                            <div class="fw-bold small">{{ $nhanVien->chucVu ? $nhanVien->chucVu->ten_chuc_vu : '-' }}</div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <small class="text-dark">Ngày vào làm:</small>
                                            </div>
                                            <div class="fw-bold small">{{ $nhanVien->ngay_vao_lam ? \Carbon\Carbon::parse($nhanVien->ngay_vao_lam)->format('d/m/Y') : '-' }}</div>
                                        </div>
                                        @if(in_array($nhanVien->trang_thai, ['nhan_vien_chinh_thuc', 'thai_san']))
                                            <div class="mb-0">
                                                <div class="d-flex justify-content-between align-items-center py-1">
                                                    <small class="text-dark">Thâm niên:</small>
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
                                                    <small class="text-dark">Ngày thử việc:</small>
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
                            <div class=" p-4">
                                <ul class="nav nav-tabs flex-nowrap nav-tabs-scroll" id="employeeTabs" role="tablist">
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
                                        <a class="nav-link" id="ktkl-tab" data-bs-toggle="tab" href="#ktkl" role="tab" aria-controls="ktkl" aria-selected="false">Khen thưởng & Kỷ luật</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="family-tab" data-bs-toggle="tab" href="#family" role="tab" aria-controls="family" aria-selected="false">Gia đình</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="giayto-tab" data-bs-toggle="tab" href="#giayto" role="tab" aria-controls="giayto" aria-selected="false">Giấy tờ & Chứng chỉ</a>
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
                                    <!-- Khen thưởng & Kỷ luật Tab -->
                                    <div class="tab-pane fade" id="ktkl" role="tabpanel" aria-labelledby="ktkl-tab">
                                        <div class="row">
                                            <div class="col-12 mb-4">
                                                <div class="card border-0 shadow-sm">
                                                    <div class="card-header bg-success text-white">
                                                        <h5 class="mb-0">Khen thưởng</h5>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        @if($khenThuong->count())
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered mb-0">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th>Quyết định</th>
                                                                        <th>Ngày</th>
                                                                        <th>Khen thưởng</th>
                                                                        <th>Mô tả</th>
                                                                        <th>Giá trị</th>
                                                                        <th>Đối tượng</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($khenThuong as $item)
                                                                    <tr>
                                                                        <td>{{ $item->khenThuongKyLuat->so_quyet_dinh ?? '-' }}</td>
                                                                        <td>{{ $item->khenThuongKyLuat->ngay_quyet_dinh ? \Carbon\Carbon::parse($item->khenThuongKyLuat->ngay_quyet_dinh)->format('d/m/Y') : '-' }}</td>
                                                                        <td>{{ $item->khenThuongKyLuat->tieu_de ?? '-' }}</td>
                                                                        <td>{{ $item->khenThuongKyLuat->mo_ta ?? '-' }}</td>
                                                                        <td>{{ $item->khenThuongKyLuat->gia_tri ? number_format($item->khenThuongKyLuat->gia_tri) : '-' }}</td>
                                                                        <td>
                                                                            @if($item->loai_doi_tuong == 'nhan_vien')
                                                                                Cá nhân
                                                                            @elseif($item->loai_doi_tuong == 'phong_ban')
                                                                                Tập thể ({{ $item->phongBan->ten_phong_ban ?? '-' }})
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        @else
                                                            <div class="text-center text-muted py-3">Chưa có khen thưởng</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="card border-0 shadow-sm">
                                                    <div class="card-header bg-danger text-white">
                                                        <h5 class="mb-0">Kỷ luật</h5>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        @if($kyLuat->count())
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered mb-0">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th>Quyết định</th>
                                                                        <th>Ngày</th>
                                                                        <th>Kỷ luật</th>
                                                                        <th>Mô tả</th>
                                                                        <th>Giá trị</th>
                                                                        <th>Đối tượng</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($kyLuat as $item)
                                                                    <tr>
                                                                        <td>{{ $item->khenThuongKyLuat->so_quyet_dinh ?? '-' }}</td>
                                                                        <td>{{ $item->khenThuongKyLuat->ngay_quyet_dinh ? \Carbon\Carbon::parse($item->khenThuongKyLuat->ngay_quyet_dinh)->format('d/m/Y') : '-' }}</td>
                                                                        <td>{{ $item->khenThuongKyLuat->tieu_de ?? '-' }}</td>
                                                                        <td>{{ $item->khenThuongKyLuat->mo_ta ?? '-' }}</td>
                                                                        <td>{{ $item->khenThuongKyLuat->gia_tri ? number_format($item->khenThuongKyLuat->gia_tri) : '-' }}</td>
                                                                        <td>
                                                                            @if($item->loai_doi_tuong == 'nhan_vien')
                                                                                Cá nhân
                                                                            @elseif($item->loai_doi_tuong == 'phong_ban')
                                                                                Tập thể ({{ $item->phongBan->ten_phong_ban ?? '-' }})
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        @else
                                                            <div class="text-center text-muted py-3">Chưa có kỷ luật</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Basic Info Tab -->
                                    <div class="tab-pane fade show active" id="basic" role="tabpanel" aria-labelledby="basic-tab">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="card border-0 bg-light mb-4">
                                                    <div class="card-header bg-primary text-white">
                                                        <h5 class="mb-0">Thông tin cơ bản</h5>
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
                                            <div class="col-md-7">
                                                <div class="card border-0 bg-light mb-4">
                                                    <div class="card-header bg-info text-white">
                                                        <h5 class="mb-0">Thông tin bổ sung</h5>
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
                                                        <h5 class="mb-0">Thông tin công việc</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row g-3">
                                                            <div class="col-12">
                                                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                                    <strong class="text-dark">Phòng ban:</strong>
                                                                    <span class="fw-bold">
                                                                        @if($nhanVien->phongBan)
                                                                            {{ $nhanVien->phongBan->ten_phong_ban }}
                                                                            @if($nhanVien->phongBan->phongBanCha)
                                                                                <span class="text-muted small">(thuộc: {{ $nhanVien->phongBan->phongBanCha->ten_phong_ban }})</span>
                                                                            @endif
                                                                        @else
                                                                            -
                                                                        @endif
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                                    <strong class="text-dark">Chức vụ:</strong>
                                                                    <span class="fw-bold">{{ $nhanVien->chucVu ? $nhanVien->chucVu->ten_chuc_vu : '-' }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                                    <strong class="text-dark">Quản lý trực tiếp:</strong>
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
                                                                    <strong class="text-dark">Nhân viên cấp dưới:</strong>
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
                                                                    <strong class="text-dark">Ngày vào làm:</strong>
                                                                    <span class="fw-bold">{{ $nhanVien->ngay_vao_lam ? \Carbon\Carbon::parse($nhanVien->ngay_vao_lam)->format('d/m/Y') : '-' }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="d-flex justify-content-between align-items-center py-2">
                                                                    <strong class="text-dark">Ngày thử việc:</strong>
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
                                                        <h5 class="mb-0">Trạng thái & Hợp đồng</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row g-3">
                                                            <div class="col-12">
                                                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                                    <strong class="text-dark">Trạng thái:</strong>
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
                                                                        <strong class="text-dark">Loại hợp đồng:</strong>
                                                                        <span class="fw-bold">
                                                                            {{ $nhanVien->hopDongLaoDong->last()->loai_hop_dong ?? '-' }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            <div class="col-12">
                                                                <div class="d-flex justify-content-between align-items-center py-2">
                                                                    <strong class="text-dark">Thâm niên:</strong>
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


                                            <div>
                                                <h5 class="mb-2 fw-bold" style="background:none;padding:0;margin-bottom:8px;">Quá trình công tác</h5>
                                                <div class="timeline-qtct" style="position:relative;">
                                                    @forelse($nhanVien->quaTrinhCongTac as $qt)
                                                    <div class="timeline-item mb-4" style="position:relative;padding-left:32px;">
                                                        <span class="timeline-dot" style="position:absolute;left:0;top:35px;width:16px;height:16px;background:#0d6efd;border-radius:50%;border:2px solid #fff;"></span>
                                                        <div class="card border-0 shadow-sm" style="background:#f8f9fa;">
                                                            <div class="card-body py-2 px-3">
                                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                                    <span class="fw-bold">
                                                                        <span class="fw-bold">Chức vụ:</span>
                                                                        <span class="text-dark">{{ $qt->chucVu->ten_chuc_vu ?? '' }}</span>
                                                                    </span>
                                                                </div>
                                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                                    <span class="fw-bold">Phòng ban:</span>
                                                                    <span class="fw-bold">{{ $qt->phongBan->ten_phong_ban ?? '' }}</span>
                                                                    <span class="text-muted small ms-auto">{{ $qt->ngay_bat_dau }} - {{ $qt->ngay_ket_thuc ?? '...' }}</span>
                                                                </div>
                                                                <div class="mb-1"><span class="fw-bold">Mô tả:</span> {{ $qt->mo_ta }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @empty
                                                    <div class="text-center text-muted py-3">Chưa có dữ liệu</div>
                                                    @endforelse
                                                    <div style="position:absolute;left:8px;top:0;bottom:0;width:2px;background:#dee2e6;"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Contract Information -->
                                        @if($nhanVien->hopDongLaoDong && $nhanVien->hopDongLaoDong->count() > 0)
                                            <div class="row mt-4">
                                                <div class="col-12">
                                                    <div class="card border-0 shadow-sm">
                                                        <div class="card-header bg-dark text-white">
                                                            <h5 class="mb-0">Hợp đồng lao động</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            @foreach($nhanVien->hopDongLaoDong as $hopDong)
                                                                <div class="border rounded p-3 mb-3 bg-white">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="mb-2">
                                                                                <strong class="text-dark">Số hợp đồng:</strong>
                                                                                <span class="ms-2">{{ $hopDong->so_hop_dong }}</span>
                                                                            </div>
                                                                            <div class="mb-2">
                                                                                <strong class="text-dark">Ngày ký:</strong>
                                                                                <span class="ms-2">{{ $hopDong->ngay_ky ? \Carbon\Carbon::parse($hopDong->ngay_ky)->format('d/m/Y') : '-' }}</span>
                                                                            </div>
                                                                            <div class="mb-2">
                                                                                <strong class="text-dark">Ngày bắt đầu:</strong>
                                                                                <span class="ms-2">{{ $hopDong->ngay_bat_dau ? \Carbon\Carbon::parse($hopDong->ngay_bat_dau)->format('d/m/Y') : '-' }}</span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="mb-2">
                                                                                <strong class="text-dark">Ngày kết thúc:</strong>
                                                                                <span class="ms-2">{{ $hopDong->ngay_ket_thuc ? \Carbon\Carbon::parse($hopDong->ngay_ket_thuc)->format('d/m/Y') : '-' }}</span>
                                                                            </div>
                                                                            <div class="mb-2">
                                                                                <strong class="text-dark">Loại hợp đồng:</strong>
                                                                                <span class="ms-2">{{ $hopDong->loai_hop_dong }}</span>
                                                                            </div>
                                                                            <div class="mb-2">
                                                                                <strong class="text-dark">Trạng thái:</strong>
                                                                                <span class="ms-2">
                                                                                    @php
                                                                                        $contractStatusConfig = [
                                                                                            'hieu_luc' => ['text' => 'Hiệu lực', 'class' => 'success'],
                                                                                            'het_hieu_luc' => ['text' => 'Hết hiệu lực', 'class' => 'secondary'],
                                                                                        ];
                                                                                        $contractConfig = $contractStatusConfig[$hopDong->trang_thai] ?? $contractStatusConfig['het_hieu_luc'];
                                                                                    @endphp
                                                                                    <span class="badge bg-{{ $contractConfig['class'] }}">{{ $contractConfig['text'] }}</span>
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
                                                        <h5 class="mb-0">Thông tin lương</h5>
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
                                                            <h5 class="mb-0">Số điện thoại</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row g-3">
                                                                <div class="col-12">
                                                                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                                        <strong class="text-dark">Điện thoại di động:</strong>
                                                                        <span class="fw-bold">{{ $nhanVien->thongTinLienHe->dien_thoai_di_dong ?? '-' }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="d-flex justify-content-between align-items-center py-2">
                                                                        <strong class="text-dark">Điện thoại khác:</strong>
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
                                                            <h5 class="mb-0">Email</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row g-3">
                                                                <div class="col-12">
                                                                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                                        <strong class="text-dark">Email cơ quan:</strong>
                                                                        <span class="fw-bold">{{ $nhanVien->thongTinLienHe->email_co_quan ?? '-' }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="d-flex justify-content-between align-items-center py-2">
                                                                        <strong class="text-dark">Email cá nhân khác:</strong>
                                                                        <span class="fw-bold">{{ $nhanVien->thongTinLienHe->email_ca_nhan ?? '-' }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="card border-0 bg-light mb-4">
                                                        <div class="card-header bg-warning text-dark">
                                                            <h5 class="mb-0">Địa chỉ</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row g-3">
                                                                <div class="col-12">
                                                                    <div class="d-flex justify-content-between align-items-start py-2 border-bottom">
                                                                        <strong class="text-dark">Địa chỉ thường trú:</strong>
                                                                        <span class="fw-bold text-end">{{ $nhanVien->thongTinLienHe->dia_chi_thuong_tru ?? '-' }}</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="d-flex justify-content-between align-items-start py-2">
                                                                        <strong class="text-dark">Địa chỉ hiện tại:</strong>
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
                                                            <h5 class="mb-0">Liên hệ khẩn cấp</h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="text-center p-3 bg-light rounded">
                                                                        <!-- ...existing code... -->
                                                                        <h6 class="text-muted">Tên</h6>
                                                                        <strong class="d-block">{{ $nhanVien->thongTinLienHe->lien_he_khan_cap_ten ?? '-' }}</strong>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="text-center p-3 bg-light rounded">
                                                                        <!-- ...existing code... -->
                                                                        <h6 class="text-muted">Quan hệ</h6>
                                                                        <strong class="d-block">{{ $nhanVien->thongTinLienHe->lien_he_khan_cap_quan_he ?? '-' }}</strong>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="text-center p-3 bg-light rounded">
                                                                        <!-- ...existing code... -->
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
                                                <!-- ...existing code... -->
                                                <p class="text-muted fs-5">Chưa có thông tin liên hệ bổ sung</p>
                                            </div>
                                        @endif
                                    </div>
                                    <!-- Family Tab -->
                                    <div class="tab-pane fade" id="family" role="tabpanel" aria-labelledby="family-tab">
                                        @if($nhanVien->thongTinGiaDinh && $nhanVien->thongTinGiaDinh->count() > 0)
                                            <div class="card border-0 shadow-sm">
                                                <div class="card-header bg-success text-white">
                                                    <h5 class="mb-0">Thành viên gia đình</h5>
                                                </div>
                                                <div class="card-body p-0">
                                                    @foreach($nhanVien->thongTinGiaDinh as $member)
                                                        <div class="border-bottom p-3 {{ $loop->last ? '' : 'border-bottom' }}">
                                                            <div class="row align-items-center">
                                                                <div class="col-md-3">
                                                                    <div class="d-flex align-items-center">
                                                                        <!-- ...existing code... -->
                                                                        <div>
                                                                            <h6 class="mb-0 fw-bold">{{ $member->quan_he }}</h6>
                                                                            <small class="text-muted">{{ $member->ho_ten }}</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="text-center">
                                                                        <!-- ...existing code... -->
                                                                        <div>
                                                                            <small class="text-muted d-block">Ngày sinh</small>
                                                                            <strong>{{ $member->ngay_sinh ? $member->ngay_sinh->format('d/m/Y') : '-' }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="text-center">
                                                                        <!-- ...existing code... -->
                                                                        <div>
                                                                            <small class="text-muted d-block">Nghề nghiệp</small>
                                                                            <strong>{{ $member->nghe_nghiep ?? '-' }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <div class="text-center">
                                                                        <!-- ...existing code... -->
                                                                        <div>
                                                                            <small class="text-muted d-block">Điện thoại</small>
                                                                            <strong>{{ $member->dien_thoai ?? '-' }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-center py-5">
                                                <!-- ...existing code... -->
                                                <p class="text-muted fs-5">Chưa có thông tin thành viên gia đình</p>
                                            </div>
                                        @endif
                                    </div>
                                    <!-- Documents Tab -->
                                    <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                                        @if($nhanVien->tepTin && $nhanVien->tepTin->count() > 0)
                                            <div class="card border-0 shadow-sm">
                                                <div class="card-header bg-primary text-white">
                                                    <h5 class="mb-0">Tài liệu đính kèm</h5>
                                                </div>
                                                <div class="card-body p-0">
                                                    @foreach($nhanVien->tepTin as $file)
                                                        <div class="d-flex align-items-center p-3 border-bottom {{ $loop->last ? '' : 'border-bottom' }}">
                                                            <div class="flex-shrink-0 me-3">
                                                                @if(str_contains($file->kieu_mime, 'pdf'))
                                                                    <!-- ...existing code... -->
                                                                @elseif(str_contains($file->kieu_mime, 'image'))
                                                                    <!-- ...existing code... -->
                                                                @elseif(str_contains($file->kieu_mime, 'word') || str_contains($file->kieu_mime, 'document'))
                                                                    <!-- ...existing code... -->
                                                                @elseif(str_contains($file->kieu_mime, 'excel') || str_contains($file->kieu_mime, 'spreadsheet'))
                                                                    <!-- ...existing code... -->
                                                                @else
                                                                    <!-- ...existing code... -->
                                                                @endif
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-1">{{ $file->ten_tep }}</h6>
                                                                <div class="row">
                                                                    <div class="col-md-4">
                                                                        <small class="text-muted">
                                                                            <!-- ...existing code... -->
                                                                            <span class="badge bg-info">{{ ucfirst($file->loai_tep) }}</span>
                                                                        </small>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <small class="text-muted">
                                                                            {{ $file->kieu_mime }}
                                                                        </small>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <small class="text-muted">
                                                                            {{ $file->created_at->format('d/m/Y H:i') }}
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="flex-shrink-0">
                                                                @if($file->duong_dan_tep)
                                                                    <a href="{{ asset('storage/' . $file->duong_dan_tep) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                                        Tải xuống
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-center py-5">
                                                <!-- ...existing code... -->
                                                <p class="text-muted fs-5">Chưa có tài liệu nào được upload</p>
                                            </div>
                                        @endif
                                    </div>
                                    <!-- Insurance Tab -->
                                    <div class="tab-pane fade" id="insurance" role="tabpanel" aria-labelledby="insurance-tab">
                                        <div class="card border-0 bg-light mb-4">
                                            <div class="card-header bg-info text-white">
                                                <h5 class="mb-0">Thông tin bảo hiểm</h5>
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
                                                <div class="card-body p-0">
                                                    <table class="table table-bordered mb-0">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Loại giấy tờ</th>
                                                                <th>Tên</th>
                                                                <th>Số giấy tờ</th>
                                                                <th>Ngày cấp</th>
                                                                <th>Nơi cấp</th>
                                                                <th>Ngày hết hạn</th>
                                                                <th>File</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($nhanVien->thongTinGiayTo as $giayTo)
                                                                <tr>
                                                                    <td>
                                                                        @switch($giayTo->loai_giay_to)
                                                                            @case('giay_to_tuy_than')
                                                                                Giấy tờ tùy thân
                                                                                @break
                                                                            @case('chung_chi')
                                                                                Chứng chỉ
                                                                                @break
                                                                            @case('bang_cap')
                                                                                Bằng cấp
                                                                                @break
                                                                            @case('khac')
                                                                                Khác
                                                                                @break
                                                                            @default
                                                                                {{ $giayTo->loai_giay_to }}
                                                                        @endswitch
                                                                    </td>
                                                                    <td>{{ $giayTo->ghi_chu ?? '-' }}</td>
                                                                    <td>{{ $giayTo->so_giay_to }}</td>
                                                                    <td>{{ $giayTo->ngay_cap ? \Carbon\Carbon::parse($giayTo->ngay_cap)->format('d/m/Y') : '-' }}</td>
                                                                    <td>{{ $giayTo->noi_cap ?? '-' }}</td>
                                                                    <td>{{ $giayTo->ngay_het_han ? \Carbon\Carbon::parse($giayTo->ngay_het_han)->format('d/m/Y') : '-' }}</td>
                                                                    <td>
                                                                        @if($giayTo->tepTin && $giayTo->tepTin->duong_dan_tep)
                                                                            <a href="{{ asset('storage/' . $giayTo->tepTin->duong_dan_tep) }}" target="_blank">Xem file</a>
                                                                        @else
                                                                            <span class="text-muted">Không có file</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-center py-5">
                                                <!-- ...existing code... -->
                                                <p class="text-muted fs-5">Chưa có thông tin Giấy tờ & chứng chỉ</p>
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