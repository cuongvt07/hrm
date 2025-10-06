@extends('layouts.app')
@section('title', 'Cài đặt hệ thống')
@section('content')
    <div class="row">
        <div class="col-md-3">
            <!-- Sidebar danh mục -->
            <div class="list-group" id="danhMucSidebar">
                @foreach($danhMucs as $danhMuc)
                    <a href="{{ route('cai-dat.index', ['danh_muc' => $danhMuc]) }}"
                        class="list-group-item list-group-item-action {{ $activeDanhMuc == $danhMuc ? 'active' : ($loop->last && !$activeDanhMuc ? 'active' : '') }}">
                        <i class="fas fa-folder"></i> {{ $danhMuc }}
                    </a>
                @endforeach
                <button class="btn btn-sm btn-success mt-2"
                    onclick="document.getElementById('addDanhMucForm').classList.toggle('d-none')">+ Thêm danh mục
                    mới</button>
                <div id="addDanhMucForm" class="card card-body mt-2 d-none">
                    <form id="formAddDanhMuc">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label">Tên danh mục</label>
                            <input type="text" id="tenDanhMucInput" name="ten_cai_dat" class="form-control"
                                placeholder="Tên danh mục" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Slug (item)</label>
                            <input type="text" id="slugDanhMucInput" name="gia_tri_cai_dat" class="form-control"
                                placeholder="Slug" readonly required>
                        </div>
                        <div class="mb-2">
                            <textarea name="mo_ta" class="form-control" placeholder="Mô tả"></textarea>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary btn-sm"
                                onclick="document.getElementById('addDanhMucForm').classList.add('d-none')">Đóng</button>
                            <button type="submit" class="btn btn-primary btn-sm">Thêm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <span><i class="fas fa-folder-open"></i>
                            {{ isset($danhMucModel) ? $danhMucModel->ten_cai_dat : ($danhMucs->count() ? $danhMucs->last() : 'Chưa chọn danh mục') }}</span>
                        @if(isset($danhMucModel) && $danhMucModel->mo_ta)
                            <span class="text-muted small">{{ $danhMucModel->mo_ta }}</span>
                        @endif
                    </div>
                    @if(isset($danhMucModel))
                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteDanhMuc('{{ $danhMucModel->id }}')">
                            <i class="fas fa-trash"></i> Xóa danh mục
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    <form id="inlineItemForm" method="POST" action="{{ route('cai-dat.store') }}">
                        @csrf
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 30%">Tên item</th>
                                    <th style="width: 50%">Mô tả</th>
                                    <th style="width: 20%">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="itemTableBody">
                                @if(isset($items) && count($items))
                                    @foreach($items as $item)
                                        <tr data-id="{{ $item->id }}">
                                            <td>
                                                <input type="text" class="form-control form-control-sm" name="ten_item"
                                                    value="{{ $item->ten_item }}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm" name="mo_ta"
                                                    value="{{ $item->mo_ta }}">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-success btn-save-item"
                                                    data-id="{{ $item->id }}">Lưu</button>
                                                <form method="POST" action="" style="display:inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Xóa item này?')">Xóa</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                <!-- Dòng thêm mới -->
                                <tr id="addRow">
                                    <td>
                                        <input type="text" class="form-control form-control-sm" name="new_ten_item"
                                            placeholder="Tên item mới">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" name="new_mo_ta"
                                            placeholder="Mô tả">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" id="btnAddItem">Thêm</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
        // Xóa danh mục
        function deleteDanhMuc(id) {
            if (!confirm('Bạn có chắc chắn muốn xóa danh mục này? Tất cả các items trong danh mục này cũng sẽ bị xóa.')) {
                return;
            }
            
            fetch(`{{ url('cai-dat') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (window.HRM && typeof HRM.showSuccess === 'function') {
                        HRM.showSuccess(data.message || 'Xóa danh mục thành công!');
                    } else {
                        alert(data.message || 'Xóa danh mục thành công!');
                    }
                    window.location.href = "{{ route('cai-dat.index') }}";
                } else {
                    alert(data.message || 'Lỗi khi xóa danh mục!');
                }
            })
            .catch(() => alert('Lỗi mạng!'));
        }

        // Tự động sinh slug cho danh mục
        function toSlug(str) {
            str = str.toLowerCase();
            str = str.normalize('NFD').replace(/\p{Diacritic}/gu, '');
            str = str.replace(/[^a-z0-9]+/g, '-');
            str = str.replace(/^-+|-+$/g, '');
            return str;
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Lưu item
            document.querySelectorAll('.btn-save-item').forEach(btn => {
                btn.addEventListener('click', function () {
                    const row = btn.closest('tr');
                    const id = btn.getAttribute('data-id');
                    const ten_item = row.querySelector('input[name="ten_item"]').value;
                    const mo_ta = row.querySelector('input[name="mo_ta"]').value;
                    fetch(`{{ url('cai-dat-item') }}/${id}`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            ten_item: ten_item,
                            mo_ta: mo_ta
                        })
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                if (window.HRM && typeof HRM.showSuccess === 'function') {
                                    HRM.showSuccess(data.message || 'Cập nhật thành công!');
                                } else {
                                    alert(data.message || 'Cập nhật thành công!');
                                }
                            } else {
                                alert(data.message || 'Lỗi!');
                            }
                        })
                        .catch(() => alert('Lỗi mạng!'));
                });
            });

            // Thêm danh mục mới bằng AJAX
            var formAddDanhMuc = document.getElementById('formAddDanhMuc');
            if (formAddDanhMuc) {
                formAddDanhMuc.addEventListener('submit', function (e) {
                    e.preventDefault();
                    var formData = new FormData(formAddDanhMuc);
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
                                alert('Lỗi dữ liệu trả về!');
                                return;
                            }
                            if (data.success) {
                                if (window.HRM && typeof HRM.showSuccess === 'function') {
                                    HRM.showSuccess(data.message || 'Thêm danh mục thành công!');
                                } else {
                                    alert(data.message || 'Thêm danh mục thành công!');
                                }
                                setTimeout(() => window.location.reload(), 1000);
                            } else {
                                alert(data.message || 'Lỗi!');
                            }
                        })
                        .catch(() => alert('Lỗi mạng!'));
                });
            }

            // Thêm item mới
            document.getElementById('btnAddItem').addEventListener('click', function () {
                const row = document.getElementById('addRow');
                const ten = row.querySelector('input[name="new_ten_item"]').value.trim();
                const mo_ta = row.querySelector('input[name="new_mo_ta"]').value.trim();
                if (!ten) {
                    alert('Vui lòng nhập tên item!');
                    return;
                }
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('ten_item', ten);
                formData.append('mo_ta', mo_ta);
                var danhMucId = "{{ isset($danhMucModel) ? $danhMucModel->id : '' }}";
                formData.append('danh_muc_id', danhMucId);
                fetch("{{ route('cai-dat-item.store', isset($danhMucModel) ? $danhMucModel->id : 0) }}", {
                    method: "POST",
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            if (window.HRM && typeof HRM.showSuccess === 'function') {
                                HRM.showSuccess(data.message || 'Thêm item thành công!');
                            } else {
                                alert(data.message || 'Thêm item thành công!');
                            }
                            // Reload lại đúng danh mục cha đang mở
                            window.location.href = "{{ route('cai-dat.index') }}?danh_muc={{ isset($danhMucModel) ? $danhMucModel->ten_cai_dat : '' }}";
                        } else {
                            alert(data.message || 'Lỗi!');
                        }
                    })
                    .catch(() => alert('Lỗi mạng!'));
            });

            // Tự động sinh slug khi nhập tên danh mục
            var tenInput = document.getElementById('tenDanhMucInput');
            var slugInput = document.getElementById('slugDanhMucInput');
            if (tenInput && slugInput) {
                tenInput.addEventListener('input', function () {
                    slugInput.value = toSlug(tenInput.value);
                });
            }
        });
        </script>
    @endpush
@endsection