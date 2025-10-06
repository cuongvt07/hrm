@extends('layouts.app')
@section('title', $loai === 'ky_luat' ? 'Danh sách quyết định kỷ luật' : 'Danh sách quyết định khen thưởng')
@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">
            {{ $loai === 'ky_luat' ? 'Danh sách quyết định kỷ luật' : 'Danh sách quyết định khen thưởng' }}
        </h4>
    <a href="{{ route('che-do.khen-thuong-ky-luat.create', ['loai' => $loai]) }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tạo quyết định {{ $loai === 'ky_luat' ? 'kỷ luật' : 'khen thưởng' }}
        </a>
    </div>
    <form method="get" class="row g-2 mb-3 align-items-end">
        <div class="col-auto">
            <label class="form-label mb-0">Từ ngày</label>
            <input type="date" name="from" value="{{ request('from') }}" class="form-control form-control-sm">
        </div>
        <div class="col-auto">
            <label class="form-label mb-0">Đến ngày</label>
            <input type="date" name="to" value="{{ request('to') }}" class="form-control form-control-sm">
        </div>
        <div class="col-auto">
            <label class="form-label mb-0">Tìm kiếm</label>
            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Tiêu đề, số quyết định...">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Tìm kiếm
            </button>
        </div>
    </form>
    {{-- Bộ lọc (nếu có) --}}
    <div class="card">
        <div class="card-body p-0" id="tableContainer">
            <table class="table table-bordered table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        {{-- <th>Loại</th> --}} 
                        <th>Số quyết định</th>
                        <th>Tiêu đề</th>
                        <th>Ngày quyết định</th>
                        <th>Người quyết định</th>
                        <th>Trạng thái</th>
                        <th>Đối tượng áp dụng</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($khenThuongKyLuats as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            {{-- <td>
                                @if($item->loai === 'khen_thuong')
                                    <span class="badge bg-success">Khen thưởng</span>
                                @else
                                    <span class="badge bg-danger">Kỷ luật</span>
                                @endif
                            </td> --}}
                            <td>{{ $item->so_quyet_dinh }}</td>
                            <td>{{ $item->tieu_de }}</td>
                            <td>{{ $item->ngay_quyet_dinh ? $item->ngay_quyet_dinh->format('d/m/Y') : '' }}</td>
                            <td>{{ $item->nguoi_quyet_dinh }}</td>
                            <td>
                                @if($item->trang_thai === 'chua_thuc_hien')
                                    <span class="badge bg-secondary">Chưa thực hiện</span>
                                @elseif($item->trang_thai === 'dang_thuc_hien')
                                    <span class="badge bg-warning text-dark">Đang thực hiện</span>
                                @elseif($item->trang_thai === 'hoan_thanh')
                                    <span class="badge bg-success">Hoàn thành</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $nhanVienArr = $item->doiTuongApDung->where('loai_doi_tuong', 'nhan_vien')->pluck('nhanVien.ho_ten')->toArray();
                                    $phongBanArr = $item->doiTuongApDung->where('loai_doi_tuong', 'phong_ban')->pluck('phongBan.ten_phong_ban')->toArray();
                                @endphp
                                @if($nhanVienArr)
                                    <span class="text-primary">Nhân viên:</span> {{ implode(', ', $nhanVienArr) }}<br>
                                @endif
                                @if($phongBanArr)
                                    <span class="text-success">Phòng ban:</span> {{ implode(', ', $phongBanArr) }}
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('che-do.khen-thuong-ky-luat.show', $item->id) }}" class="btn btn-sm btn-info">Xem</a>
                                <!-- Có thể bổ sung sửa/xóa nếu cần -->
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3 px-3 pb-3">
                @if($khenThuongKyLuats->hasPages())
                    <x-pagination :paginator="$khenThuongKyLuats" />
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Nếu muốn ajax filter/phân trang thì tham khảo scripts của các màn hình khác
</script>
@endpush
