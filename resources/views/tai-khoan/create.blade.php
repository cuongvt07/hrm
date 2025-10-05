@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Thêm tài khoản mới</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('tai-khoan.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nhan_vien_id" class="form-label">Nhân viên</label>
                            <select name="nhan_vien_id" id="nhan_vien_id" class="form-select @error('nhan_vien_id') is-invalid @enderror" required>
                                <option value="">-- Chọn nhân viên --</option>
                                @foreach($nhanViens as $nhanVien)
                                    <option value="{{ $nhanVien->id }}" {{ old('nhan_vien_id') == $nhanVien->id ? 'selected' : '' }}>
                                        {{ $nhanVien->ho }} {{ $nhanVien->ten }} - {{ $nhanVien->ma_nhan_vien }}
                                        @if($nhanVien->phongBan)
                                            | {{ $nhanVien->phongBan->ten_phong_ban }}
                                        @endif
                                        @if($nhanVien->chucVu)
                                            | {{ $nhanVien->chucVu->ten_chuc_vu }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('nhan_vien_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="ten_dang_nhap" class="form-label">Tên đăng nhập</label>
                            <input type="text" class="form-control @error('ten_dang_nhap') is-invalid @enderror"
                                   id="ten_dang_nhap" name="ten_dang_nhap" value="{{ old('ten_dang_nhap') }}" required>
                            @error('ten_dang_nhap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="mat_khau" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control @error('mat_khau') is-invalid @enderror"
                                   id="mat_khau" name="mat_khau" required>
                            @error('mat_khau')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="vai_tro" class="form-label">Vai trò</label>
                            <select name="vai_tro" id="vai_tro" class="form-select @error('vai_tro') is-invalid @enderror" required>
                                <option value="">-- Chọn vai trò --</option>
                                <option value="quan_tri" {{ old('vai_tro') == 'quan_tri' ? 'selected' : '' }}>Quản trị</option>
                                <option value="nhan_su" {{ old('vai_tro') == 'nhan_su' ? 'selected' : '' }}>Nhân sự</option>
                                <option value="quan_ly" {{ old('vai_tro') == 'quan_ly' ? 'selected' : '' }}>Quản lý</option>
                                <option value="nhan_vien" {{ old('vai_tro') == 'nhan_vien' ? 'selected' : '' }}>Nhân viên</option>
                            </select>
                            @error('vai_tro')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="trang_thai" class="form-label">Trạng thái</label>
                            <select name="trang_thai" id="trang_thai" class="form-select @error('trang_thai') is-invalid @enderror" required>
                                <option value="hoat_dong" {{ old('trang_thai', 'hoat_dong') == 'hoat_dong' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="khong_hoat_dong" {{ old('trang_thai') == 'khong_hoat_dong' ? 'selected' : '' }}>Không hoạt động</option>
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