<table class="table table-bordered table-hover mb-0">
    <thead>
        <tr>
            <th>STT</th>
            <th>Số quyết định</th>
            <th>Tiêu đề</th>
            <th>Ngày quyết định</th>
            <th>Người quyết định</th>
            <th>Trạng thái</th>
            <th>Đối tượng áp dụng</th>
            <th>Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @foreach($khenThuongKyLuats as $item)
            <tr>
                <td>{{ $loop->iteration + (($khenThuongKyLuats->currentPage() - 1) * $khenThuongKyLuats->perPage()) }}</td>
                <td>{{ $item->so_quyet_dinh }}</td>
                <td>{{ $item->tieu_de }}</td>
                <td>{{ $item->ngay_quyet_dinh ? $item->ngay_quyet_dinh->format('d/m/Y') : '' }}</td>
                <td>{{ $item->nguoi_quyet_dinh }}</td>
                <td>
                    @if($item->trang_thai === 'chua_thuc_hien')
                        <span class="badge bg-secondary">Chưa thực hiện</span>
                    @elseif($item->trang_thai === 'dang_thuc_hien')
                        <span class="badge bg-warning text-dark">Đang thực hiện</span>
                    @elseif($item->trang_thai === 'hoan_thanh')
                        <span class="badge bg-success">Hoàn thành</span>
                    @endif
                </td>
                <td>
                    @php
                        $nhanVienArr = $item->doiTuongApDung->where('loai_doi_tuong', 'nhan_vien')->pluck('nhanVien.ho_ten')->toArray();
                        $phongBanArr = $item->doiTuongApDung->where('loai_doi_tuong', 'phong_ban')->pluck('phongBan.ten_phong_ban')->toArray();
                    @endphp
                    @if($nhanVienArr)
                        <span class="text-primary">Nhân viên:</span> {{ implode(', ', $nhanVienArr) }}<br>
                    @endif
                    @if($phongBanArr)
                        <span class="text-success">Phòng ban:</span> {{ implode(', ', $phongBanArr) }}
                    @endif
                </td>
                <td>
                    <a href="{{ route('che-do.khen-thuong-ky-luat.show', $item->id) }}" class="btn btn-sm btn-info">Xem</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="mt-3 px-3 pb-3">
    @if($khenThuongKyLuats->hasPages())
        <x-pagination :paginator="$khenThuongKyLuats" />
    @endif
</div>