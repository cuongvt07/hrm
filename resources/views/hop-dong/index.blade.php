@extends('layouts.app')
@section('title', 'Quản lý hợp đồng')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Danh sách hợp đồng lao động</h4>
        <div>
            <a href="{{ route('hop-dong.create') }}" class="btn btn-primary me-2">
                <i class="fas fa-plus me-1"></i> Thêm hợp đồng
            </a>
            <button id="exportContractsBtn" class="btn btn-outline-success">
                <i class="fas fa-file-export me-1"></i> Xuất Excel
            </button>
        </div>
    </div>
    {{-- Bộ lọc --}}
    <x-filters.contract-filter :nhanViens="$nhanViens" :phongBans="$phongBans" />
    <div class="card">
        <div class="card-body p-0" id="tableContainer">
            @include('hop-dong.partials.table')
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    // Ajax filter + phân trang
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById("contractFilterForm");
            const exportBtn = document.getElementById('exportContractsBtn');
            if (exportBtn) {
                exportBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (!form) {
                        // fallback to direct export link
                        window.location = "{{ route('hop-dong.export') }}";
                        return;
                    }
                    const params = new URLSearchParams(new FormData(form)).toString();
                    const url = "{{ route('hop-dong.export') }}" + (params ? ('?' + params) : '');
                    // navigate to url to trigger download
                    window.location = url;
                });
            }
        function loadData(url) {
            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById("tableContainer").innerHTML = data.table;
            });
        }
        form.addEventListener("submit", function(e) {
            e.preventDefault();
            const url = "{{ route('hop-dong.index') }}?" + new URLSearchParams(new FormData(form)).toString();
            loadData(url);
        });
        document.body.addEventListener("click", function(e) {
            if (e.target.closest(".pagination a")) {
                e.preventDefault();
                const paginationLink = e.target.closest("a");
                const baseUrl = paginationLink.href.split('?')[0]; // Lấy URL không có query string
                const currentFormData = new FormData(form);
                const searchParams = new URLSearchParams();

                // Thêm tất cả parameters từ form hiện tại
                for (let [key, value] of currentFormData.entries()) {
                    if (value) { // Chỉ thêm nếu có giá trị
                        searchParams.append(key, value);
                    }
                }

                // Thêm page parameter từ pagination link
                const urlParams = new URLSearchParams(paginationLink.href.split('?')[1] || '');
                const page = urlParams.get('page');
                if (page) {
                    searchParams.set('page', page);
                }

                const finalUrl = baseUrl + '?' + searchParams.toString();
                loadData(finalUrl);
            }
        });

        // Nếu có alert thành công (sau khi thêm/sửa/xóa/gia hạn), tự động reload table qua Ajax
        const alertSuccess = document.querySelector('.alert-success');
        if (alertSuccess) {
            // Đợi DOM render xong, trigger submit filter để reload table
            setTimeout(function() {
                if (form) form.dispatchEvent(new Event('submit', {cancelable: true, bubbles: true}));
            }, 500); // delay nhẹ để alert hiển thị trước
        }

        // Event delegation cho nút Chấm dứt hợp đồng
        document.body.addEventListener('click', function(e) {
            const terminateBtn = e.target.closest('.btn-terminate-general');
            if (terminateBtn) {
                e.preventDefault();
                if (!confirm('Xác nhận chấm dứt hợp đồng?')) return;
                const id = terminateBtn.dataset.id;
                fetch("{{ url('hop-dong') }}" + '/' + id + '/terminate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({})
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message || 'Chấm dứt hợp đồng thành công.');
                        location.reload();
                    } else {
                        alert('Lỗi: ' + (data.message || 'Không thể chấm dứt hợp đồng.'));
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Lỗi khi thực hiện yêu cầu.');
                });
            }
        });
    });
</script>
@endpush
