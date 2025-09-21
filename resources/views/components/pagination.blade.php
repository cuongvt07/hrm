@props(['paginator'])

@if($paginator->hasPages())
<div class="pagination-component">
    <div class="pagination-info">
        <span class="text-muted">
            Hiển thị {{ $paginator->firstItem() }} - {{ $paginator->lastItem() }} 
            trong tổng số {{ $paginator->total() }} kết quả
        </span>
    </div>
    <div class="pagination-wrapper">
        <nav aria-label="Pagination">
            <ul class="pagination pagination-sm">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</div>
@endif
