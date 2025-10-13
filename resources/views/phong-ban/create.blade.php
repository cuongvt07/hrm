@extends('layouts.app')
@section('title', 'Thêm phòng ban')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">Thêm phòng ban</div>
        <div class="card-body">
            <form method="POST" action="{{ route('phong-ban.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Tên phòng ban</label>
                    <input type="text" name="ten_phong_ban" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Phòng ban cha (nếu có)</label>
                    <select name="phong_ban_cha_id" class="form-select">
                        <option value="">-- Không chọn --</option>
                        @foreach($phongBans as $pb)
                            <option value="{{ $pb->id }}">{{ $pb->ten_phong_ban }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Lưu</button>
                <a href="{{ route('phong-ban.index') }}" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
</div>
@endsection