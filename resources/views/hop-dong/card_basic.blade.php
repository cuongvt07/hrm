@if($hopDong)
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-primary text-white">
        <h6 class="mb-0"><i class="fas fa-file-contract me-1"></i>Hợp đồng lao động hiện tại</h6>
    </div>
    <div class="card-body">
        <div class="row g-3 align-items-center">
            <div class="col-md-6">
                <div><strong>Số hợp đồng:</strong> {{ $hopDong->so_hop_dong }}</div>
                <div><strong>Loại hợp đồng:</strong> {{ $hopDong->loai_hop_dong }}</div>
                <div><strong>Ngày bắt đầu:</strong> {{ $hopDong->ngay_bat_dau ? $hopDong->ngay_bat_dau->format('d/m/Y') : '-' }}</div>
                <div><strong>Ngày kết thúc:</strong> {{ $hopDong->ngay_ket_thuc ? $hopDong->ngay_ket_thuc->format('d/m/Y') : '-' }}</div>
            </div>
            <div class="col-md-6">
                <div><strong>Trạng thái:</strong> 
                    @if($hopDong->trang_thai === 'hoat_dong')
                        <span class="badge bg-success">Hoạt động</span>
                    @elseif($hopDong->trang_thai === 'het_han')
                        <span class="badge bg-secondary">Hết hạn</span>
                    @elseif($hopDong->trang_thai === 'cham_dut')
                        <span class="badge bg-danger">Chấm dứt</span>
                    @else
                        <span class="badge bg-secondary">-</span>
                    @endif
                </div>
                <div><strong>Trạng thái ký:</strong> 
                    @if($hopDong->trang_thai_ky === 'duyet')
                        <span class="badge bg-success">Duyệt</span>
                    @elseif($hopDong->trang_thai_ky === 'tai_ki')
                        <span class="badge bg-warning text-dark">Tái kí</span>
                    @else
                        <span class="badge bg-secondary">-</span>
                    @endif
                </div>
                <a href="{{ route('hop-dong.show', $hopDong->id) }}" class="btn btn-outline-primary mt-3">
                    <i class="fas fa-eye me-1"></i>Xem chi tiết hợp đồng
                </a>
            </div>
        </div>
    </div>
</div>
@endif
