@extends('layouts.app')
@section('title', 'Chi tiết quyết định khen thưởng/kỷ luật')
@section('content')
    <h3 class="mb-4">
        Chi tiết quyết định 
        @if($item->loai === 'khen_thuong')
            khen thưởng
        @else
            kỷ luật
        @endif
        : {{ $item->tieu_de }}
    </h3>
    <form action="{{ route('che-do.khen-thuong-ky-luat.update', $item->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="loai" value="{{ $item->loai }}">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-4">
                        <strong>Loại quyết định:</strong> 
                        @if($item->loai === 'khen_thuong')
                            <span class="badge bg-success">Khen thưởng</span>
                        @else
                            <span class="badge bg-danger">Kỷ luật</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <strong>Trạng thái:</strong>
                        <select name="trang_thai" class="form-select">
                            <option value="chua_thuc_hien" {{ $item->trang_thai === 'chua_thuc_hien' ? 'selected' : '' }}>Chưa thực hiện</option>
                            <option value="dang_thuc_hien" {{ $item->trang_thai === 'dang_thuc_hien' ? 'selected' : '' }}>Đang thực hiện</option>
                            <option value="hoan_thanh" {{ $item->trang_thai === 'hoan_thanh' ? 'selected' : '' }}>Hoàn thành</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <strong>Số quyết định:</strong>
                        <input type="text" name="so_quyet_dinh" class="form-control" value="{{ $item->so_quyet_dinh }}">
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-4">
                        <strong>Ngày quyết định:</strong>
                        <input type="date" name="ngay_quyet_dinh" class="form-control" value="{{ $item->ngay_quyet_dinh ? $item->ngay_quyet_dinh->format('Y-m-d') : '' }}" required>
                    </div>
                    <div class="col-md-4">
                        <strong>Người quyết định:</strong>
                        <input type="text" name="nguoi_quyet_dinh" class="form-control" value="{{ $item->nguoi_quyet_dinh }}">
                    </div>
                    <div class="col-md-4">
                        <strong>Giá trị:</strong>
                        <input type="number" name="gia_tri" class="form-control" value="{{ $item->gia_tri }}" min="0">
                    </div>
                </div>
                <div class="mb-2">
                    <strong>Tiêu đề:</strong>
                    <input type="text" name="tieu_de" class="form-control" value="{{ $item->tieu_de }}" required>
                </div>
                <div class="mb-2">
                    <strong>Mô tả:</strong>
                    <textarea name="mo_ta" class="form-control" rows="2">{{ $item->mo_ta }}</textarea>
                </div>
                <div class="mb-2">
                    <strong>Đối tượng áp dụng:</strong>
                    <div class="d-flex flex-wrap gap-2 mt-2">
                        @php
                            $nhanViens = $item->doiTuongApDung->where('loai_doi_tuong', 'nhan_vien')->pluck('nhanVien')->filter();
                            $phongBans = $item->doiTuongApDung->where('loai_doi_tuong', 'phong_ban')->pluck('phongBan')->filter();
                        @endphp
                        @foreach($nhanViens as $nv)
                            <a href="{{ route('nhan-vien.show', $nv->id) }}" class="badge bg-primary text-white text-decoration-none" style="font-size:1rem;">
                                <i class="fas fa-user"></i> {{ $nv->ho }} {{ $nv->ten }}
                            </a>
                        @endforeach
                        @foreach($phongBans as $pb)
                            <span class="badge bg-success text-white" style="font-size:1rem;">
                                <i class="fas fa-users"></i> {{ $pb->ten_phong_ban }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <a href="{{ $item->loai === 'ky_luat' ? route('che-do.ky-luat.index') : route('che-do.khen-thuong.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </div>
        </div>
    </form>
@endsection
