

<form id="contractFilterForm" class="row g-2 mb-3 align-items-end" method="GET" action="{{ route('hop-dong.index') }}">
    <div class="col-md-3">
        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm số HĐ, tên nhân viên..." value="{{ request('search') }}">
    </div>
    <div class="col-md-2">
        <select name="phong_ban_id" class="form-select select-scrollable">
            <option value="">Tất cả phòng ban</option>
            @if(isset($phongBans))
                @foreach($phongBans as $phongBan)
                    <option value="{{ $phongBan->id }}" {{ request('phong_ban_id') == $phongBan->id ? 'selected' : '' }}>
                        {{ $phongBan->ten_phong_ban }}
                    </option>
                    @if($phongBan->phongBanCon && $phongBan->phongBanCon->count() > 0)
                        @foreach($phongBan->phongBanCon as $phongBanCon)
                            <option value="{{ $phongBanCon->id }}" {{ request('phong_ban_id') == $phongBanCon->id ? 'selected' : '' }}>
                                &nbsp;&nbsp;&nbsp;&nbsp;└─ {{ $phongBanCon->ten_phong_ban }}
                            </option>
                        @endforeach
                    @endif
                @endforeach
            @endif
        </select>
    </div>
    <div class="col-md-2">
        <select name="trang_thai" class="form-select">
            <option value="">Trạng thái</option>
            <option value="hieu_luc" {{ request('trang_thai')=='hieu_luc' ? 'selected' : '' }}>Hiệu lực</option>
            <option value="het_hieu_luc" {{ request('trang_thai')=='het_hieu_luc' ? 'selected' : '' }}>Hết hiệu lực</option>
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

<style>
.select-scrollable {
    overflow-y: auto !important;
    max-height: 300px !important;
}
</style>
