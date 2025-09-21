/**
 * HRM Employee Management JavaScript
 * Handles AJAX operations, modal interactions, and form validations
 */

class HrmEmployee {
    constructor() {
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupFormValidation();
        this.setupImagePreview();
        this.setupTooltips();
    }

    setupEventListeners() {
        // Filter form submission
        const filterForm = document.getElementById('filterForm');
        if (filterForm) {
            filterForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.filterEmployees();
            });
        }

        // Search input with debounce
        const searchInput = document.getElementById('search');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', () => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.filterEmployees();
                }, 500);
            });
        }

        // Select change events
        ['phong_ban_id', 'chuc_vu_id', 'trang_thai'].forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener('change', () => this.filterEmployees());
            }
        });

        // Employee form submission
        const employeeForm = document.getElementById('employeeForm');
        if (employeeForm) {
            employeeForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.saveEmployee();
            });
        }

        // Image upload preview
        const imageInput = document.getElementById('anh_dai_dien');
        if (imageInput) {
            imageInput.addEventListener('change', (e) => {
                this.handleImagePreview(e);
            });
        }
    }

    setupFormValidation() {
        // Real-time validation
        const requiredFields = document.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            field.addEventListener('blur', () => {
                this.validateField(field);
            });
        });

        // Email validation
        const emailFields = document.querySelectorAll('input[type="email"]');
        emailFields.forEach(field => {
            field.addEventListener('blur', () => {
                this.validateEmail(field);
            });
        });

        // Phone validation
        const phoneFields = document.querySelectorAll('input[type="tel"]');
        phoneFields.forEach(field => {
            field.addEventListener('blur', () => {
                this.validatePhone(field);
            });
        });
    }

    setupImagePreview() {
        const imageInput = document.getElementById('anh_dai_dien');
        if (imageInput) {
            imageInput.addEventListener('change', (e) => {
                this.handleImagePreview(e);
            });
        }
    }

    setupTooltips() {
        // Initialize Bootstrap tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    // Filter employees with AJAX
    async filterEmployees() {
        try {
            this.showLoading();
            
            const formData = new FormData(document.getElementById('filterForm'));
            const params = new URLSearchParams(formData);
            
            const response = await fetch(`${window.location.pathname}?${params.toString()}`);
            const html = await response.text();
            
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Update table body
            const newTableBody = doc.querySelector('#employeeTableBody');
            if (newTableBody) {
                document.getElementById('employeeTableBody').innerHTML = newTableBody.innerHTML;
            }
            
            // Update total count
            const newTotal = doc.querySelector('#totalEmployees');
            if (newTotal) {
                document.getElementById('totalEmployees').textContent = newTotal.textContent;
            }
            
            // Update pagination
            const newPagination = doc.querySelector('.pagination');
            if (newPagination) {
                const paginationContainer = document.querySelector('.pagination');
                if (paginationContainer) {
                    paginationContainer.innerHTML = newPagination.innerHTML;
                }
            }
            
            this.hideLoading();
            this.showAlert('Dữ liệu đã được cập nhật', 'success');
            
        } catch (error) {
            console.error('Error filtering employees:', error);
            this.hideLoading();
            this.showAlert('Có lỗi xảy ra khi tải dữ liệu', 'error');
        }
    }

    // Reset filter
    resetFilter() {
        document.getElementById('filterForm').reset();
        this.filterEmployees();
    }

    // Open employee modal for create
    async openEmployeeModal() {
        try {
            this.showLoading();
            
            const response = await fetch('{{ route("nhan-vien.create") }}');
            const html = await response.text();
            
            document.getElementById('employeeModalContent').innerHTML = html;
            document.getElementById('employeeModalLabel').textContent = 'Thêm nhân viên mới';
            
            const modal = new bootstrap.Modal(document.getElementById('employeeModal'));
            modal.show();
            
            this.hideLoading();
            this.setupFormValidation(); // Re-setup validation for new form
            
        } catch (error) {
            console.error('Error opening employee modal:', error);
            this.hideLoading();
            this.showAlert('Có lỗi xảy ra khi tải form', 'error');
        }
    }

    // Edit employee
    async editEmployee(id) {
        try {
            this.showLoading();
            
            const response = await fetch(`{{ url('nhan-vien') }}/${id}/edit`);
            const html = await response.text();
            
            document.getElementById('employeeModalContent').innerHTML = html;
            document.getElementById('employeeModalLabel').textContent = 'Chỉnh sửa nhân viên';
            
            const modal = new bootstrap.Modal(document.getElementById('employeeModal'));
            modal.show();
            
            this.hideLoading();
            this.setupFormValidation(); // Re-setup validation for new form
            
        } catch (error) {
            console.error('Error editing employee:', error);
            this.hideLoading();
            this.showAlert('Có lỗi xảy ra khi tải form', 'error');
        }
    }

    // View employee details
    async viewEmployee(id) {
        try {
            this.showLoading();
            
            const response = await fetch(`{{ url('nhan-vien') }}/${id}`);
            const html = await response.text();
            
            document.getElementById('employeeDetailContent').innerHTML = html;
            
            const modal = new bootstrap.Modal(document.getElementById('employeeDetailModal'));
            modal.show();
            
            this.hideLoading();
            
        } catch (error) {
            console.error('Error viewing employee:', error);
            this.hideLoading();
            this.showAlert('Có lỗi xảy ra khi tải thông tin', 'error');
        }
    }

    // Delete employee
    async deleteEmployee(id) {
        if (confirm('Bạn có chắc chắn muốn xóa nhân viên này?')) {
            try {
                this.showLoading();
                
                const response = await fetch(`{{ url('nhan-vien') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showAlert('Xóa nhân viên thành công!', 'success');
                    this.filterEmployees(); // Refresh the list
                } else {
                    this.showAlert(data.message || 'Có lỗi xảy ra khi xóa nhân viên', 'error');
                }
                
                this.hideLoading();
                
            } catch (error) {
                console.error('Error deleting employee:', error);
                this.hideLoading();
                this.showAlert('Có lỗi xảy ra khi xóa nhân viên', 'error');
            }
        }
    }

    // Save employee (create or update)
    async saveEmployee() {
        const form = document.getElementById('employeeForm');
        const formData = new FormData(form);
        
        // Validate form
        if (!this.validateForm()) {
            return;
        }
        
        try {
            this.showLoading();
            
            const isEdit = form.querySelector('input[name="_method"]');
            const url = isEdit 
                ? `{{ url('nhan-vien') }}/${isEdit.value}`
                : '{{ route("nhan-vien.store") }}';
            
            const method = isEdit ? 'PUT' : 'POST';
            
            const response = await fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showAlert(data.message, 'success');
                bootstrap.Modal.getInstance(document.getElementById('employeeModal')).hide();
                this.filterEmployees(); // Refresh the list
            } else {
                this.showAlert(data.message || 'Có lỗi xảy ra', 'error');
            }
            
            this.hideLoading();
            
        } catch (error) {
            console.error('Error saving employee:', error);
            this.hideLoading();
            this.showAlert('Có lỗi xảy ra khi lưu dữ liệu', 'error');
        }
    }

    // Export employees
    exportEmployees(format) {
        const formData = new FormData(document.getElementById('filterForm'));
        const params = new URLSearchParams(formData);
        params.append('export', format);
        
        window.open(`{{ route('nhan-vien.index') }}?${params.toString()}`, '_blank');
    }

    // Form validation
    validateForm() {
        let isValid = true;
        
        // Clear previous validation
        document.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });
        
        // Required fields
        const requiredFields = ['ma_nhanvien', 'ho', 'ten', 'trang_thai'];
        requiredFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (field && !field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            }
        });
        
        // Email validation
        const email = document.getElementById('email');
        if (email && email.value && !this.isValidEmail(email.value)) {
            email.classList.add('is-invalid');
            isValid = false;
        }
        
        // Phone validation
        const phone = document.getElementById('so_dien_thoai');
        if (phone && phone.value && !this.isValidPhone(phone.value)) {
            phone.classList.add('is-invalid');
            isValid = false;
        }
        
        return isValid;
    }

    // Field validation
    validateField(field) {
        if (field.hasAttribute('required') && !field.value.trim()) {
            field.classList.add('is-invalid');
            return false;
        }
        
        if (field.type === 'email' && field.value && !this.isValidEmail(field.value)) {
            field.classList.add('is-invalid');
            return false;
        }
        
        if (field.type === 'tel' && field.value && !this.isValidPhone(field.value)) {
            field.classList.add('is-invalid');
            return false;
        }
        
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
        return true;
    }

    // Email validation
    validateEmail(field) {
        if (field.value && !this.isValidEmail(field.value)) {
            field.classList.add('is-invalid');
            return false;
        }
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
        return true;
    }

    // Phone validation
    validatePhone(field) {
        if (field.value && !this.isValidPhone(field.value)) {
            field.classList.add('is-invalid');
            return false;
        }
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
        return true;
    }

    // Email regex validation
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Phone regex validation
    isValidPhone(phone) {
        const phoneRegex = /^[0-9+\-\s()]+$/;
        return phoneRegex.test(phone) && phone.length >= 10;
    }

    // Handle image preview
    handleImagePreview(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = document.getElementById('previewImg');
                const imagePreview = document.getElementById('imagePreview');
                const noImagePreview = document.getElementById('noImagePreview');
                
                if (previewImg) {
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';
                }
                
                if (imagePreview) {
                    imagePreview.style.display = 'block';
                }
                
                if (noImagePreview) {
                    noImagePreview.style.display = 'none';
                }
            };
            reader.readAsDataURL(file);
        }
    }

    // Show loading state
    showLoading() {
        const loadingElement = document.querySelector('.loading-overlay');
        if (loadingElement) {
            loadingElement.style.display = 'flex';
        }
    }

    // Hide loading state
    hideLoading() {
        const loadingElement = document.querySelector('.loading-overlay');
        if (loadingElement) {
            loadingElement.style.display = 'none';
        }
    }

    // Show alert message
    showAlert(message, type = 'info') {
        // Remove existing alerts
        const existingAlerts = document.querySelectorAll('.alert');
        existingAlerts.forEach(alert => alert.remove());
        
        // Create new alert
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            <i class="fas fa-${this.getAlertIcon(type)} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Insert at top of content
        const content = document.querySelector('.container-fluid');
        if (content) {
            content.insertBefore(alertDiv, content.firstChild);
        }
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    // Get alert icon based on type
    getAlertIcon(type) {
        const icons = {
            'success': 'check-circle',
            'danger': 'exclamation-triangle',
            'warning': 'exclamation-circle',
            'info': 'info-circle'
        };
        return icons[type] || 'info-circle';
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.hrmEmployee = new HrmEmployee();
});

// Global functions for backward compatibility
function filterEmployees() {
    if (window.hrmEmployee) {
        window.hrmEmployee.filterEmployees();
    }
}

function resetFilter() {
    if (window.hrmEmployee) {
        window.hrmEmployee.resetFilter();
    }
}

function openEmployeeModal() {
    if (window.hrmEmployee) {
        window.hrmEmployee.openEmployeeModal();
    }
}

function editEmployee(id) {
    if (window.hrmEmployee) {
        window.hrmEmployee.editEmployee(id);
    }
}

function viewEmployee(id) {
    if (window.hrmEmployee) {
        window.hrmEmployee.viewEmployee(id);
    }
}

function deleteEmployee(id) {
    if (window.hrmEmployee) {
        window.hrmEmployee.deleteEmployee(id);
    }
}

function exportEmployees(format) {
    if (window.hrmEmployee) {
        window.hrmEmployee.exportEmployees(format);
    }
}

function saveEmployee(formData) {
    if (window.hrmEmployee) {
        window.hrmEmployee.saveEmployee();
    }
}
