<!-- Contract Table Partial -->
<div class="table-responsive">

<!-- Bulk update trạng thái -->
<div class="d-flex align-items-center mb-2 gap-2 justify-content-end">
    <select id="bulkStatusSelect" class="form-select form-select-sm" style="width: 180px;">
        <option value="">Trạng thái</option>
        <option value="hieu_luc">Hiệu lực</option>
        <option value="het_hieu_luc">Hết hiệu lực</option>
    </select>
    <button id="bulkUpdateBtn" class="btn btn-sm btn-primary" disabled>Cập nhật trạng thái</button>
</div>

<table class="table table-light table-hover mb-0">
    <thead class="table-light">
        <tr>
            <th><input type="checkbox" id="checkAll" class="form-check-input" onchange="toggleSelectAllContracts()"></th>
            <th>Số HĐ</th>
            <th>Họ và tên NLĐ</th>
            <th>Vị trí công việc</th>
            <th>Phòng ban</th>
            <th>Loại HĐ</th>
            <th>Thời hạn</th>
            <th>Ngày hết hạn</th>
            <th>Trạng thái ký</th>
            <th>Trạng thái hợp đồng</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach($hopDongs as $hopDong)
            <tr>
                <td><input type="checkbox" class="contract-checkbox form-check-input" value="{{ $hopDong->id }}" onchange="updateBulkBtnState()"></td>
                <td>{{ $hopDong->so_hop_dong }}</td>
                <td>{{ $hopDong->nhanVien->ho_ten ?? '-' }}</td>
                <td>{{ $hopDong->vi_tri_cong_viec ?? ($hopDong->nhanVien->chucVu->ten_chuc_vu ?? '-') }}</td>
                <td>{{ $hopDong->don_vi_ky_hd ?? ($hopDong->nhanVien->phongBan->ten_phong_ban ?? '-') }}</td>
                <td>
                    {{ $hopDong->loai_hop_dong }}
                </td>
                @php
                    $thoiHanLabel = '-';
                    if ($hopDong->thoi_han) {
                        if (isset($hopDong->loai_hop_dong) && $hopDong->loai_hop_dong === 'Thử việc') {
                            $thoiHanLabel = $hopDong->thoi_han . ' tháng';
                        } else {
                            $thoiHanLabel = $hopDong->thoi_han . ' năm';
                        }
                    }
                @endphp
                <td>{{ $thoiHanLabel }}</td>
                <td>{{ $hopDong->ngay_ket_thuc ? $hopDong->ngay_ket_thuc->format('d/m/Y') : '-' }}</td>
                <td>
                    @if($hopDong->trang_thai_ky === 'duyet')
                        <span class="badge bg-success">Đã ký</span>
                    @elseif($hopDong->trang_thai_ky === 'tai_ki')
                        <span class="badge bg-warning text-dark">Gia hạn</span>
                    @else
                        <span class="badge bg-secondary">-</span>
                    @endif
                </td>
                <td>
                    @if($hopDong->trang_thai === 'hieu_luc')
                        <span class="badge bg-success">Hiệu lực</span>
                    @elseif($hopDong->trang_thai === 'het_hieu_luc')
                        <span class="badge bg-danger">Hết hiệu lực</span>
                    @else
                        <span class="badge bg-secondary">-</span>
                    @endif
                </td>
                @if(isset($specialStatus) && $specialStatus === 'sap_het_han')
                    @include('hop-dong.partials.table_action_giahan', ['hopDong' => $hopDong])
                @else
                    @component('components.table-action', [
                        'showRoute' => 'hop-dong.show',
                        'editRoute' => 'hop-dong.edit',
                        'deleteRoute' => 'hop-dong.destroy',
                        'id' => $hopDong->id
                    ])@endcomponent
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
<div class="mt-3">
    @if($hopDongs->hasPages())
        <x-pagination :paginator="$hopDongs" />
    @endif
</div>
</div>

<script>
function toggleSelectAllContracts() {
    const selectAll = document.getElementById("checkAll");
    const checkboxes = document.querySelectorAll(".contract-checkbox");
    checkboxes.forEach(cb => cb.checked = selectAll.checked);
    updateBulkBtnState();
}

function updateBulkBtnState() {
    const checked = document.querySelectorAll(".contract-checkbox:checked").length;
    document.getElementById("bulkUpdateBtn").disabled = checked === 0;
    // Update selectAll state
    const checkboxes = document.querySelectorAll(".contract-checkbox");
    document.getElementById("checkAll").checked = [...checkboxes].every(cb => cb.checked);
}

document.getElementById("bulkUpdateBtn").onclick = function() {
    const ids = [...document.querySelectorAll(".contract-checkbox:checked")].map(cb => cb.value);
    const status = document.getElementById("bulkStatusSelect").value;
    if (ids.length === 0) {
        alert("Vui lòng chọn ít nhất 1 hợp đồng!");
        return;
    }
    if (!status) {
        alert("Vui lòng chọn trạng thái muốn cập nhật!");
        return;
    }
    if (!confirm("Bạn có chắc muốn cập nhật trạng thái cho các hợp đồng đã chọn?")) return;
    fetch("{{ route('hop-dong.bulk-update-status') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ ids, status })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert("Lỗi: " + data.message);
        }
    })
    .catch(err => console.error(err));
};
</script>
