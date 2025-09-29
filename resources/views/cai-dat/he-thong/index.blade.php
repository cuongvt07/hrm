@extends('layouts.app')
@section('title', 'Cài đặt hệ thống')
@section('content')
<div class="row">
    <div class="col-md-3">
        <!-- Sidebar danh mục -->
        <div class="list-group" id="danhMucSidebar">
            @foreach($danhMucs as $danhMuc)
                <a href="{{ route('cai-dat.index', ['danh_muc' => $danhMuc]) }}"
                   class="list-group-item list-group-item-action {{ $activeDanhMuc == $danhMuc ? 'active' : '' }}">
                    <i class="fas fa-folder"></i> {{ $danhMuc }}
                </a>
            @endforeach
            <button class="btn btn-sm btn-success mt-2" onclick="document.getElementById('addDanhMucForm').classList.toggle('d-none')">+ Thêm danh mục mới</button>
            <div id="addDanhMucForm" class="card card-body mt-2 d-none">
                <form id="formAddDanhMuc">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label">Tên danh mục</label>
                        <input type="text" id="tenDanhMucInput" name="ten_cai_dat" class="form-control" placeholder="Tên danh mục" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Slug (item)</label>
                        <input type="text" id="slugDanhMucInput" name="gia_tri_cai_dat" class="form-control" placeholder="Slug" readonly required>
                    </div>
                    <div class="mb-2">
                        <textarea name="mo_ta" class="form-control" placeholder="Mô tả"></textarea>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary btn-sm" onclick="document.getElementById('addDanhMucForm').classList.add('d-none')">Đóng</button>
                        <button type="submit" class="btn btn-primary btn-sm">Thêm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        @if($activeDanhMuc)
        <!-- Main content: bảng item -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-folder-open"></i> {{ $activeDanhMuc }}</span>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Tên item</th>
                            <th>Mô tả</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>{{ $item->gia_tri_cai_dat }}</td>
                                <td>{{ $item->mo_ta }}</td>
                                <td>
                                    <form method="POST" action="{{ route('cai-dat.destroy', $item->id) }}" style="display:inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Xóa item này?')">Xóa</button>
                                    </form>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editItemModal{{ $item->id }}">Sửa</button>
                                </td>
                            </tr>
                            <!-- Modal sửa item -->
                            <div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form class="modal-content" method="POST" action="{{ route('cai-dat.update', $item->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header"><h5 class="modal-title">Sửa item</h5></div>
                                        <div class="modal-body">
                                            <input type="text" name="ten_cai_dat" class="form-control mb-2" value="{{ $item->ten_cai_dat }}" required>
                                            <input type="text" name="gia_tri_cai_dat" class="form-control mb-2" value="{{ $item->gia_tri_cai_dat }}" required readonly>
                                            <textarea name="mo_ta" class="form-control" placeholder="Mô tả">{{ $item->mo_ta }}</textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                            <button type="submit" class="btn btn-warning">Cập nhật</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@push('scripts')
<script>
function toSlug(str) {
    str = str.toLowerCase();
    str = str.normalize('NFD').replace(/\p{Diacritic}/gu, '');
    str = str.replace(/[^a-z0-9]+/g, '-');
    str = str.replace(/^-+|-+$/g, '');
    return str;
}
document.addEventListener('DOMContentLoaded', function() {
    var tenInput = document.getElementById('tenDanhMucInput');
    var slugInput = document.getElementById('slugDanhMucInput');
    if (tenInput && slugInput) {
        tenInput.addEventListener('input', function() {
            slugInput.value = toSlug(tenInput.value);
        });
    }
    var form = document.getElementById('formAddDanhMuc');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(form);
            fetch("{{ route('cai-dat.store') }}", {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': formData.get('_token'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(async res => {
                let data;
                try {
                    data = await res.json();
                } catch (e) {
                    console.log('Không parse được JSON:', res);
                    alert('Lỗi dữ liệu trả về!');
                    return;
                }
                console.log('Response:', res, 'Data:', data);
                if (data.success) {
                    if (window.HRM && typeof HRM.showSuccess === 'function') {
                        HRM.showSuccess(data.message || 'Thêm danh mục thành công!');
                        setTimeout(() => location.reload(), 1200);
                    } else {
                        alert(data.message || 'Thêm danh mục thành công!');
                        location.reload();
                    }
                } else {
                    alert(data.message || 'Lỗi!');
                }
            })
            .catch(() => alert('Lỗi mạng!'));
        });
    }
});
</script>
@endpush
@endsection
