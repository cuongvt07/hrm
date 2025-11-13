@extends('layouts.app')

@section('title', 'Danh sách nhân viên')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Danh sách nhân viên</h4>
        <a href="{{ route('nhan-vien.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Thêm nhân viên
        </a>
    </div>

    {{-- Bộ lọc --}}
    <x-filters.employee-filter
        :search="request('search')"
        :phong_ban_id="request('phong_ban_id')"
        :chuc_vu_id="request('chuc_vu_id')"
        :trang_thai="request('trang_thai')"
        :phong-bans="$phongBans"
        :chuc-vus="$chucVus"
        :show-bulk-delete="true"
        :show-export="true"
    />

    <div class="card">
        <div class="card-body p-0" id="tableContainer">
            @include('nhan-vien.partials.table')
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Ajax filter + phân trang
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById("filterForm");

        function loadData(url) {
            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById("tableContainer").innerHTML = data.table;
            });
        }

        // Hàm lấy tham số filter hiện tại từ form
        function getCurrentFilterParams() {
            const formData = new FormData(form);
            const params = new URLSearchParams();
            for (let [key, value] of formData.entries()) {
                if (value && value.trim() !== '') {
                    params.append(key, value);
                }
            }
            return params.toString();
        }

        // Khi submit form
        form.addEventListener("submit", function(e) {
            e.preventDefault();
            const url = "{{ route('nhan-vien.index') }}?" + getCurrentFilterParams();
            loadData(url);
        });

        // Delegate click phân trang - PRESERVE FILTER PARAMS
        document.body.addEventListener("click", function(e) {
            if (e.target.closest(".pagination a")) {
                e.preventDefault();
                const paginationUrl = e.target.closest("a").href;
                const filterParams = getCurrentFilterParams();

                // Append filter params to pagination URL
                const separator = paginationUrl.includes('?') ? '&' : '?';
                const finalUrl = paginationUrl + separator + filterParams;

                console.log('Pagination URL:', paginationUrl);
                console.log('Filter params:', filterParams);
                console.log('Final URL:', finalUrl);

                loadData(finalUrl);
            }
        });
    });
</script>
@endpush
