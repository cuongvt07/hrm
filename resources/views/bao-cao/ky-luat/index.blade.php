@extends('layouts.app')
@section('title', 'Báo cáo kỷ luật')

@section('content')
<div class="container-fluid">
    <!-- Tiêu đề -->
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h3 mb-2 text-gray-800">Báo cáo kỷ luật</h1>
    </div>

    <!-- Filter -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('bao-cao.ky-luat.index') }}" class="row g-3">
                <input type="hidden" name="active_tab" id="active_tab" value="{{ request('active_tab', 'ca-nhan') }}">
                <div class="col-md-4">
                    <label for="tu_ngay" class="form-label">Từ ngày</label>
                    <input type="date" class="form-control" id="tu_ngay" name="tu_ngay" 
                           value="{{ request('tu_ngay') }}">
                </div>
                <div class="col-md-4">
                    <label for="den_ngay" class="form-label">Đến ngày</label>
                    <input type="date" class="form-control" id="den_ngay" name="den_ngay"
                           value="{{ request('den_ngay') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search"></i> Tìm kiếm
                    </button>
                    <button type="reset" class="btn btn-secondary me-2">
                        <i class="fas fa-sync"></i> Đặt lại
                    </button>
                    <a href="{{ route('bao-cao.ky-luat.export-ca-nhan', request()->all()) }}" 
                    class="btn me-2 btn-success {{ request('active_tab', 'ca-nhan') == 'ca-nhan' ? '' : 'd-none' }}" 
                    id="export-ca-nhan">
                        <i class="fas fa-file-excel"></i> Xuất Excel
                    </a>
                    <a href="{{ route('bao-cao.ky-luat.export-phong-ban', request()->all()) }}" 
                    class="btn me-2 btn-success {{ request('active_tab') == 'phong-ban' ? '' : 'd-none' }}"
                    id="export-phong-ban">
                        <i class="fas fa-file-excel"></i> Xuất Excel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabs -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ request('active_tab', 'ca-nhan') == 'ca-nhan' ? 'active' : '' }}" 
                       id="ca-nhan-tab" 
                       href="{{ route('bao-cao.ky-luat.index', array_merge(request()->except('active_tab'), ['active_tab' => 'ca-nhan'])) }}">
                        Kỷ luật cá nhân
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('active_tab') == 'phong-ban' ? 'active' : '' }}" 
                       id="phong-ban-tab"
                       href="{{ route('bao-cao.ky-luat.index', array_merge(request()->except('active_tab'), ['active_tab' => 'phong-ban'])) }}">
                        Kỷ luật phòng ban
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <!-- Tab Kỷ luật cá nhân -->
                <div class="tab-pane fade {{ request('active_tab', 'ca-nhan') == 'ca-nhan' ? 'show active' : '' }}" id="ca-nhan" role="tabpanel" aria-labelledby="ca-nhan-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Mã nhân viên</th>
                                    <th>Họ và tên</th>
                                    <th>Đơn vị công tác</th>
                                    <th>Vị trí công việc</th>
                                    <th>Ngày kỷ luật</th>
                                    <th>Ngày quyết định</th>
                                    <th>Đợt kỷ luật</th>
                                    <th>Hình thức kỷ luật</th>
                                    <th>Giá trị phạt</th>
                                    <th>Mô tả</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kyLuatCaNhan as $index => $kyLuat)
                                    @foreach($kyLuat->doiTuongApDung as $doiTuong)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $doiTuong->nhanVien->ma_nhanvien }}</td>
                                            <td>{{ $doiTuong->nhanVien->ho_ten }}</td>
                                            <td>{{ $doiTuong->nhanVien->phongBan->ten_phong_ban }}</td>
                                            <td>{{ $doiTuong->nhanVien->chucVu->ten_chuc_vu }}</td>
                                            <td>{{ Carbon\Carbon::parse($kyLuat->ngay_quyet_dinh)->format('d/m/Y') }}</td>
                                            <td>{{ Carbon\Carbon::parse($kyLuat->ngay_quyet_dinh)->format('d/m/Y') }}</td>
                                            <td>{{ $kyLuat->tieu_de }}</td>
                                            <td>
                                                {{ $kyLuat->gia_tri ? 'Phạt tiền' : 'Khác' }}
                                            </td>
                                            <td>
                                                {{ $kyLuat->gia_tri !== null ? number_format($kyLuat->gia_tri, 0, ',', '.') . ' VNĐ' : '-' }}
                                            </td>
                                            <td>{{ $kyLuat->mo_ta }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab Kỷ luật phòng ban -->
                <div class="tab-pane fade {{ request('active_tab') == 'phong-ban' ? 'show active' : '' }}" id="phong-ban" role="tabpanel" aria-labelledby="phong-ban-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Đơn vị</th>
                                    <th>Ngày kỷ luật</th>
                                    <th>Ngày quyết định</th>
                                    <th>Đợt kỷ luật</th>
                                    <th>Hình thức kỷ luật</th>
                                    <th>Giá trị phạt</th>
                                    <th>Mô tả</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kyLuatPhongBan as $index => $kyLuat)
                                    @foreach($kyLuat->doiTuongApDung as $doiTuong)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $doiTuong->phongBan->ten_phong_ban }}</td>
                                            <td>{{ Carbon\Carbon::parse($kyLuat->ngay_quyet_dinh)->format('d/m/Y') }}</td>
                                            <td>{{ Carbon\Carbon::parse($kyLuat->ngay_quyet_dinh)->format('d/m/Y') }}</td>
                                            <td>{{ $kyLuat->tieu_de }}</td>
                                            <td>
                                                {{ $kyLuat->gia_tri ? 'Phạt tiền' : 'Khác' }}
                                            </td>
                                            <td>
                                                {{ $kyLuat->gia_tri !== null ? number_format($kyLuat->gia_tri, 0, ',', '.') . ' VNĐ' : '-' }}
                                            </td>               
                                            <td>{{ $kyLuat->mo_ta }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Xử lý khi click nút reset
    $('button[type="reset"]').click(function(e) {
        e.preventDefault();
        $('#tu_ngay, #den_ngay').val('');
        $('form').submit();
    });

    // Xử lý chuyển tab bằng links thay vì Bootstrap tabs
    $('.nav-link').click(function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        window.location.href = href;
    });

    // Xử lý active tab dựa vào URL
    var activeTab = '{{ request("active_tab", "ca-nhan") }}';
    $('.nav-link').removeClass('active');
    $('#' + activeTab + '-tab').addClass('active');
    $('.tab-pane').removeClass('show active');
    $('#' + activeTab).addClass('show active');
});
</script>
@endpush