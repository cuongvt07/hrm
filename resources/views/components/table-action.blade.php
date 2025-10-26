<td>
    <div class="btn-group" role="group">
        <a href="{{ route($showRoute, $id) }}" class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
            <i class="fas fa-eye"></i>
        </a>
        <a href="{{ route($editRoute, $id) }}" class="btn btn-sm btn-outline-secondary" title="Chỉnh sửa">
            <i class="fas fa-edit"></i>
        </a>
        <button type="button" class="btn btn-sm btn-outline-danger btn-terminate-general" data-id="{{ $id }}" title="Chấm dứt">
            <i class="fas fa-times"></i>
        </button>
        <!-- <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="{{ $id }}" data-name="{{ $name ?? '' }}" title="Xóa">
            <i class="fas fa-trash"></i>
        </button> -->
    </div>
</td>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-terminate-general').forEach(function(btn){
        btn.addEventListener('click', function(){
            if (!confirm('Xác nhận chấm dứt hợp đồng?')) return;
            const id = this.dataset.id;
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
        });
    });
});
</script>
@endpush
