<li class="list-group-item">
    <div class="d-flex justify-content-between align-items-center">
        <span>
            <i class="fas fa-sitemap me-1"></i> {{ $phongBan->ten_phong_ban }}
        </span>
        <span>
            <a href="{{ route('phong-ban.edit', $phongBan) }}" class="btn btn-sm btn-warning">Sửa</a>
            <form action="{{ route('phong-ban.destroy', $phongBan) }}" method="POST" style="display:inline-block">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger" onclick="return confirm('Xóa phòng ban này?')">Xóa</button>
            </form>
        </span>
    </div>
    @if($phongBan->phongBanCon->count())
        <ul class="list-group ms-4 mt-2">
            @foreach($phongBan->phongBanCon as $child)
                @include('phong-ban.partials.tree', ['phongBan' => $child])
            @endforeach
        </ul>
    @endif
</li>
