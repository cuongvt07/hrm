<!-- Employee Table -->
<div class="table-responsive">
    <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th width="50">
                        <input type="checkbox" id="selectAll" class="form-check-input" onchange="toggleSelectAll()">
                    </th>
                    <th width="50">#</th>
                    <th>Mã NV</th>
                    <th>Họ và tên</th>
                    <th>Giới tính</th>
                    <th>Ngày sinh</th>
                    <th>Điện thoại</th>
                    <th>Email</th>
                    <th>Chức vụ</th>
                    <th>Phòng ban</th>
                    <th>Ngày thử việc</th>
                    <th>Ngày chính thức</th>
                    <th>Loại hợp đồng</th>
                    <th>Trạng thái</th>
                    <th>Thâm niên</th>
                    <th width="150">Thao tác</th>
                </tr>
            </thead>
            <tbody id="employeeTableBody">
                @forelse($nhanViens as $index => $nhanVien)
                <tr>
                    <td>
                        <input type="checkbox" class="form-check-input employee-checkbox"
                               value="{{ $nhanVien->id }}" onchange="updateSelectAllState()">
                    </td>
                    <td>{{ $nhanViens->firstItem() + $index }}</td>
                    <td>
                        <span class="fw-bold text-primary">{{ $nhanVien->ma_nhanvien }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            @if($nhanVien->anh_dai_dien)
                                <img src="{{ Storage::url($nhanVien->anh_dai_dien) }}"
                                     alt="Avatar" class="rounded-circle me-2" width="32" height="32">
                            @else
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2"
                                     style="width: 32px; height: 32px;">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                            @endif
                            <div>
                                <div class="fw-bold">{{ $nhanVien->ho }} {{ $nhanVien->ten }}</div>
                                @if($nhanVien->email)
                                    <small class="text-muted">{{ $nhanVien->email }}</small>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        @php
                            $genderText = match($nhanVien->gioi_tinh) {
                                'nam' => 'Nam',
                                'nu' => 'Nữ',
                                default => 'Khác'
                            };
                            $genderClass = match($nhanVien->gioi_tinh) {
                                'nam' => 'text-primary',
                                'nu' => 'text-danger',
                                default => 'text-secondary'
                            };
                        @endphp
                        <span class="badge bg-light {{ $genderClass }}">{{ $genderText }}</span>
                    </td>
                    <td>
                        @if($nhanVien->ngay_sinh)
                            {{ \Carbon\Carbon::parse($nhanVien->ngay_sinh)->format('d/m/Y') }}
                            <br><small class="text-muted">({{ \Carbon\Carbon::parse($nhanVien->ngay_sinh)->age }} tuổi)</small>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($nhanVien->so_dien_thoai)
                            <a href="tel:{{ $nhanVien->so_dien_thoai }}" class="text-decoration-none">
                                {{ $nhanVien->so_dien_thoai }}
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($nhanVien->email)
                            <a href="mailto:{{ $nhanVien->email }}" class="text-decoration-none">
                                {{ $nhanVien->email }}
                            </a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($nhanVien->chucVu)
                            <span class="badge bg-info">{{ $nhanVien->chucVu->ten_chuc_vu }}</span>
                        @else
                            <span class="text-muted">Chưa có</span>
                        @endif
                    </td>
                    <td>
                        @if($nhanVien->phongBan)
                            <span class="badge bg-secondary">{{ $nhanVien->phongBan->ten_phong_ban }}</span>
                        @else
                            <span class="text-muted">Chưa có</span>
                        @endif
                    </td>
                    <td>
                        @if($nhanVien->ngay_thu_viec)
                            {{ \Carbon\Carbon::parse($nhanVien->ngay_thu_viec)->format('d/m/Y') }}
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($nhanVien->ngay_vao_lam)
                            {{ \Carbon\Carbon::parse($nhanVien->ngay_vao_lam)->format('d/m/Y') }}
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($nhanVien->loai_hop_dong)
                            <span class="badge bg-dark">{{ $nhanVien->loai_hop_dong }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $statusConfig = [
                                'nhan_vien_chinh_thuc' => ['class' => 'success', 'text' => 'Đang làm việc'],
                                'thu_viec' => ['class' => 'warning', 'text' => 'Thử việc'],
                                'thai_san' => ['class' => 'info', 'text' => 'Thai sản'],
                                'nghi_viec' => ['class' => 'danger', 'text' => 'Đã nghỉ việc'],
                                'khac' => ['class' => 'secondary', 'text' => 'Khác']
                            ];
                            $config = $statusConfig[$nhanVien->trang_thai] ?? $statusConfig['khac'];
                        @endphp
                        <span class="badge bg-{{ $config['class'] }}">{{ $config['text'] }}</span>
                    </td>
                    <td>
                        @php
                            $thamNien = '-';
                            if ($nhanVien->ngay_vao_lam) {
                                $startDate = \Carbon\Carbon::parse($nhanVien->ngay_vao_lam);
                                $now = \Carbon\Carbon::now();
                                $years = $startDate->diffInYears($now);
                                $months = $startDate->diffInMonths($now) % 12;
                                if ($years > 0) {
                                    $thamNien = $years . ' năm ' . $months . ' tháng';
                                } else {
                                    $thamNien = $months . ' tháng';
                                }
                            }
                        @endphp
                        <span class="text-muted">{{ $thamNien }}</span>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('nhan-vien.show', $nhanVien) }}"
                               class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('nhan-vien.edit', $nhanVien) }}"
                               class="btn btn-sm btn-outline-secondary" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn"
                                    data-id="{{ $nhanVien->id }}"
                                    data-name="{{ $nhanVien->ho }} {{ $nhanVien->ten }}"
                                    title="Xóa">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="16" class="text-center py-4">
                        <div class="text-muted">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <p>Không tìm thấy nhân viên nào</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>