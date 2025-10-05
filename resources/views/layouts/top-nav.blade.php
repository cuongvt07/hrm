<!-- Top Navigation -->
<nav class="navbar navbar-expand-lg navbar-light topbar">
    <div class="container-fluid">
        <!-- Mobile menu button -->
        <button class="navbar-toggler d-lg-none" type="button" id="sidebarToggle">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Right side -->
        <ul class="navbar-nav ms-auto">

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Profile dropdown -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                        {{ Auth::user()->ten_dang_nhap ?? 'User' }}
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow">
                    <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>
