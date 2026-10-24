@if ($paginator->total() > 0)
    @php
        $startPage = max(1, min($paginator->currentPage() - 2, $paginator->lastPage() - 4));
        $endPage = min($paginator->lastPage(), $startPage + 4);
    @endphp
    <nav aria-label="Table pagination">
        <ul class="pagination pagination-sm mb-0">
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true"><span class="page-link" aria-label="First page">&laquo;</span></li>
                <li class="page-item disabled" aria-disabled="true"><span class="page-link" aria-label="Previous page">&lsaquo;</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $paginator->url(1) }}" aria-label="First page">&laquo;</a></li>
                <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Previous page">&lsaquo;</a></li>
            @endif
            @foreach ($paginator->getUrlRange($startPage, $endPage) as $page => $url)
                @if ($page === $paginator->currentPage())
                    <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $url }}" aria-label="Page {{ $page }}">{{ $page }}</a></li>
                @endif
            @endforeach
            @if ($paginator->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Next page">&rsaquo;</a></li>
                <li class="page-item"><a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}" aria-label="Last page">&raquo;</a></li>
            @else
                <li class="page-item disabled" aria-disabled="true"><span class="page-link" aria-label="Next page">&rsaquo;</span></li>
                <li class="page-item disabled" aria-disabled="true"><span class="page-link" aria-label="Last page">&raquo;</span></li>
            @endif
        </ul>
    </nav>
@endif
