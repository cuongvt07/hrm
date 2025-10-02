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
    <div class="card mb-3">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-4"><strong>Loại quyết định:</strong> 
                    @if($item->loai === 'khen_thuong')
                        <span class="badge bg-success">Khen thưởng</span>
                    @else
                        <span class="badge bg-danger">Kỷ luật</span>
                    @endif
                </div>
                <div class="col-md-4">
                    <strong>Trạng thái:</strong>
                    @if($item->trang_thai === 'chua_thuc_hien')
                        <span class="badge bg-secondary">Chưa thực hiện</span>
                    @elseif($item->trang_thai === 'dang_thuc_hien')
                        <span class="badge bg-warning text-dark">Đang thực hiện</span>
                    @elseif($item->trang_thai === 'hoan_thanh')
                        <span class="badge bg-success">Hoàn thành</span>
                    @endif
                </div>
                <div class="col-md-4"><strong>Số quyết định:</strong> {{ $item->so_quyet_dinh }}</div>
                <div class="col-md-4"><strong>Ngày quyết định:</strong> {{ $item->ngay_quyet_dinh ? $item->ngay_quyet_dinh->format('d/m/Y') : '' }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4"><strong>Người quyết định:</strong> {{ $item->nguoi_quyet_dinh }}</div>
                <div class="col-md-8"><strong>Tiêu đề:</strong> {{ $item->tieu_de }}</div>
            </div>
            <div class="mb-2"><strong>Mô tả:</strong> {{ $item->mo_ta }}</div>
            <div class="mb-2"><strong>Giá trị:</strong> {{ $item->gia_tri ? number_format($item->gia_tri, 0, ',', '.') . ' VNĐ' : '-' }}</div>
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
    </div>
    <a href="{{ $item->loai === 'ky_luat' ? route('che-do.ky-luat.index') : route('che-do.khen-thuong.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
@endsection
