<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <!-- Brand -->
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}" class="text-decoration-none">
            <i class="fas fa-users-cog me-2"></i>
            <span class="sidebar-brand-text">
                <?php
                    echo optional(optional(optional(App\Models\CaiDatHeThong::where('gia_tri_cai_dat', 'cai-dat-chung')->first())->cai_dat_item)->first())->ten_item ?? '-';
                ?>
            </span>
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
                    <span class="nav-text">HomePage</span>
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

            <!-- Quản lý tài khoản -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('tai-khoan.*') ? 'active' : '' }}" 
                   href="{{ route('tai-khoan.index') }}" title="Quản lý tài khoản">
                    <i class="fas fa-user-cog"></i>
                    <span class="nav-text">Tài khoản</span>
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
                    <ul class="nav flex-column ms-1">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('hop-dong.index') ? 'active' : '' }}" href="{{ route('hop-dong.index') }}">
                                <i class="fas fa-file-contract"></i> Hợp đồng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('hop-dong.saphethan') ? 'active' : '' }}" href="{{ route('hop-dong.saphethan') }}">
                                <i class="fas fa-exclamation-triangle"></i> Hợp đồng sắp hết hạn
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Dropdown Chế độ -->
            @php
                $cheDoActive = request()->routeIs('che-do.khen-thuong.index') || request()->routeIs('che-do.ky-luat.index');
            @endphp
            <li class="nav-item">
                <a class="nav-link d-flex justify-content-between align-items-center {{ $cheDoActive ? 'active' : '' }}" data-bs-toggle="collapse" href="#cheDoDropdown" role="button" aria-expanded="{{ $cheDoActive ? 'true' : 'false' }}" aria-controls="cheDoDropdown">
                    <span><i class="fas fa-gift"></i> <span class="nav-text">Chế độ</span></span>
                    <i class="fas fa-chevron-down ms-2"></i>
                </a>
                <div class="collapse {{ $cheDoActive ? 'show' : '' }}" id="cheDoDropdown">
                    <ul class="nav flex-column ms-1">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('che-do.khen-thuong.index') ? 'active' : '' }}" href="{{ route('che-do.khen-thuong.index') }}">
                                <i class="fas fa-award text-white"></i> Khen thưởng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('che-do.ky-luat.index') ? 'active' : '' }}" href="{{ route('che-do.ky-luat.index') }}">
                                <i class="fas fa-gavel text-white"></i> Kỷ luật
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Dropdown Báo cáo -->
            @php
                $reportActive = request()->routeIs('bao-cao.*');
            @endphp
            <li class="nav-item">
                <a class="nav-link d-flex justify-content-between align-items-center {{ $reportActive ? 'active' : '' }}" 
                   data-bs-toggle="collapse" href="#reportDropdown" role="button" 
                   aria-expanded="{{ $reportActive ? 'true' : 'false' }}" 
                   aria-controls="reportDropdown">
                    <span>
                        <i class="fas fa-chart-bar"></i>
                        <span class="nav-text">Báo cáo thống kê</span>
                    </span>
                    <i class="fas fa-chevron-down ms-2"></i>
                </a>
                <div class="collapse {{ $reportActive ? 'show' : '' }}" id="reportDropdown">
                    <ul class="nav flex-column ms-1">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('bao-cao.nhan-su.*') ? 'active' : '' }}" 
                               href="{{ route('bao-cao.nhan-su.index') }}">
                                <i class="fas fa-users"></i> Phòng ban nhân sự
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('bao-cao.khen-thuong.*') ? 'active' : '' }}" 
                               href="{{ route('bao-cao.khen-thuong.index') }}">
                                <i class="fas fa-award"></i> Khen thưởng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('bao-cao.ky-luat.*') ? 'active' : '' }}" 
                               href="{{ route('bao-cao.ky-luat.index') }}">
                                <i class="fas fa-gavel"></i> Kỷ luật
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
        </ul>
    </nav>

    <!-- Footer -->
    <div class="sidebar-footer">
        <div class="text-center text-white-50 small">
            <div class="mb-2">
                <i class="fas fa-copyright"></i>
                {{ date('Y') }}                <?php
                    echo optional(optional(optional(App\Models\CaiDatHeThong::where('gia_tri_cai_dat', 'cai-dat-chung')->first())->cai_dat_item)->first())->ten_item ?? '-';
                ?>
            </div>
            <div class="text-xs">
                Version 1.0.0
            </div>
        </div>
    </div>
</div>
<style>
/* Sidebar mặc định */
.sidebar {
  width: 250px;
  background: #2d2f33;
  color: #fff;
  transition: width 0.3s ease;
  overflow: hidden;
}

/* Sidebar thu gọn */
.sidebar.collapsed {
  width: 80px; /* chỉ còn icon */
}

/* Khi thu gọn, ẩn text */
.sidebar.collapsed .nav-text {
  display: none;
}

/* Căn giữa icon khi thu gọn */
.sidebar .nav-link {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 20px;
  transition: all 0.3s ease;
}

.sidebar.collapsed .nav-link {
  justify-content: center;
  padding: 10px 0;
}

/* Icon căn giữa và to hơn chút */
.sidebar .nav-link i {
  font-size: 18px;
}

/* Tên brand */
.sidebar-brand {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 15px;
}

.sidebar.collapsed .sidebar-brand-text {
  display: none;
}

.sidebar.collapsed .sidebar-brand {
  justify-content: center;
}

/* Footer căn giữa */
.sidebar-footer {
  text-align: center;
  padding: 10px;
}

.sidebar.collapsed .sidebar-footer div {
  font-size: 12px;
}

/* Đảm bảo ẩn mũi tên dropdown khi thu gọn */
.sidebar.collapsed .fa-chevron-down,
.sidebar.collapsed .collapse {
  display: none !important;
}
</style>
