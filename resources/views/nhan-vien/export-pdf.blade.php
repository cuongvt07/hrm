<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách nhân viên</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2c3e50;
        }
        
        .header p {
            margin: 5px 0 0 0;
            color: #7f8c8d;
        }
        
        .info {
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        
        .info p {
            margin: 5px 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #2c3e50;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .status {
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .status-nhan_vien_chinh_thuc {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-thu_viec {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-thai_san {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        .status-nghi_viec {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .status-khac {
            background-color: #e2e3e5;
            color: #383d41;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DANH SÁCH NHÂN VIÊN</h1>
        <p>Hệ thống quản lý nhân sự</p>
    </div>
    
    <div class="info">
        <p><strong>Ngày xuất báo cáo:</strong> {{ date('d/m/Y H:i:s') }}</p>
        <p><strong>Tổng số nhân viên:</strong> {{ $nhanViens->count() }}</p>
    </div>
    
    @if($nhanViens->count() > 0)
        <table>
            <thead>
                <tr>
                    <th width="5%">STT</th>
                    <th width="12%">Mã NV</th>
                    <th width="20%">Họ tên</th>
                    <th width="15%">Email</th>
                    <th width="12%">SĐT</th>
                    <th width="15%">Phòng ban</th>
                    <th width="12%">Chức vụ</th>
                    <th width="9%">Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @foreach($nhanViens as $index => $nhanVien)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $nhanVien->ma_nhanvien }}</strong></td>
                    <td>{{ $nhanVien->ho }} {{ $nhanVien->ten }}</td>
                    <td>{{ $nhanVien->email ?? '-' }}</td>
                    <td>{{ $nhanVien->so_dien_thoai ?? '-' }}</td>
                    <td>{{ $nhanVien->phongBan->ten_phong_ban ?? '-' }}</td>
                    <td>{{ $nhanVien->chucVu->ten_chuc_vu ?? '-' }}</td>
                    <td>
                        @php
                            $statusClass = [
                                'nhan_vien_chinh_thuc' => 'status-nhan_vien_chinh_thuc',
                                'thu_viec' => 'status-thu_viec',
                                'thai_san' => 'status-thai_san',
                                'nghi_viec' => 'status-nghi_viec',
                                'khac' => 'status-khac'
                            ][$nhanVien->trang_thai] ?? 'status-khac';
                            
                            $statusText = [
                                'nhan_vien_chinh_thuc' => 'Chính thức',
                                'thu_viec' => 'Thử việc',
                                'thai_san' => 'Thai sản',
                                'nghi_viec' => 'Nghỉ việc',
                                'khac' => 'Khác'
                            ][$nhanVien->trang_thai] ?? 'Khác';
                        @endphp
                        <span class="status {{ $statusClass }}">{{ $statusText }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <h3>Không có dữ liệu</h3>
            <p>Không tìm thấy nhân viên nào phù hợp với điều kiện lọc.</p>
        </div>
    @endif
    
    <div class="footer">
        <p>Báo cáo được tạo tự động bởi hệ thống HRM - {{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>
