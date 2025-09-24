<td>
    <div class="btn-group" role="group">
        <a href="{{ route($showRoute, $id) }}" class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
            <i class="fas fa-eye"></i>
        </a>
        <a href="{{ route($editRoute, $id) }}" class="btn btn-sm btn-outline-secondary" title="Chỉnh sửa">
            <i class="fas fa-edit"></i>
        </a>
        <button type="button" class="btn btn-sm btn-outline-danger delete-btn" data-id="{{ $id }}" data-name="{{ $name ?? '' }}" title="Xóa">
            <i class="fas fa-trash"></i>
        </button>
    </div>
</td>
