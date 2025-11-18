<td>
    <div class="btn-group" role="group">
        <a href="{{ route($showRoute, $id) }}" class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
            <i class="fas fa-eye"></i>
        </a>
        <a href="{{ route($editRoute, $id) }}" class="btn btn-sm btn-outline-secondary" title="Chỉnh sửa">
            <i class="fas fa-edit"></i>
        </a>
        @if(isset($showTerminate) ? $showTerminate : true)
        <button type="button" class="btn btn-sm btn-outline-danger btn-terminate-general" data-id="{{ $id }}" data-terminate-id="{{ $terminateId ?? '' }}" title="Chấm dứt">
            <i class="fas fa-times"></i>
        </button>
        @endif
        <!-- <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="{{ $id }}" data-name="{{ $name ?? '' }}" title="Xóa">
            <i class="fas fa-trash"></i>
        </button> -->
    </div>
</td>
@once
@push('scripts')
<script>
    if (!window.terminateHandlerAdded) {
            document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-terminate-general');
            if (!btn) return;
            e.preventDefault();
            if (!confirm('Xác nhận chấm dứt hợp đồng?')) return;
                const id = btn.getAttribute('data-id');
                const terminateId = btn.getAttribute('data-terminate-id');
                const url = (terminateId && terminateId.length > 0) ? ('/hop-dong/' + terminateId + '/terminate') : ('/hop-dong/' + id + '/terminate');
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (data && data.success) {
                    alert(data.message || 'Chấm dứt hợp đồng thành công.');
                    location.reload();
                } else {
                    alert('Lỗi: ' + (data && data.message ? data.message : 'Không thể chấm dứt hợp đồng.'));
                }
            })
            .catch(function(err) {
                console.error(err);
                alert('Lỗi khi thực hiện yêu cầu.');
            });
        });
        window.terminateHandlerAdded = true;
    }
</script>
@endpush
@endonce
