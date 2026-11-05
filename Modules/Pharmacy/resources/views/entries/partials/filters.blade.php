<form class="p-3 border-bottom" method="GET" action="{{ route($routePrefix.'.index') }}">
    <input name="product_type" type="hidden" value="{{ $filters['product_type'] ?? 'medicine' }}">
    <input name="per_page" type="hidden" value="{{ $filters['per_page'] ?? config('pagination.default') }}">
    <div class="row g-2 align-items-end">
        <div class="col-sm-6 col-lg-3">
            <label class="form-label small" for="search">{{ $numberLabel }}</label>
            <input class="form-control" id="search" name="search" value="{{ $filters['search'] ?? '' }}" maxlength="80">
        </div>
        <div class="col-sm-6 col-lg-2">
            <label class="form-label small" for="from_date">From date</label>
            <div class="input-group" data-datepicker>
                <input class="{{ inputClass('from_date') }}" id="from_date" name="from_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="{{ getValue('from_date', null, $filters['from_date'] ?? '') }}" data-input {{ inputAttrs('from_date') }}>
                <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="from_date" aria-label="Choose start date" title="Choose start date">
                    <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                </button>
            </div>
            @error('from_date')
                <div class="invalid-feedback d-block" id="from_date-error">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-sm-6 col-lg-2">
            <label class="form-label small" for="to_date">To date</label>
            <div class="input-group" data-datepicker>
                <input class="{{ inputClass('to_date') }}" id="to_date" name="to_date" type="text" inputmode="numeric" autocomplete="off" placeholder="YYYY-MM-DD" value="{{ getValue('to_date', null, $filters['to_date'] ?? '') }}" data-input {{ inputAttrs('to_date') }}>
                <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="to_date" aria-label="Choose end date" title="Choose end date">
                    <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                </button>
            </div>
            @error('to_date')
                <div class="invalid-feedback d-block" id="to_date-error">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-lg-3 d-flex gap-2">
            <button class="btn btn-primary" type="submit">
                Filter
            </button>
            <a class="btn btn-outline-secondary" href="{{ route($routePrefix.'.index', ['product_type' => $filters['product_type'] ?? 'medicine', 'per_page' => $filters['per_page'] ?? config('pagination.default')]) }}">
                Reset
            </a>
        </div>
    </div>
</form>
