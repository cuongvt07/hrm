@extends('layouts.app')
@section('title', 'Hợp đồng sắp hết hạn')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Danh sách hợp đồng sắp hết hạn</h5>
    </div>
    {{-- Bộ lọc --}}
    <x-filters.contract-filter :nhanViens="$nhanViens" />
    <div class="card-body p-0">
        @include('hop-dong.partials.table', ['specialStatus' => 'sap_het_han'])
    </div>
    <div class="card-footer">
        {{ $hopDongs->links() }}
    </div>
</div>
<script>
    // Enable/disable Gia hạn button
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý nút tái kí từng dòng
        document.querySelectorAll('.btn-giahan').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id = btn.getAttribute('data-id');
                window.location.href = '/hop-dong/' + id + '/gia-han';
            });
        });
        // Xử lý nút Gia hạn nhiều dòng (nếu cần giữ)
        const checkboxes = document.querySelectorAll('.hopdong-saphethan');
        const btnGiaHan = document.getElementById('btnGiaHan');
        if (btnGiaHan) {
            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    btnGiaHan.disabled = !Array.from(checkboxes).some(c => c.checked);
                });
            });
            document.getElementById('checkAllSapHetHan').addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
                btnGiaHan.disabled = !this.checked;
            });
            btnGiaHan.addEventListener('click', function() {
                const checked = Array.from(checkboxes).filter(c => c.checked);
                if (checked.length > 0) {
                    const id = checked[0].value;
                    window.location.href = '/hop-dong/' + id + '/gia-han';
                }
            });
        }
    });
</script>
@endsection
