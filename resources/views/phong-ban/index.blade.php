@extends('layouts.app')
@section('title', 'Quản lý phòng ban')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Danh sách phòng ban</h4>
        <a href="{{ route('phong-ban.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Thêm phòng ban
        </a>
    </div>
    <div class="card">
        <div class="card-body">
            <ul class="list-group">
                @foreach($phongBans as $pb)
                    @include('phong-ban.partials.tree', ['phongBan' => $pb])
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection