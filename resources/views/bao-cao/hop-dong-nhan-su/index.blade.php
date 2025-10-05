@extends('layouts.app')

@section('title', 'Báo cáo hợp đồng nhân sự')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <x-ui.card>
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Từ ngày</label>
                        <input type="date" class="form-control" name="from_date" value="{{ optional($fromDate)->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Đến ngày</label>
                        <input type="date" class="form-control" name="to_date" value="{{ optional($toDate)->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Trạng thái hợp đồng</label>
                        <select class="form-select" name="trang_thai">
                            <option value="">Tất cả</option>
                            <option value="hieu_luc" @if($trangThai=='hieu_luc') selected @endif>Còn hiệu lực</option>
                            <option value="het_hieu_luc" @if($trangThai=='het_hieu_luc') selected @endif>Hết hiệu lực</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-filter me-2"></i>Lọc dữ liệu</button>
                        <a href="{{ route('bao-cao.hop-dong.export', ['from_date' => request('from_date'), 'to_date' => request('to_date'), 'trang_thai' => request('trang_thai')]) }}" class="btn btn-success">
                            <i class="fas fa-file-excel me-2"></i>Xuất Excel
                        </a>
                    </div>
                </form>
            </x-ui.card>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <x-ui.card title="Báo cáo phân loại hợp đồng nhân viên">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>Phòng ban</th>
                                @foreach($loaiHopDong as $loai)
                                    <th>{{ $loai }}</th>
                                @endforeach
                                <th>Tổng cộng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tableData as $row)
                            <tr>
                                <td class="text-start">{{ $row['phong_ban'] }}</td>
                                @foreach($loaiHopDong as $loai)
                                    <td>{{ number_format($row[$loai]) }}</td>
                                @endforeach
                                <td>{{ number_format($row['tong_cong']) }}</td>
                            </tr>
                            @endforeach
                            <tr style="font-weight:bold; background:#f8f9fc;">
                                <td class="text-start">{{ $totalRow['phong_ban'] }}</td>
                                @foreach($loaiHopDong as $loai)
                                    <td>{{ number_format($totalRow[$loai]) }}</td>
                                @endforeach
                                <td>{{ number_format($totalRow['tong_cong']) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </x-ui.card>
        </div>
    </div>
</div>
@endsection
