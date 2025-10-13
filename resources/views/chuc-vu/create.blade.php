@extends('layouts.app')
@section('title', 'Thêm chức vụ')
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">Thêm chức vụ</div>
        <div class="card-body">
            <form method="POST" action="{{ route('chuc-vu.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Tên chức vụ</label>
                    <input type="text" name="ten_chuc_vu" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Lưu</button>
                <a href="{{ route('chuc-vu.index') }}" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
</div>
@endsection