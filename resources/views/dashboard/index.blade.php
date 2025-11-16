@extends('layouts.app')

@section('title', 'Dashboard - HRM System')

@section('content')
<div class="container-fluid">
    <!-- Page header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0">Tổng quan tình hình nhân sự</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats cards -->

    <div class="row mb-4">
        <!-- Tổng nhân viên -->
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

        <!-- Đang làm việc -->
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

        <!-- Thử việc -->
        <div class="col-xl-3 col-md-6 mb-4">
            <x-ui.card class="border-left-info">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Thử việc
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['probation_employees'] ?? 0) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </x-ui.card>
        </div>

        <!-- Đã nghỉ việc -->
        <div class="col-xl-3 col-md-6 mb-4">
            <x-ui.card class="border-left-warning">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Đã nghỉ việc
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['resigned_employees'] ?? 0) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </x-ui.card>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Thai sản -->
        <div class="col-xl-3 col-md-6 mb-4">
            <x-ui.card class="border-left-pink">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-pink text-uppercase mb-1">
                                Thai sản
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['maternity_employees'] ?? 0) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-baby fa-2x text-gray-300"></i>
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

        <!-- Biểu đồ biến động nhân sự -->
        <div class="col-xl-6 col-lg-12 mb-4">
            <x-ui.card title="Biểu đồ biến động nhân sự">
                <div class="chart-bar pt-4 pb-2">
                    <canvas id="staffChangeChart"></canvas>
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
                                <h6 class="mb-1">
                                    @if(isset($contract->nhanVien) && $contract->nhanVien)
                                        <a href="{{ route('hop-dong.giahan.form', $contract->id) }}" class="text-decoration-underline text-primary">{{ $contract->nhanVien->ho_ten }}</a>
                                    @else
                                        N/A
                                    @endif
                                </h6>
                                <p class="mb-1 text-muted small">
                                    Hết hạn: {{ optional($contract->ngay_ket_thuc)->format('d/m/Y') ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <x-ui.badge type="warning" size="sm">
                                    {{ intval(optional($contract->ngay_ket_thuc)->diffInDays(now()) ?? 0) }} ngày
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
    </div>
</div>

@push('scripts')
<script>
@php
    // Lấy tất cả phòng ban cho biểu đồ (bao gồm cả phòng ban không có nhân viên)
    $chartDepartments = $departmentStats ?? collect();
@endphp

// Department Chart
const departmentCtx = document.getElementById('departmentChart').getContext('2d');
new Chart(departmentCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($chartDepartments->pluck('ten_phong_ban')->toArray()) !!},
        datasets: [{
            data: {!! json_encode($chartDepartments->pluck('nhan_viens_count')->toArray()) !!},
            backgroundColor: [
                '#4e73df',
                '#1cc88a',
                '#36b9cc',
                '#f6c23e',
                '#e74a3b',
                '#6f42c1',
                '#fd7e14',
                '#20c997',
                '#dc3545',
                '#ffc107'
            ],
            hoverBackgroundColor: [
                '#2e59d9',
                '#17a673',
                '#2c9faf',
                '#dda20e',
                '#e02d1b',
                '#5a32a3',
                '#e8680d',
                '#1aa085',
                '#c82333',
                '#e0a800'
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

// Biểu đồ biến động nhân sự (Tăng/Giảm)
const staffChangeCtx = document.getElementById('staffChangeChart').getContext('2d');
new Chart(staffChangeCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode(($staffChangeStats['months'] ?? [])) !!},
        datasets: [
            {
                label: 'Tăng',
                data: {!! json_encode(($staffChangeStats['increase'] ?? [])) !!},
                borderColor: '#1cc88a',
                backgroundColor: 'rgba(28,200,138,0.1)',
                fill: true,
                tension: 0.3,
            },
            {
                label: 'Giảm',
                data: {!! json_encode(($staffChangeStats['decrease'] ?? [])) !!},
                borderColor: '#e74a3b',
                backgroundColor: 'rgba(231,74,59,0.1)',
                fill: true,
                tension: 0.3,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: true },
            tooltip: {
                mode: 'index',
                intersect: false,
            }
        },
        interaction: {
            mode: 'nearest',
            axis: 'x',
            intersect: false
        },
        scales: {
            x: {
                title: { display: true, text: 'Tháng' },
            },
            y: {
                beginAtZero: true,
                title: { display: true, text: 'Số lượng' },
            }
        }
    }
});
</script>
@endpush
@endsection
