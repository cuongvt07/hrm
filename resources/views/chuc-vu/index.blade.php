@extends('layouts.app')
@section('title', 'Quản lý chức vụ')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Danh sách chức vụ</h4>
        <a href="{{ route('chuc-vu.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Thêm chức vụ
        </a>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Tên chức vụ</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($chucVus as $cv)
                        <tr>
                            <td>{{ $cv->ten_chuc_vu }}</td>
                            <td>
                                <a href="{{ route('chuc-vu.edit', $cv) }}" class="btn btn-sm btn-warning">Sửa</a>
                                <form action="{{ route('chuc-vu.destroy', $cv) }}" method="POST" style="display:inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Xóa chức vụ này?')">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection