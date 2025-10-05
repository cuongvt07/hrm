<table>
    <tr>
        <td colspan="{{ count($loaiHopDong) + 2 }}" style="font-size: 16px; font-weight: bold; text-align: center; background-color: #4e73df; color: white;">
            BÁO CÁO PHÂN LOẠI HỢP ĐỒNG NHÂN VIÊN
        </td>
    </tr>
    @if($fromDate && $toDate)
    <tr>
        <td colspan="{{ count($loaiHopDong) + 2 }}" style="text-align: center; font-style: italic;">
            Từ ngày {{ $fromDate->format('d/m/Y') }} đến ngày {{ $toDate->format('d/m/Y') }}
        </td>
    </tr>
    @endif
    <tr>
        <td colspan="{{ count($loaiHopDong) + 2 }}"></td>
    </tr>
    <tr>
        <th style="font-weight: bold; background-color: #f8f9fc; border: 1px solid #e3e6f0;">Phòng ban</th>
        @foreach($loaiHopDong as $loai)
            <th style="font-weight: bold; background-color: #f8f9fc; border: 1px solid #e3e6f0;">{{ $loai }}</th>
        @endforeach
        <th style="font-weight: bold; background-color: #f8f9fc; border: 1px solid #e3e6f0;">Tổng cộng</th>
    </tr>
    @foreach($tableData as $row)
    <tr>
        <td style="border: 1px solid #e3e6f0;">{{ $row['phong_ban'] }}</td>
        @foreach($loaiHopDong as $loai)
            <td style="border: 1px solid #e3e6f0; text-align: center;">{{ number_format($row[$loai]) }}</td>
        @endforeach
        <td style="border: 1px solid #e3e6f0; text-align: center; font-weight: bold;">{{ number_format($row['tong_cong']) }}</td>
    </tr>
    @endforeach
    <tr style="font-weight:bold; background:#f8f9fc;">
        <td style="border: 1px solid #e3e6f0;">{{ $totalRow['phong_ban'] }}</td>
        @foreach($loaiHopDong as $loai)
            <td style="border: 1px solid #e3e6f0; text-align: center;">{{ number_format($totalRow[$loai]) }}</td>
        @endforeach
        <td style="border: 1px solid #e3e6f0; text-align: center; font-weight: bold;">{{ number_format($totalRow['tong_cong']) }}</td>
    </tr>
    <tr>
        <td colspan="{{ count($loaiHopDong) + 2 }}" style="font-style: italic; text-align: right;">
            Ngày xuất báo cáo: {{ now()->format('d/m/Y H:i') }}
        </td>
    </tr>
</table>
