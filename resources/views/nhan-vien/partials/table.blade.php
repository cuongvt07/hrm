<!-- Employee Table -->
<div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th width="50">
                    <input type="checkbox" id="selectAll" class="form-check-input" onchange="toggleSelectAll()">
                </th>
                <th width="50">Stt</th>
                <th>Mã NV</th>
                <th>Họ và tên</th>
                <th>Giới tính</th>
                <th>Ngày sinh</th>
                <th>Điện thoại</th>
                <th>Email</th>
                <th>Chức vụ</th>
                <th>Phòng ban</th>
                <th>Trạng thái</th>
                <th width="150">Thao tác</th>
            </tr>
        </thead>
        <tbody id="employeeTableBody">
            @forelse($nhanViens as $index => $nhanVien)
                <tr>
                    <td>
                        <input type="checkbox" class="form-check-input employee-checkbox" value="{{ $nhanVien->id }}"
                            onchange="updateSelectAllState()">
                    </td>
                    <td>{{ $nhanViens->firstItem() + $index }}</td>
                    <td>
                        <span class="fw-bold text-primary">{{ $nhanVien->ma_nhanvien }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div>
                                <div class="fw-bold">{{ $nhanVien->ho }} {{ $nhanVien->ten }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @php
                            $genderText = match ($nhanVien->gioi_tinh) {
                                'nam' => 'Nam',
                                'nu' => 'Nữ',
                                default => 'Khác'
                            };
                            $genderClass = match ($nhanVien->gioi_tinh) {
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
                    @component('components.table-action', [
                        'showRoute' => 'nhan-vien.show',
                        'editRoute' => 'nhan-vien.edit',
                        'deleteRoute' => 'nhan-vien.destroy',
                        'id' => $nhanVien->id
                    ])@endcomponent
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

    <div class="mt-3">
        @if($nhanViens->hasPages())
            <x-pagination :paginator="$nhanViens" />
        @endif
    </div>
</div>

<script>
    function toggleSelectAll() {
        const selectAll = document.getElementById("selectAll");
        const checkboxes = document.querySelectorAll(".employee-checkbox");
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateBulkDeleteBtn();
    }

    function updateSelectAllState() {
        const checkboxes = document.querySelectorAll(".employee-checkbox");
        const selectAll = document.getElementById("selectAll");
        selectAll.checked = [...checkboxes].every(cb => cb.checked);
        updateBulkDeleteBtn();
    }

    function updateBulkDeleteBtn() {
        const checked = document.querySelectorAll(".employee-checkbox:checked").length;
        document.getElementById("bulkDeleteBtn").disabled = checked === 0;
    }

    function deleteSelectedEmployees() {
        const ids = [...document.querySelectorAll(".employee-checkbox:checked")].map(cb => cb.value);

        if (ids.length === 0) {
            alert("Vui lòng chọn ít nhất 1 nhân viên!");
            return;
        }

        if (!confirm("Bạn có chắc muốn chuyển trạng thái nghỉ việc cho các nhân viên đã chọn?")) return;

        fetch("{{ route('nhan-vien.bulk-delete') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ ids })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert("Lỗi: " + data.message);
            }
        })
        .catch(err => console.error(err));
    }
</script>
