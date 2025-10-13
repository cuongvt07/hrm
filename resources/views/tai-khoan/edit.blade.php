@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Sửa tài khoản</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('tai-khoan.update', $taiKhoan) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nhân viên</label>
                            <input type="text" class="form-control" value="{{ $taiKhoan->nhanVien->ho }} {{ $taiKhoan->nhanVien->ten }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tên đăng nhập</label>
                            <input type="text" class="form-control" value="{{ $taiKhoan->ten_dang_nhap }}" disabled>
                        </div>

                        <div class="mb-3">
                            <label for="mat_khau" class="form-label">Mật khẩu mới</label>
                            <input type="password" class="form-control @error('mat_khau') is-invalid @enderror"
                                   id="mat_khau" name="mat_khau">
                            <small class="form-text text-muted">Để trống nếu không muốn thay đổi mật khẩu</small>
                            @error('mat_khau')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email', $taiKhoan->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="vai_tro" class="form-label">Vai trò</label>
                            <select name="vai_tro" id="vai_tro" class="form-select @error('vai_tro') is-invalid @enderror" required>
                                <option value="quan_tri" {{ old('vai_tro', $taiKhoan->vai_tro) == 'quan_tri' ? 'selected' : '' }}>Quản trị</option>
                                <option value="nhan_su" {{ old('vai_tro', $taiKhoan->vai_tro) == 'nhan_su' ? 'selected' : '' }}>Nhân sự</option>
                                <option value="quan_ly" {{ old('vai_tro', $taiKhoan->vai_tro) == 'quan_ly' ? 'selected' : '' }}>Quản lý</option>
                                <option value="nhan_vien" {{ old('vai_tro', $taiKhoan->vai_tro) == 'nhan_vien' ? 'selected' : '' }}>Nhân viên</option>
                            </select>
                            @error('vai_tro')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="trang_thai" class="form-label">Trạng thái</label>
                            <select name="trang_thai" id="trang_thai" class="form-select @error('trang_thai') is-invalid @enderror" required>
                                <option value="hoat_dong" {{ old('trang_thai', $taiKhoan->trang_thai) == 'hoat_dong' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="khong_hoat_dong" {{ old('trang_thai', $taiKhoan->trang_thai) == 'khong_hoat_dong' ? 'selected' : '' }}>Không hoạt động</option>
                            </select>
                            @error('trang_thai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu
                    </button>
                    <a href="{{ route('tai-khoan.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection