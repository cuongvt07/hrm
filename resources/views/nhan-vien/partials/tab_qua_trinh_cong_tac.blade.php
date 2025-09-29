@if(isset($nhanVien) && isset($chucVus) && isset($phongBans))
<div class="mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Quá trình công tác</span>
    </div>
    <div class="card-body p-0">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th>Chức vụ</th>
                    <th>Phòng ban</th>
                    <th>Mô tả</th>
                    <th>Thời gian</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($nhanVien->quaTrinhCongTac ?? []) as $qt)
                <tr>
                    <td>{{ $qt->chucVu->ten ?? '' }}</td>
                    <td>{{ $qt->phongBan->ten ?? '' }}</td>
                    <td>{{ $qt->mo_ta }}</td>
                    <td>{{ $qt->ngay_bat_dau }} - {{ $qt->ngay_ket_thuc ?? '...' }}</td>
                    <td>
                        <form action="{{ route('qua-trinh-cong-tac.destroy', $qt->id) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Xóa quá trình này?')">Xóa</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">Chưa có dữ liệu</td></tr>
                @endforelse
                <tr>
                    <form action="{{ route('qua-trinh-cong-tac.store', $nhanVien->id) }}" method="POST">
                        @csrf
                        <td>
                            <select name="chucvu_id" class="form-select" required>
                                <option value="">-- Chọn chức vụ --</option>
                                @foreach($chucVus as $cv)
                                    <option value="{{ $cv->id }}">{{ $cv->ten }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select name="phongban_id" class="form-select" required>
                                <option value="">-- Chọn phòng ban --</option>
                                @foreach($phongBans as $pb)
                                    <option value="{{ $pb->id }}">{{ $pb->ten }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="text" name="mo_ta" class="form-control" placeholder="Mô tả">
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <input type="date" name="ngay_bat_dau" class="form-control" required>
                                <input type="date" name="ngay_ket_thuc" class="form-control">
                            </div>
                        </td>
                        <td>
                            <button type="submit" class="btn btn-sm btn-success">Thêm</button>
                        </td>
                    </form>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endif
