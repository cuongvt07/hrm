@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">Quản lý tài khoản</h5>
                </div>
                <div class="col text-end">
                    <a href="{{ route('tai-khoan.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Thêm tài khoản
                    </a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <!-- Filter -->
            <form action="{{ route('tai-khoan.index') }}" method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Tìm kiếm..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="vai_tro" class="form-select">
                            <option value="">-- Vai trò --</option>
                            <option value="quan_tri" {{ request('vai_tro') == 'quan_tri' ? 'selected' : '' }}>Quản trị</option>
                            <option value="nhan_su" {{ request('vai_tro') == 'nhan_su' ? 'selected' : '' }}>Nhân sự</option>
                            <option value="quan_ly" {{ request('vai_tro') == 'quan_ly' ? 'selected' : '' }}>Quản lý</option>
                            <option value="nhan_vien" {{ request('vai_tro') == 'nhan_vien' ? 'selected' : '' }}>Nhân viên</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="trang_thai" class="form-select">
                            <option value="">-- Trạng thái --</option>
                            <option value="hoat_dong" {{ request('trang_thai') == 'hoat_dong' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="khong_hoat_dong" {{ request('trang_thai') == 'khong_hoat_dong' ? 'selected' : '' }}>Không hoạt động</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                        <a href="{{ route('tai-khoan.index') }}" class="btn btn-secondary">
                            <i class="fas fa-sync"></i> Làm mới
                        </a>
                    </div>
                </div>
            </form>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên đăng nhập</th>
                            <th>Email</th>
                            <th>Nhân viên</th>
                            <th>Phòng ban</th>
                            <th>Chức vụ</th>
                            <th>Vai trò</th>
                            <th>Trạng thái</th>
                            <th>Đăng nhập cuối</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($taiKhoans as $index => $taiKhoan)
                            <tr>
                                <td>{{ $taiKhoans->firstItem() + $index }}</td>
                                <td>{{ $taiKhoan->ten_dang_nhap }}</td>
                                <td>{{ $taiKhoan->email }}</td>
                                <td>
                                    @if($taiKhoan->nhanVien)
                                        {{ $taiKhoan->nhanVien->ho }} {{ $taiKhoan->nhanVien->ten }}
                                    @endif
                                </td>
                                <td>
                                    @if($taiKhoan->nhanVien && $taiKhoan->nhanVien->phongBan)
                                        {{ $taiKhoan->nhanVien->phongBan->ten_phong_ban }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($taiKhoan->nhanVien && $taiKhoan->nhanVien->chucVu)
                                        {{ $taiKhoan->nhanVien->chucVu->ten_chuc_vu }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @switch($taiKhoan->vai_tro)
                                        @case('quan_tri')
                                            <span class="badge bg-danger">Quản trị</span>
                                            @break
                                        @case('nhan_su')
                                            <span class="badge bg-primary">Nhân sự</span>
                                            @break
                                        @case('quan_ly')
                                            <span class="badge bg-success">Quản lý</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">Nhân viên</span>
                                    @endswitch
                                </td>
                                <td>
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input account-status-switch" type="checkbox"
                                            data-id="{{ $taiKhoan->id }}"
                                            {{ $taiKhoan->trang_thai == 'hoat_dong' ? 'checked' : '' }}>
                                        <span class="ms-2">
                                            {{ $taiKhoan->trang_thai == 'hoat_dong' ? 'Hoạt động' : 'Không hoạt động' }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    {{ $taiKhoan->lan_dang_nhap_cuoi ? $taiKhoan->lan_dang_nhap_cuoi->format('d/m/Y H:i') : 'Chưa đăng nhập' }}
                                </td>
                                <td>
                                    <a href="{{ route('tai-khoan.edit', $taiKhoan) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Không có dữ liệu</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                @if($taiKhoans->hasPages())
                    <x-pagination :paginator="$taiKhoans" />
                @endif
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.account-status-switch').forEach(function (el) {
        el.addEventListener('change', function () {
            const id = this.getAttribute('data-id');
            const status = this.checked ? 'hoat_dong' : 'khong_hoat_dong';
            fetch(`/tai-khoan/${id}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ trang_thai: status })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    this.nextElementSibling.textContent = status === 'hoat_dong' ? 'Hoạt động' : 'Không hoạt động';
                    showToast('Cập nhật trạng thái thành công!', 'success');
                } else {
                    showToast('Cập nhật trạng thái thất bại!', 'error');
                    this.checked = !this.checked;
                }
            })
            .catch(() => {
                showToast('Có lỗi xảy ra!', 'error');
                this.checked = !this.checked;
            });
        });
    });
});

function showToast(message, type = 'success') {
    let toast = document.createElement('div');
    toast.className = 'toast-message position-fixed top-0 end-0 m-3 p-3 text-white rounded shadow';
    toast.style.zIndex = 9999;
    toast.style.background = type === 'success' ? '#28a745' : '#dc3545';
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.remove();
    }, 2000);
}
</script>
@endpush
@endsection