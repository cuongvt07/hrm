<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <!-- Brand -->
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}" class="text-decoration-none">
            <i class="fas fa-users-cog me-2"></i>
            <span class="sidebar-brand-text">HRM System</span>
        </a>
        <button class="sidebar-toggle d-none d-lg-block" id="sidebarToggleBtn">
            <i class="fas fa-chevron-left"></i>
        </button>
    </div>

    <!-- Divider -->
    <hr class="sidebar-divider">


    <!-- Navigation -->
    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                   href="{{ route('dashboard') }}" title="Dashboard">
                    <i class="fas fa-tachometer-alt"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>

            <!-- Quản lý nhân viên -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('nhan-vien.*') ? 'active' : '' }}" 
                   href="{{ route('nhan-vien.index') }}" title="Quản lý nhân viên">
                    <i class="fas fa-users"></i>
                    <span class="nav-text">Nhân viên</span>
                </a>
            </li>

            <!-- Dropdown Quản lý hợp đồng -->
            @php
                $contractActive = request()->routeIs('hop-dong.*') || request()->routeIs('hop-dong.saphethan');
            @endphp
            <li class="nav-item">
                <a class="nav-link d-flex justify-content-between align-items-center {{ $contractActive ? 'active' : '' }}" data-bs-toggle="collapse" href="#contractDropdown" role="button" aria-expanded="{{ $contractActive ? 'true' : 'false' }}" aria-controls="contractDropdown">
                    <span><i class="fas fa-file-contract"></i> <span class="nav-text">Quản lý hợp đồng</span></span>
                    <i class="fas fa-chevron-down ms-2"></i>
                </a>
                <div class="collapse {{ $contractActive ? 'show' : '' }}" id="contractDropdown">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('hop-dong.index') ? 'active' : '' }}" href="{{ route('hop-dong.index') }}">
                                <i class="fas fa-file-contract"></i> Hợp đồng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('hop-dong.saphethan') ? 'active' : '' }}" href="{{ route('hop-dong.saphethan') }}">
                                <i class="fas fa-exclamation-triangle text-warning"></i> Hợp đồng sắp hết hạn
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Cài đặt hệ thống -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('cai-dat.*') ? 'active' : '' }}" href="{{ route('cai-dat.index') }}">
                    <i class="fas fa-cogs"></i>
                    <span class="nav-text">Cài đặt hệ thống</span>
                </a>
            </li>

            <!-- Chế độ Section -->
            <li class="nav-item">
                <div class="sidebar-heading">Chế độ</div>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('che-do.khen-thuong.index') ? 'active' : '' }}" 
                   href="{{ route('che-do.khen-thuong.index') }}" title="Khen thưởng">
                    <i class="fas fa-award text-warning"></i>
                    <span class="nav-text">Khen thưởng</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('che-do.ky-luat.index') ? 'active' : '' }}" 
                   href="{{ route('che-do.ky-luat.index') }}" title="Kỷ luật">
                    <i class="fas fa-gavel text-danger"></i>
                    <span class="nav-text">Kỷ luật</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Báo cáo -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('bao-cao.*') ? 'active' : '' }}" 
                   href="{{ route('bao-cao.index') }}" title="Báo cáo thống kê">
                    <i class="fas fa-chart-bar"></i>
                    <span class="nav-text">Báo cáo</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Footer -->
    <div class="sidebar-footer">
        <div class="text-center text-white-50 small">
            <div class="mb-2">
                <i class="fas fa-copyright"></i>
                {{ date('Y') }} HRM System
            </div>
            <div class="text-xs">
                Version 1.0.0
            </div>
        </div>
    </div>
</div>
