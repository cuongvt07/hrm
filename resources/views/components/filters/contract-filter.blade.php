

<form id="contractFilterForm" class="row g-2 mb-3 align-items-end" method="GET" action="{{ route('hop-dong.index') }}">
    <div class="col-md-4">
        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm số HĐ, tên nhân viên..." value="{{ request('search') }}">
    </div>
    <div class="col-md-2">
        <select name="trang_thai" class="form-select">
            <option value="">Trạng thái</option>
            <option value="hoat_dong" {{ request('trang_thai')=='hoat_dong' ? 'selected' : '' }}>Hoạt động</option>
            <option value="het_han" {{ request('trang_thai')=='het_han' ? 'selected' : '' }}>Hết hạn</option>
            <option value="cham_dut" {{ request('trang_thai')=='cham_dut' ? 'selected' : '' }}>Chấm dứt</option>
        </select>
    </div>
    <div class="col-md-2">
        <div class="input-group">
            <input type="date" name="ngay_bat_dau" class="form-control" value="{{ request('ngay_bat_dau') }}" placeholder="Từ ngày">
            <input type="date" name="ngay_ket_thuc" class="form-control" value="{{ request('ngay_ket_thuc') }}" placeholder="Đến ngày">
        </div>
    </div>
    <div class="col-md-1">
        <select name="thoi_han" class="form-select">
            <option value="">Thời hạn</option>
            @for($i=1;$i<=10;$i++)
                <option value="{{ $i }}" {{ request('thoi_han')==$i ? 'selected' : '' }}>{{ $i }} năm</option>
            @endfor
        </select>
    </div>
    <div class="col-md-1">
        <button type="submit" class="btn btn-info w-100"><i class="fas fa-filter"></i></button>
    </div>
</form>
