<!DOCTYPE html>
<html>
<head>
    <title>Báo cáo khen thưởng {{ request('active_tab') == 'phong-ban' ? 'phòng ban' : 'cá nhân' }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
    <table>
        <thead>
            @if(request('active_tab') == 'phong-ban')
            <!-- Header cho khen thưởng phòng ban -->
            <tr>
                <th colspan="8" style="text-align: center; font-size: 16px; font-weight: bold;">
                    BÁO CÁO KHEN THƯỞNG PHÒNG BAN
                </th>
            </tr>
            @else
            <!-- Header cho khen thưởng cá nhân -->
            <tr>
                <th colspan="12" style="text-align: center; font-size: 16px; font-weight: bold;">
                    BÁO CÁO KHEN THƯỞNG CÁ NHÂN
                </th>
            </tr>
            @endif

            <!-- Thông tin thời gian báo cáo -->
            <tr>
                <th colspan="{{ request('active_tab') == 'phong-ban' ? '8' : '12' }}" style="text-align: center;">
                    @if(request('tu_ngay') && request('den_ngay'))
                        Từ ngày {{ \Carbon\Carbon::parse(request('tu_ngay'))->format('d/m/Y') }} 
                        đến ngày {{ \Carbon\Carbon::parse(request('den_ngay'))->format('d/m/Y') }}
                    @else
                        Tất cả thời gian
                    @endif
                </th>
            </tr>

            <!-- Header trống để tạo khoảng cách -->
            <tr>
                <th colspan="{{ request('active_tab') == 'phong-ban' ? '8' : '12' }}"></th>
            </tr>

            <!-- Headers cho bảng dữ liệu -->
            <tr>
                @if(request('active_tab') == 'phong-ban')
                <!-- Headers cho khen thưởng phòng ban -->
                <th style="font-weight: bold; background-color: #f4f4f4;">STT</th>
                <th style="font-weight: bold; background-color: #f4f4f4;">Đơn vị</th>
                <th style="font-weight: bold; background-color: #f4f4f4;">Ngày khen thưởng</th>
                <th style="font-weight: bold; background-color: #f4f4f4;">Ngày quyết định</th>
                <th style="font-weight: bold; background-color: #f4f4f4;">Đợt khen thưởng</th>
                <th style="font-weight: bold; background-color: #f4f4f4;">Hình thức khen thưởng</th>
                <th style="font-weight: bold; background-color: #f4f4f4;">Giá trị khen thưởng</th>
                <th style="font-weight: bold; background-color: #f4f4f4;">Mô tả</th>
                @else
                <!-- Headers cho khen thưởng cá nhân -->
                <th style="font-weight: bold; background-color: #f4f4f4;">STT</th>
                <th style="font-weight: bold; background-color: #f4f4f4;">Mã nhân viên</th>
                <th style="font-weight: bold; background-color: #f4f4f4;">Họ và tên</th>
                <th style="font-weight: bold; background-color: #f4f4f4;">Đơn vị công tác</th>
                <th style="font-weight: bold; background-color: #f4f4f4;">Vị trí công việc</th>
                <th style="font-weight: bold; background-color: #f4f4f4;">Ngày vào làm</th>
                <th style="font-weight: bold; background-color: #f4f4f4;">Ngày khen thưởng</th>
                <th style="font-weight: bold; background-color: #f4f4f4;">Ngày quyết định</th>
                <th style="font-weight: bold; background-color: #f4f4f4;">Đợt khen thưởng</th>
                <th style="font-weight: bold; background-color: #f4f4f4;">Hình thức khen thưởng</th>
                <th style="font-weight: bold; background-color: #f4f4f4;">Giá trị khen thưởng</th>
                <th style="font-weight: bold; background-color: #f4f4f4;">Mô tả</th>
                @endif
            </tr>
        </thead>

        <tbody>
            @php $stt = 1; @endphp
            
            @if(request('active_tab') == 'phong-ban')
                @foreach($khenThuongPhongBan as $khenThuong)
                    @foreach($khenThuong->doiTuongApDung as $doiTuong)
                    <tr>
                        <td>{{ $stt++ }}</td>
                        <td>{{ $doiTuong->phongBan->ten_phong_ban }}</td>
                        <td>{{ Carbon\Carbon::parse($khenThuong->ngay_quyet_dinh)->format('d/m/Y') }}</td>
                        <td>{{ Carbon\Carbon::parse($khenThuong->ngay_quyet_dinh)->format('d/m/Y') }}</td>
                        <td>{{ $khenThuong->tieu_de }}</td>
                        <td>{{ $khenThuong->gia_tri ? 'Thưởng tiền' : 'Khác' }}</td>
                        <td>{{ $khenThuong->gia_tri !== null ? number_format($khenThuong->gia_tri, 0, ',', '.') . ' VNĐ' : '-' }}</td>
                        <td>{{ $khenThuong->mo_ta }}</td>
                    </tr>
                    @endforeach
                @endforeach
            @else
                @foreach($khenThuongCaNhan as $khenThuong)
                    @foreach($khenThuong->doiTuongApDung as $doiTuong)
                    <tr>
                        <td>{{ $stt++ }}</td>
                        <td>{{ $doiTuong->nhanVien->ma_nhanvien }}</td>
                        <td>{{ $doiTuong->nhanVien->ho_ten }}</td>
                        <td>{{ $doiTuong->nhanVien->phongBan->ten_phong_ban }}</td>
                        <td>{{ $doiTuong->nhanVien->chucVu->ten_chuc_vu }}</td>
                        <td>{{ $doiTuong->nhanVien->ngay_vao_lam->format('d/m/Y') }}</td>
                        <td>{{ Carbon\Carbon::parse($khenThuong->ngay_quyet_dinh)->format('d/m/Y') }}</td>
                        <td>{{ Carbon\Carbon::parse($khenThuong->ngay_quyet_dinh)->format('d/m/Y') }}</td>
                        <td>{{ $khenThuong->tieu_de }}</td>
                        <td>{{ $khenThuong->gia_tri ? 'Thưởng tiền' : 'Khác' }}</td>
                        <td>{{ $khenThuong->gia_tri !== null ? number_format($khenThuong->gia_tri, 0, ',', '.') . ' VNĐ' : '-' }}</td>
                        <td>{{ $khenThuong->mo_ta }}</td>
                    </tr>
                    @endforeach
                @endforeach
            @endif

            <!-- Footer thống kê -->
            <tr>
                <td colspan="{{ request('active_tab') == 'phong-ban' ? '8' : '12' }}"></td>
            </tr>
            <tr>
                <td colspan="2" style="font-weight: bold;">Tổng số:</td>
                <td colspan="{{ request('active_tab') == 'phong-ban' ? '6' : '10' }}">
                    {{ $stt - 1 }} {{ request('active_tab') == 'phong-ban' ? 'phòng ban' : 'nhân viên' }}
                </td>
            </tr>
            <tr>
                <td colspan="2" style="font-weight: bold;">Ngày xuất báo cáo:</td>
                <td colspan="{{ request('active_tab') == 'phong-ban' ? '6' : '10' }}">
                    {{ Carbon\Carbon::now()->format('d/m/Y H:i') }}
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>