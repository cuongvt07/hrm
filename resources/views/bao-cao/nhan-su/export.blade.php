<table>
    <tr>
        <td colspan="7" style="font-size: 16px; font-weight: bold; text-align: center; background-color: #4e73df; color: white;">
            BÁO CÁO THỐNG KÊ NHÂN SỰ
        </td>
    </tr>
    @if($fromDate && $toDate)
    <tr>
        <td colspan="7" style="text-align: center; font-style: italic;">
            Từ ngày {{ $fromDate->format('d/m/Y') }} đến ngày {{ $toDate->format('d/m/Y') }}
        </td>
    </tr>
    @endif
    <tr>
        <td colspan="7"></td>
    </tr>
    <tr>
        <th style="font-weight: bold; background-color: #f8f9fc; border: 1px solid #e3e6f0;">Đơn vị công tác</th>
        <th style="font-weight: bold; background-color: #f8f9fc; border: 1px solid #e3e6f0;">Không có hợp đồng</th>
        <th style="font-weight: bold; background-color: #f8f9fc; border: 1px solid #e3e6f0;">Thử việc</th>
        <th style="font-weight: bold; background-color: #f8f9fc; border: 1px solid #e3e6f0;">Hợp đồng xác định thời hạn</th>
        <th style="font-weight: bold; background-color: #f8f9fc; border: 1px solid #e3e6f0;">Hợp đồng không xác định thời hạn</th>
        <th style="font-weight: bold; background-color: #f8f9fc; border: 1px solid #e3e6f0;">Hợp đồng tái ký</th>
        <th style="font-weight: bold; background-color: #f8f9fc; border: 1px solid #e3e6f0;">Tổng cộng</th>
    </tr>
    @foreach($tableData as $row)
    <tr>
        <td style="border: 1px solid #e3e6f0;">{{ $row['phong_ban'] }}</td>
        <td style="border: 1px solid #e3e6f0; text-align: center;">{{ number_format($row['khong_hop_dong']) }}</td>
        <td style="border: 1px solid #e3e6f0; text-align: center;">{{ number_format($row['hop_dong_thu_viec']) }}</td>
        <td style="border: 1px solid #e3e6f0; text-align: center;">{{ number_format($row['hop_dong_xac_dinh']) }}</td>
        <td style="border: 1px solid #e3e6f0; text-align: center;">{{ number_format($row['hop_dong_khong_xac_dinh']) }}</td>
        <td style="border: 1px solid #e3e6f0; text-align: center;">{{ number_format($row['hop_dong_tai_ki']) }}</td>
        <td style="border: 1px solid #e3e6f0; text-align: center; font-weight: bold;">{{ number_format($row['tong_cong']) }}</td>
    </tr>
    @endforeach
    @if(isset($totalRow))
    <tr style="font-weight:bold; background:#f8f9fc;">
        <td style="border: 1px solid #e3e6f0;">{{ $totalRow['phong_ban'] }}</td>
        <td style="border: 1px solid #e3e6f0; text-align: center;">{{ number_format($totalRow['khong_hop_dong']) }}</td>
        <td style="border: 1px solid #e3e6f0; text-align: center;">{{ number_format($totalRow['hop_dong_thu_viec']) }}</td>
        <td style="border: 1px solid #e3e6f0; text-align: center;">{{ number_format($totalRow['hop_dong_xac_dinh']) }}</td>
        <td style="border: 1px solid #e3e6f0; text-align: center;">{{ number_format($totalRow['hop_dong_khong_xac_dinh']) }}</td>
        <td style="border: 1px solid #e3e6f0; text-align: center;">{{ number_format($totalRow['hop_dong_tai_ki']) }}</td>
        <td style="border: 1px solid #e3e6f0; text-align: center; font-weight: bold;">{{ number_format($totalRow['tong_cong']) }}</td>
    </tr>
    @endif
    <tr>
        <td colspan="7"></td>
    </tr>
    <tr>
        <td colspan="7" style="font-style: italic; text-align: right;">
            Ngày xuất báo cáo: {{ now()->format('d/m/Y H:i') }}
        </td>
    </tr>
</table>
