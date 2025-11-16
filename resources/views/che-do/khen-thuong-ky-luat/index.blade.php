@extends('layouts.app')
@section('title', $loai === 'ky_luat' ? 'Danh sách quyết định kỷ luật' : 'Danh sách quyết định khen thưởng')
@section('content')

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">
            {{ $loai === 'ky_luat' ? 'Danh sách quyết định kỷ luật' : 'Danh sách quyết định khen thưởng' }}
        </h4>
    <a href="{{ route('che-do.khen-thuong-ky-luat.create', ['loai' => $loai]) }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tạo quyết định {{ $loai === 'ky_luat' ? 'kỷ luật' : 'khen thưởng' }}
        </a>
    </div>
    <form method="get" id="filterForm" class="row g-2 mb-3 align-items-end">
        <div class="col-auto">
            <label class="form-label mb-0">Từ ngày</label>
            <input type="date" name="from" value="{{ request('from') }}" class="form-control form-control-sm">
        </div>
        <div class="col-auto">
            <label class="form-label mb-0">Đến ngày</label>
            <input type="date" name="to" value="{{ request('to') }}" class="form-control form-control-sm">
        </div>
        <div class="col-auto">
            <label class="form-label mb-0">Tìm kiếm</label>
            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Tiêu đề, số quyết định...">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Tìm kiếm
            </button>
        </div>
    </form>
    {{-- Bộ lọc (nếu có) --}}
    <div class="card">
        <div class="card-body p-0" id="tableContainer">
            @include('che-do.khen-thuong-ky-luat.partials.table')
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Ajax filter + phân trang
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.querySelector("form[method='get']");
        function loadData(url) {
            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById("tableContainer").innerHTML = data.table;
            })
            .catch(err => {
                console.error('Error loading data:', err);
                // Fallback: reload page
                window.location.href = url;
            });
        }

        // Xử lý form submit
        form.addEventListener("submit", function(e) {
            e.preventDefault();
            const url = "{{ $loai === 'ky_luat' ? route('che-do.ky-luat.index') : route('che-do.khen-thuong.index') }}?" + new URLSearchParams(new FormData(form)).toString();
            loadData(url);
        });

        // Xử lý phân trang với filter parameters
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

        // Nếu có alert thành công, tự động reload table
        const alertSuccess = document.querySelector('.alert-success');
        if (alertSuccess) {
            setTimeout(function() {
                if (form) form.dispatchEvent(new Event('submit', {cancelable: true, bubbles: true}));
            }, 500);
        }
    });
</script>
@endpush
