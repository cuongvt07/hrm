<!-- Employee Detail Overlay -->
<div class="employee-overlay" id="employeeDetailOverlay">
    <div class="employee-detail">
        <div class="employee-header">
            <h4 class="employee-title">Chi tiết nhân viên</h4>
            <div class="employee-actions">
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="closeEmployeeDetail()">
                    <i class="fas fa-times me-1"></i>Đóng
                </button>
                <button type="button" class="btn btn-primary btn-sm" onclick="editEmployee({{ $nhanVien->id }})">
                    <i class="fas fa-edit me-1"></i>Chỉnh sửa
                </button>
            </div>
        </div>
        <div class="employee-content">
                    <div class="row g-0">
                        <!-- Left Column - Basic Info (3 parts) -->
                        <div class="col-md-3">
                            <div class="p-4 border-end">
                                <!-- Avatar Section -->
                                <div class="text-center mb-4">
                                    @if($nhanVien->anh_dai_dien)
                                        <img src="{{ asset('storage/' . $nhanVien->anh_dai_dien) }}" 
                                             alt="Avatar" class="rounded-circle shadow" width="120" height="120" style="object-fit: cover;">
                                    @else
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto shadow" 
                                             style="width: 120px; height: 120px; font-size: 2.5rem;">
                                            {{ strtoupper(substr($nhanVien->ten, 0, 1)) }}
                                        </div>
                                    @endif
                                    <h4 class="mt-3 mb-1">{{ $nhanVien->ho }} {{ $nhanVien->ten }}</h4>
                                    <p class="text-muted mb-0">{{ $nhanVien->ma_nhanvien }}</p>
                                </div>

                                <!-- Basic Information -->
                                <div class="row align-items-center mb-3">
                                    <div class="col-4">
                                        <small class="text-muted">Email</small>
                                    </div>
                                    <div class="col-8">
                                        <small>{{ $nhanVien->email ?? '-' }}</small>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3">
                                    <div class="col-4">
                                        <small class="text-muted">Điện thoại</small>
                                    </div>
                                    <div class="col-8">
                                        <small>{{ $nhanVien->so_dien_thoai ?? '-' }}</small>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3">
                                    <div class="col-4">
                                        <small class="text-muted">Ngày sinh</small>
                                    </div>
                                    <div class="col-8">
                                        <small>{{ $nhanVien->ngay_sinh ? \Carbon\Carbon::parse($nhanVien->ngay_sinh)->format('d/m/Y') : '-' }}</small>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3">
                                    <div class="col-4">
                                        <small class="text-muted">Giới tính</small>
                                    </div>
                                    <div class="col-8">
                                        <small>{{ ucfirst($nhanVien->gioi_tinh) }}</small>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3">
                                    <div class="col-4">
                                        <small class="text-muted">Phòng ban</small>
                                    </div>
                                    <div class="col-8">
                                        <small>{{ $nhanVien->phongBan->ten_phong_ban ?? 'Chưa phân công' }}</small>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3">
                                    <div class="col-4">
                                        <small class="text-muted">Chức vụ</small>
                                    </div>
                                    <div class="col-8">
                                        <small>{{ $nhanVien->chucVu->ten_chuc_vu ?? 'Chưa phân công' }}</small>
                                    </div>
                                </div>
                                <div class="row align-items-center mb-3">
                                    <div class="col-4">
                                        <small class="text-muted">Trạng thái</small>
                                    </div>
                                    <div class="col-8">
                                        @php
                                            $statusClass = [
                                                'nhan_vien_chinh_thuc' => 'success',
                                                'thu_viec' => 'warning',
                                                'thai_san' => 'info',
                                                'nghi_viec' => 'danger',
                                                'khac' => 'secondary'
                                            ][$nhanVien->trang_thai] ?? 'secondary';
                                            
                                            $statusText = [
                                                'nhan_vien_chinh_thuc' => 'Đang làm việc',
                                                'thu_viec' => 'Thử việc',
                                                'thai_san' => 'Thai sản',
                                                'nghi_viec' => 'Đã nghỉ việc',
                                                'khac' => 'Khác'
                                            ][$nhanVien->trang_thai] ?? 'Khác';
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
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
                                                <table class="table table-sm">
                                                    <tr>
                                                        <td><strong>Họ tên đầy đủ:</strong></td>
                                                        <td>{{ $nhanVien->ho }} {{ $nhanVien->ten }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Mã nhân viên:</strong></td>
                                                        <td>{{ $nhanVien->ma_nhanvien }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Email:</strong></td>
                                                        <td>{{ $nhanVien->email ?? 'Chưa cập nhật' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Điện thoại:</strong></td>
                                                        <td>{{ $nhanVien->so_dien_thoai ?? 'Chưa cập nhật' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Ngày sinh:</strong></td>
                                                        <td>{{ $nhanVien->ngay_sinh ? \Carbon\Carbon::parse($nhanVien->ngay_sinh)->format('d/m/Y') : 'Chưa cập nhật' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Giới tính:</strong></td>
                                                        <td>{{ ucfirst($nhanVien->gioi_tinh) }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="text-muted mb-3">Thông tin liên hệ</h6>
                                                <table class="table table-sm">
                                                    <tr>
                                                        <td><strong>Địa chỉ:</strong></td>
                                                        <td>{{ $nhanVien->dia_chi ?? 'Chưa cập nhật' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>CMND/CCCD:</strong></td>
                                                        <td>{{ $nhanVien->so_cmnd ?? 'Chưa cập nhật' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Ngày cấp:</strong></td>
                                                        <td>{{ $nhanVien->ngay_cap_cmnd ? \Carbon\Carbon::parse($nhanVien->ngay_cap_cmnd)->format('d/m/Y') : 'Chưa cập nhật' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Nơi cấp:</strong></td>
                                                        <td>{{ $nhanVien->noi_cap_cmnd ?? 'Chưa cập nhật' }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Work Information Tab -->
                                    <div class="tab-pane fade" id="work" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="text-muted mb-3">Thông tin công việc</h6>
                                                <table class="table table-sm">
                                                    <tr>
                                                        <td><strong>Phòng ban:</strong></td>
                                                        <td>{{ $nhanVien->phongBan->ten_phong_ban ?? 'Chưa phân công' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Chức vụ:</strong></td>
                                                        <td>{{ $nhanVien->chucVu->ten_chuc_vu ?? 'Chưa phân công' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Trạng thái:</strong></td>
                                                        <td>
                                                            <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Ngày vào làm:</strong></td>
                                                        <td>{{ $nhanVien->ngay_vao_lam ? \Carbon\Carbon::parse($nhanVien->ngay_vao_lam)->format('d/m/Y') : 'Chưa cập nhật' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Ngày thử việc:</strong></td>
                                                        <td>{{ $nhanVien->ngay_thu_viec ? \Carbon\Carbon::parse($nhanVien->ngay_thu_viec)->format('d/m/Y') : 'Chưa cập nhật' }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="text-muted mb-3">Thâm niên</h6>
                                                @if($nhanVien->ngay_vao_lam)
                                                    @php
                                                        $startDate = \Carbon\Carbon::parse($nhanVien->ngay_vao_lam);
                                                        $now = \Carbon\Carbon::now();
                                                        $years = $startDate->diffInYears($now);
                                                        $months = $startDate->copy()->addYears($years)->diffInMonths($now);
                                                        $days = $startDate->copy()->addYears($years)->addMonths($months)->diffInDays($now);
                                                    @endphp
                                                    <div class="text-center">
                                                        <h3 class="text-primary">{{ $years }} năm {{ $months }} tháng</h3>
                                                        <p class="text-muted">Tính từ ngày {{ \Carbon\Carbon::parse($nhanVien->ngay_vao_lam)->format('d/m/Y') }}</p>
                                                    </div>
                                                @else
                                                    <p class="text-muted">Chưa có thông tin thâm niên</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contract Information Tab -->
                                    <div class="tab-pane fade" id="contract" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="text-muted mb-3">Thông tin hợp đồng</h6>
                                                <table class="table table-sm">
                                                    <tr>
                                                        <td><strong>Loại hợp đồng:</strong></td>
                                                        <td>{{ $nhanVien->loai_hop_dong ?? 'Chưa cập nhật' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Ngày bắt đầu:</strong></td>
                                                        <td>{{ $nhanVien->ngay_bat_dau_hop_dong ? \Carbon\Carbon::parse($nhanVien->ngay_bat_dau_hop_dong)->format('d/m/Y') : 'Chưa cập nhật' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Ngày kết thúc:</strong></td>
                                                        <td>{{ $nhanVien->ngay_ket_thuc_hop_dong ? \Carbon\Carbon::parse($nhanVien->ngay_ket_thuc_hop_dong)->format('d/m/Y') : 'Chưa cập nhật' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Lương cơ bản:</strong></td>
                                                        <td>{{ $nhanVien->luong_co_ban ? number_format($nhanVien->luong_co_ban) . ' VNĐ' : 'Chưa cập nhật' }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="text-muted mb-3">Trạng thái hợp đồng</h6>
                                                @if($nhanVien->ngay_ket_thuc_hop_dong)
                                                    @php
                                                        $endDate = \Carbon\Carbon::parse($nhanVien->ngay_ket_thuc_hop_dong);
                                                        $now = \Carbon\Carbon::now();
                                                        $daysLeft = $now->diffInDays($endDate, false);
                                                    @endphp
                                                    @if($daysLeft > 0)
                                                        <div class="alert alert-warning">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                                            Hợp đồng sẽ hết hạn trong {{ $daysLeft }} ngày
                                                        </div>
                                                    @elseif($daysLeft == 0)
                                                        <div class="alert alert-danger">
                                                            <i class="fas fa-exclamation-circle me-2"></i>
                                                            Hợp đồng hết hạn hôm nay
                                                        </div>
                                                    @else
                                                        <div class="alert alert-danger">
                                                            <i class="fas fa-times-circle me-2"></i>
                                                            Hợp đồng đã hết hạn {{ abs($daysLeft) }} ngày
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="alert alert-info">
                                                        <i class="fas fa-info-circle me-2"></i>
                                                        Hợp đồng không xác định thời hạn
                                                    </div>
                                                @endif
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
        </div>
    </div>
</div>

<script>
function closeEmployeeDetail() {
    const overlay = document.getElementById('employeeDetailOverlay');
    if (overlay) {
        overlay.style.display = 'none';
    }
}

// Close on overlay click
document.getElementById('employeeDetailOverlay').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEmployeeDetail();
    }
});

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeEmployeeDetail();
    }
});
</script>
</script>