@extends('layouts.app')

@section('title', 'Báo cáo nhân sự - HRM System')

@section('content')
<div class="container-fluid">
    <!-- Page header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">Báo cáo nhân sự</h2>
                    <p class="text-muted mb-0">Thống kê số lượng nhân sự theo phòng ban và trạng thái</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="mb-4">
        <x-ui.card>
            <form method="GET" action="{{ route('bao-cao.nhan-su.index') }}" class="d-flex flex-wrap align-items-end gap-3 justify-content-center">
                <div>
                    <label class="form-label mb-1">Từ ngày</label>
                    <input type="date" class="form-control" name="from_date" id="from_date"
                        value="{{ optional($fromDate)->format('Y-m-d') }}">
                </div>
                <div>
                    <label class="form-label mb-1">Đến ngày</label>
                    <input type="date" class="form-control" name="to_date" id="to_date"
                        value="{{ optional($toDate)->format('Y-m-d') }}">
                </div>
                <div>
                    <label class="form-label mb-1">Chọn nhanh</label>
                    <select class="form-select" id="quick_range">
                        <option value="">-- Chọn --</option>
                        <option value="1m">1 tháng gần nhất</option>
                        <option value="3m">3 tháng gần nhất</option>
                        <option value="6m">6 tháng gần nhất</option>
                        <option value="1y">1 năm gần nhất</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-2"></i>Lọc dữ liệu
                    </button>
                </div>
                <div>
                    <a href="{{ route('bao-cao.nhan-su.export', ['from_date' => optional($fromDate)->format('Y-m-d'), 'to_date' => optional($toDate)->format('Y-m-d')]) }}"
                        class="btn btn-success">
                        <i class="fas fa-file-excel me-2"></i>Xuất Excel
                    </a>
                </div>
            </form>
        </x-ui.card>
    </div>

    <!-- Chart Section -->
    <div class="row mb-4">
        <div class="col-12">
            <x-ui.card>
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Biểu đồ số lượng nhân sự theo phòng ban</h5>
                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#chartCollapse" aria-expanded="true" aria-controls="chartCollapse">
                        <i class="fas fa-chevron-down me-1"></i> Thu gọn / Mở rộng
                    </button>
                </div>
                <div id="chartCollapse" class="collapse show mt-3">
                    <div class="chart-container" style="height: 400px;">
                        <canvas id="staffChart"></canvas>
                    </div>
                </div>
            </x-ui.card>
        </div>
    </div>

    <!-- Table Section -->
    <div class="row">
        <div class="col-12">
            <x-ui.card>
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Chi tiết loại hợp đồng nhân sự</h5>
                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#tableCollapse" aria-expanded="true" aria-controls="tableCollapse">
                        <i class="fas fa-chevron-down me-1"></i> Thu gọn / Mở rộng
                    </button>
                </div>
                <div id="tableCollapse" class="collapse show mt-3">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Đơn vị công tác</th>
                                    <th>Không có<br>hợp đồng</th>
                                    <th>Thử việc</th>
                                    <th>Hợp đồng<br>xác định thời hạn</th>
                                    <th>Hợp đồng<br>không xác định thời hạn</th>
                                    <th>Hợp đồng<br>tái ký</th>
                                    <th>Tổng cộng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tableData as $row)
                                <tr>
                                    <td class="text-start">{{ $row['phong_ban'] }}</td>
                                    <td>{{ number_format($row['khong_hop_dong']) }}</td>
                                    <td>{{ number_format($row['hop_dong_thu_viec']) }}</td>
                                    <td>{{ number_format($row['hop_dong_xac_dinh']) }}</td>
                                    <td>{{ number_format($row['hop_dong_khong_xac_dinh']) }}</td>
                                    <td>{{ number_format($row['hop_dong_tai_ki']) }}</td>
                                    <td>{{ number_format($row['tong_cong']) }}</td>
                                </tr>
                                @endforeach
                                @if(isset($totalRow))
                                <tr style="font-weight:bold; background:#f8f9fc;">
                                    <td class="text-start">{{ $totalRow['phong_ban'] }}</td>
                                    <td>{{ number_format($totalRow['khong_hop_dong']) }}</td>
                                    <td>{{ number_format($totalRow['hop_dong_thu_viec']) }}</td>
                                    <td>{{ number_format($totalRow['hop_dong_xac_dinh']) }}</td>
                                    <td>{{ number_format($totalRow['hop_dong_khong_xac_dinh']) }}</td>
                                    <td>{{ number_format($totalRow['hop_dong_tai_ki']) }}</td>
                                    <td>{{ number_format($totalRow['tong_cong']) }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </x-ui.card>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Quick range for date filters
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('quick_range').addEventListener('change', function() {
        const now = new Date();
        let from = '';
        let to = now.toISOString().slice(0,10);
        if (this.value === '1m') {
            const d = new Date(now); d.setMonth(d.getMonth() - 1);
            from = d.toISOString().slice(0,10);
        } else if (this.value === '3m') {
            const d = new Date(now); d.setMonth(d.getMonth() - 3);
            from = d.toISOString().slice(0,10);
        } else if (this.value === '6m') {
            const d = new Date(now); d.setMonth(d.getMonth() - 6);
            from = d.toISOString().slice(0,10);
        } else if (this.value === '1y') {
            const d = new Date(now); d.setFullYear(d.getFullYear() - 1);
            from = d.toISOString().slice(0,10);
        } else {
            from = '';
            to = '';
        }
        document.getElementById('from_date').value = from;
        document.getElementById('to_date').value = to;
    });
});
const ctx = document.getElementById('staffChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($phongBanStats->pluck('ten_phong_ban')) !!},
        datasets: [
            { label: 'Đang làm việc', data: {!! json_encode($phongBanStats->pluck('nhan_vien_chinh_thuc')) !!}, backgroundColor: '#1cc88a' },
            { label: 'Thử việc', data: {!! json_encode($phongBanStats->pluck('thu_viec')) !!}, backgroundColor: '#36b9cc' },
            { label: 'Thai sản', data: {!! json_encode($phongBanStats->pluck('thai_san')) !!}, backgroundColor: '#e83e8c' },
            { label: 'Nghỉ việc', data: {!! json_encode($phongBanStats->pluck('nghi_viec')) !!}, backgroundColor: '#e74a3b' }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: { x: { stacked: true }, y: { stacked: true } }
    }
});
</script>
@endpush
