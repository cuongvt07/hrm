<!-- Contract Table Partial (Sắp hết hạn) -->
<div class="table-responsive">
    <table class="table table-light table-hover mb-0">
        <thead class="table-light">
            <tr>
                <th><input type="checkbox" id="checkAllSapHetHan"></th>
                <th>Số HĐ</th>
                <th>Họ và tên NLĐ</th>
                <th>Vị trí công việc</th>
                <th>Phòng ban</th>
                <th>Loại HĐ</th>
                <th>Thời hạn</th>
                <th>Ngày có hiệu lực</th>
                <th>Ngày hết hạn</th>
                <th>Trạng thái ký</th>
                <th>Trạng thái hợp đồng</th>
                <th>Ngày ký HĐ</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hopDongs as $hopDong)
                <tr>
                    <td><input type="checkbox" class="hopdong-saphethan" name="hopdong_ids[]" value="{{ $hopDong->id }}"></td>
                    <td>{{ $hopDong->so_hop_dong }}</td>
                    <td>{{ $hopDong->nhanVien->ho_ten ?? '-' }}</td>
                    <td>{{ $hopDong->vi_tri_cong_viec ?? ($hopDong->nhanVien->chucVu->ten_chuc_vu ?? '-') }}</td>
                    <td>{{ $hopDong->don_vi_ky_hd ?? ($hopDong->nhanVien->phongBan->ten_phong_ban ?? '-') }}</td>
                    <td>{{ $hopDong->loai_hop_dong ?? '-' }}</td>
                    <td>{{ $hopDong->thoi_han ? $hopDong->thoi_han . ' tháng' : '-' }}</td>
                    <td>{{ $hopDong->ngay_bat_dau ? $hopDong->ngay_bat_dau->format('d/m/Y') : '-' }}</td>
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
                    <td>{{ $hopDong->ngay_ky ? $hopDong->ngay_ky->format('d/m/Y') : '-' }}</td>
                    @component('components.table-action', [
                        'showRoute' => 'hop-dong.show',
                        'editRoute' => 'hop-dong.edit',
                        'deleteRoute' => 'hop-dong.destroy',
                        'id' => $hopDong->id
                    ])@endcomponent
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
