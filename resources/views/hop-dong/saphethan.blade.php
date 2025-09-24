@extends('layouts.app')
@section('title', 'Hợp đồng sắp hết hạn')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Danh sách hợp đồng sắp hết hạn</h5>
        <button id="btnGiaHan" class="btn btn-success btn-sm" disabled>
            <i class="fas fa-sync-alt"></i> Gia hạn hợp đồng
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-light table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th><input type="checkbox" id="checkAllSapHetHan"></th>
                        <th>Ngày ký HĐ</th>
                        <th>Số HĐ</th>
                        <th>Họ và tên NLĐ</th>
                        <th>Vị trí công việc</th>
                        <th>Đơn vị ký HĐ</th>
                        <th>Loại HĐ</th>
                        <th>Thời hạn</th>
                        <th>Ngày có hiệu lực</th>
                        <th>Ngày hết hạn</th>
                        <th>Trạng thái ký</th>
                        <th>Trạng thái hợp đồng</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($hopDongs as $hopDong)
                        <tr>
                            <td><input type="checkbox" class="hopdong-saphethan" name="hopdong_ids[]" value="{{ $hopDong->id }}"></td>
                            <td>{{ $hopDong->ngay_ky ? $hopDong->ngay_ky->format('d/m/Y') : '-' }}</td>
                            <td>{{ $hopDong->so_hop_dong }}</td>
                            <td>{{ $hopDong->nhanVien->ho_ten ?? '-' }}</td>
                            <td>{{ $hopDong->vi_tri_cong_viec ?? ($hopDong->nhanVien->chucVu->ten_chuc_vu ?? '-') }}</td>
                            <td>{{ $hopDong->don_vi_ky_hd ?? ($hopDong->nhanVien->phongBan->ten_phong_ban ?? '-') }}</td>
                            <td>{{ $hopDong->loai_hop_dong ?? '-' }}</td>
                            <td>{{ $hopDong->thoi_han ? $hopDong->thoi_han . ' tháng' : '-' }}</td>
                            <td>{{ $hopDong->ngay_bat_dau ? $hopDong->ngay_bat_dau->format('d/m/Y') : '-' }}</td>
                            <td>{{ $hopDong->ngay_ket_thuc ? $hopDong->ngay_ket_thuc->format('d/m/Y') : '-' }}</td>
                            <td>{{ $hopDong->trang_thai_ky ?? '-' }}</td>
                            <td><span class="badge bg-warning">Sắp hết hạn</span></td>
                            <td>
                                <a href="{{ route('hop-dong.show', $hopDong->id) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $hopDongs->links() }}
    </div>
</div>
<script>
    // Enable/disable Gia hạn button
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.hopdong-saphethan');
        const btnGiaHan = document.getElementById('btnGiaHan');
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
    });
</script>
@endsection
