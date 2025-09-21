<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'HRM System')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <!-- Custom HRM CSS -->
    <link rel="stylesheet" href="{{ asset('css/hrm-custom.css') }}">
    
    
    @stack('styles')
</head>
<body>
    <div class="min-h-screen bg-light">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Page Content -->
        <div class="main-content">
            <!-- Top Navigation -->
            @include('layouts.top-nav')

            <!-- Main Content -->
            <main class="py-4">
                <div class="container-fluid">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <!-- Custom HRM JavaScript -->
    <script src="{{ asset('js/hrm-employee.js') }}"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Global functions
        window.HRM = {
            // Show loading spinner
            showLoading: function(element) {
                if (typeof element === 'string') {
                    element = document.querySelector(element);
                }
                if (element) {
                    element.innerHTML = '<div class="spinner"></div>';
                }
            },

            // Hide loading spinner
            hideLoading: function(element) {
                if (typeof element === 'string') {
                    element = document.querySelector(element);
                }
                if (element) {
                    element.innerHTML = '';
                }
            },

            // Show success message
            showSuccess: function(message) {
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: message,
                    timer: 3000,
                    showConfirmButton: false
                });
            },

            // Show error message
            showError: function(message) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: message
                });
            },

            // Show confirmation dialog
            confirm: function(message, callback) {
                Swal.fire({
                    title: 'Xác nhận',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Xác nhận',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed && callback) {
                        callback();
                    }
                });
            },

            // Format currency
            formatCurrency: function(amount) {
                return new Intl.NumberFormat('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                }).format(amount);
            },

            // Format date
            formatDate: function(date) {
                return new Intl.DateTimeFormat('vi-VN').format(new Date(date));
            },

            // AJAX helper
            ajax: function(url, options = {}) {
                const defaultOptions = {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                };

                const mergedOptions = { ...defaultOptions, ...options };

                return fetch(url, mergedOptions)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .catch(error => {
                        console.error('AJAX Error:', error);
                        this.showError('Có lỗi xảy ra khi tải dữ liệu');
                        throw error;
                    });
            }
        };

        // Initialize on DOM ready
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Flatpickr for date inputs
            const dateInputs = document.querySelectorAll('input[type="date"]');
            dateInputs.forEach(input => {
                flatpickr(input, {
                    dateFormat: 'Y-m-d',
                    locale: 'vi'
                });
            });

            // Auto-hide alerts
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, 5000);
            });

            // Form validation
            const forms = document.querySelectorAll('form[data-validate]');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const requiredFields = this.querySelectorAll('[required]');
                    let isValid = true;

                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            field.classList.add('border-red-500');
                            isValid = false;
                        } else {
                            field.classList.remove('border-red-500');
                        }
                    });

                    if (!isValid) {
                        e.preventDefault();
                        HRM.showError('Vui lòng điền đầy đủ thông tin bắt buộc');
                    }
                });
            });

            // Mobile sidebar toggle
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            
            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });

                // Close sidebar when clicking outside on mobile
                document.addEventListener('click', function(e) {
                    if (window.innerWidth <= 768) {
                        if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                            sidebar.classList.remove('show');
                        }
                    }
                });

                // Close sidebar when window is resized to desktop
                window.addEventListener('resize', function() {
                    if (window.innerWidth > 768) {
                        sidebar.classList.remove('show');
                    }
                });
            }

            // Desktop sidebar collapse/expand toggle
            const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
            const sidebarStatusText = document.getElementById('sidebarStatusText');
            
            if (sidebarToggleBtn && sidebar && mainContent) {
                // Load saved state from localStorage
                const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                if (isCollapsed) {
                    sidebar.classList.add('collapsed');
                    mainContent.classList.add('sidebar-collapsed');
                    if (sidebarStatusText) {
                        sidebarStatusText.textContent = 'Sidebar thu gọn';
                    }
                }

                sidebarToggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('sidebar-collapsed');
                    
                    // Update status text
                    if (sidebarStatusText) {
                        const isCollapsed = sidebar.classList.contains('collapsed');
                        sidebarStatusText.textContent = isCollapsed ? 'Sidebar thu gọn' : 'Sidebar mở rộng';
                    }
                    
                    // Save state to localStorage
                    const isCollapsed = sidebar.classList.contains('collapsed');
                    localStorage.setItem('sidebarCollapsed', isCollapsed);
                });
            }

            // Pending task icons click handlers
            const pendingTaskIcons = document.querySelectorAll('.pending-task-icon');
            pendingTaskIcons.forEach(icon => {
                icon.addEventListener('click', function() {
                    const title = this.getAttribute('title');
                    console.log('Clicked pending task:', title);
                    
                    // Show notification
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Pending Task',
                            text: title,
                            icon: 'info',
                            confirmButtonText: 'OK',
                            timer: 3000,
                            timerProgressBar: true
                        });
                    } else {
                        alert('Pending Task: ' + title);
                    }
                });
            });
        });

        // Global functions to open/close modal
        window.openModal = function(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        };

        window.closeModal = function(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        };
    </script>
    
    @stack('scripts')
</body>
</html>
