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

            <!-- Quản lý hợp đồng -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('hop-dong.*') ? 'active' : '' }}" 
                   href="{{ route('hop-dong.index') }}" title="Quản lý hợp đồng">
                    <i class="fas fa-file-contract"></i>
                    <span class="nav-text">Hợp đồng</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Chế độ Section -->
            <li class="nav-item">
                <div class="sidebar-heading">Chế độ</div>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('che-do.khen-thuong-ky-luat.*') ? 'active' : '' }}" 
                   href="{{ route('che-do.khen-thuong-ky-luat.index') }}" title="Khen thưởng & Kỷ luật">
                    <i class="fas fa-award"></i>
                    <span class="nav-text">Khen thưởng & Kỷ luật</span>
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
