@extends('layouts.app')

@section('title', 'Dashboard - HRM System')

@section('content')
<div class="container-fluid">
    <!-- Page header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">Dashboard</h2>
                    <p class="text-muted mb-0">Tổng quan hệ thống quản lý nhân sự</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats cards -->
    <div class="row mb-4">
        <!-- Total Employees -->
        <div class="col-xl-3 col-md-6 mb-4">
            <x-ui.card class="border-left-primary">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Tổng nhân viên
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_employees'] ?? 0) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </x-ui.card>
        </div>

        <!-- Active Employees -->
        <div class="col-xl-3 col-md-6 mb-4">
            <x-ui.card class="border-left-success">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Đang làm việc
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['active_employees'] ?? 0) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </x-ui.card>
        </div>

        <!-- Pending Leaves -->
        <div class="col-xl-3 col-md-6 mb-4">
            <x-ui.card class="border-left-warning">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Đơn nghỉ chờ duyệt
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['pending_leaves'] ?? 0) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </x-ui.card>
        </div>

        <!-- Expiring Contracts -->
        <div class="col-xl-3 col-md-6 mb-4">
            <x-ui.card class="border-left-danger">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Hợp đồng sắp hết hạn
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['expiring_contracts'] ?? 0) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </x-ui.card>
        </div>
    </div>

    <!-- Charts and tables -->
    <div class="row mb-4">
        <!-- Department Stats Chart -->
        <div class="col-xl-6 col-lg-12 mb-4">
            <x-ui.card title="Thống kê theo phòng ban">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="departmentChart"></canvas>
                </div>
            </x-ui.card>
        </div>

        <!-- Monthly Leave Stats Chart -->
        <div class="col-xl-6 col-lg-12 mb-4">
            <x-ui.card title="Nghỉ phép theo tháng">
                <div class="chart-bar pt-4 pb-2">
                    <canvas id="monthlyLeaveChart"></canvas>
                </div>
            </x-ui.card>
        </div>
    </div>

    <!-- Recent activities -->
    <div class="row">
        <!-- Expiring Contracts -->
        <div class="col-xl-6 col-lg-12 mb-4">
            <x-ui.card title="Hợp đồng sắp hết hạn">
                <div class="list-group list-group-flush">
                    @forelse($expiringContracts ?? [] as $contract)
                        <div class="list-group-item d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px;">
                                    <i class="fas fa-file-contract"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $contract->nhanVien->ho_ten ?? 'N/A' }}</h6>
                                <p class="mb-1 text-muted small">
                                    Hết hạn: {{ $contract->ngay_ket_thuc->format('d/m/Y') ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <x-ui.badge type="warning" size="sm">
                                    {{ $contract->ngay_ket_thuc->diffInDays(now()) ?? 0 }} ngày
                                </x-ui.badge>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-file-contract fa-3x mb-3"></i>
                            <p>Không có hợp đồng sắp hết hạn</p>
                        </div>
                    @endforelse
                </div>
            </x-ui.card>
        </div>

        <!-- Pending Leaves -->
        <div class="col-xl-6 col-lg-12 mb-4">
            <x-ui.card title="Đơn nghỉ chờ duyệt">
                <div class="list-group list-group-flush">
                    @forelse($pendingLeaves ?? [] as $leave)
                        <div class="list-group-item d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px;">
                                    <i class="fas fa-calendar-times"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $leave->nhanVien->ho_ten ?? 'N/A' }}</h6>
                                <p class="mb-1 text-muted small">
                                    {{ $leave->ngay_bat_dau->format('d/m/Y') ?? 'N/A' }} - {{ $leave->ngay_ket_thuc->format('d/m/Y') ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <x-ui.badge type="info" size="sm">
                                    {{ ucfirst(str_replace('_', ' ', $leave->loai_nghi ?? 'N/A')) }}
                                </x-ui.badge>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-calendar-times fa-3x mb-3"></i>
                            <p>Không có đơn nghỉ chờ duyệt</p>
                        </div>
                    @endforelse
                </div>
            </x-ui.card>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Department Chart
const departmentCtx = document.getElementById('departmentChart').getContext('2d');
new Chart(departmentCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode(($departmentStats ?? collect())->pluck('ten_phong_ban')->toArray()) !!},
        datasets: [{
            data: {!! json_encode(($departmentStats ?? collect())->pluck('nhan_viens_count')->toArray()) !!},
            backgroundColor: [
                '#4e73df',
                '#1cc88a',
                '#36b9cc',
                '#f6c23e',
                '#e74a3b'
            ],
            hoverBackgroundColor: [
                '#2e59d9',
                '#17a673',
                '#2c9faf',
                '#dda20e',
                '#e02d1b'
            ],
            hoverBorderColor: "rgba(234, 236, 244, 1)",
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
        },
        legend: {
            display: false
        },
        cutoutPercentage: 80,
    }
});

// Monthly Leave Chart
const monthlyLeaveCtx = document.getElementById('monthlyLeaveChart').getContext('2d');
new Chart(monthlyLeaveCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode(($monthlyLeaveStats ?? collect())->pluck('month')->toArray()) !!},
        datasets: [{
            label: 'Số đơn nghỉ',
            data: {!! json_encode(($monthlyLeaveStats ?? collect())->pluck('count')->toArray()) !!},
            backgroundColor: '#4e73df',
            hoverBackgroundColor: '#2e59d9',
            hoverBorderColor: "rgba(78, 115, 223, 1)",
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        layout: {
            padding: {
                left: 10,
                right: 25,
                top: 25,
                bottom: 0
            }
        },
        scales: {
            x: {
                time: {
                    unit: 'month'
                },
                gridLines: {
                    display: false,
                    drawBorder: false
                },
                ticks: {
                    maxTicksLimit: 6
                },
                maxBarThickness: 25,
            },
            y: {
                beginAtZero: true,
                gridLines: {
                    color: "rgb(234, 236, 244)",
                    zeroLineColor: "rgb(234, 236, 244)",
                    drawBorder: false,
                    borderDash: [2],
                    zeroLineBorderDash: [2]
                },
                ticks: {
                    padding: 20,
                    fontColor: "#858796"
                }
            }
        },
        legend: {
            display: false
        },
        tooltips: {
            titleMarginBottom: 10,
            titleFontColor: '#6e707e',
            titleFontSize: 14,
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
        }
    }
});
</script>
@endpush
@endsection
