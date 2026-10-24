<div class="app-pagination d-flex flex-wrap align-items-center justify-content-between gap-3 border-top px-3 px-md-4 py-3">
    <form class="d-flex flex-wrap align-items-center gap-2" action="{{ $paginator->path() }}" method="GET" data-page-size-form>
        @foreach (\Illuminate\Support\Arr::except($filters, ['page', 'per_page']) as $key => $value)
            @if (is_scalar($value) && $value !== '')
                <input name="{{ $key }}" type="hidden" value="{{ $value }}">
            @endif
        @endforeach
        <label class="visually-hidden" for="page-size">Entries per page</label>
        <select class="form-select form-select-sm w-auto" id="page-size" name="per_page" data-page-size>
            @foreach (config('pagination.options') as $size)
                <option value="{{ $size }}" {{ getSelected($size, 'per_page', null, $filters['per_page'] ?? config('pagination.default')) }}>{{ $size === 'all' ? 'All' : $size }}</option>
            @endforeach
        </select>
        <noscript><button class="btn btn-outline-secondary btn-sm" type="submit">Apply</button></noscript>
        @if ($paginator->total() > 0)
            <p class="small text-body-secondary mb-0">Showing <span class="fw-semibold">{{ $paginator->firstItem() ?? 0 }}</span> to <span class="fw-semibold">{{ $paginator->lastItem() ?? 0 }}</span> of <span class="fw-semibold">{{ number_format($paginator->total()) }}</span> entries</p>
        @endif
    </form>
    @include('core::partials.page-links', ['paginator' => $paginator])
</div>
