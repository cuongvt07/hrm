<td>
    <button type="button" class="btn btn-sm btn-success btn-giahan" data-id="{{ $hopDong->id }}">
        <i class="fas fa-sync-alt"></i> Tái kí
    </button>
    <button type="button" class="btn btn-sm btn-danger btn-terminate ms-2" data-id="{{ $hopDong->id }}">
        <i class="fas fa-times"></i> Chấm dứt
    </button>
</td>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delegate termination buttons
    document.querySelectorAll('.btn-terminate').forEach(function(btn) {
        btn.addEventListener('click', function() {
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
