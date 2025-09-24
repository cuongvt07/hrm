@extends('layouts.app')
@section('title', 'Quản lý hợp đồng')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Danh sách hợp đồng lao động</h4>
        <a href="{{ route('hop-dong.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Thêm hợp đồng
        </a>
    </div>
    {{-- Bộ lọc --}}
    <x-filters.contract-filter :nhanViens="$nhanViens" />
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
                loadData(e.target.closest("a").href);
            }
        });
    });
</script>
@endpush
