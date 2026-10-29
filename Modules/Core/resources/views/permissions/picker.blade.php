<section class="app-card p-3 p-md-4 mb-4" aria-labelledby="permission-{{ $recordField }}-heading">
    <h2 class="h6 mb-3" id="permission-{{ $recordField }}-heading">
        <i class="fa-solid fa-{{ $recordLabel === 'plan' ? 'layer-group' : 'shield-halved' }} me-2 text-body-secondary" aria-hidden="true"></i>Choose a {{ $recordLabel }}
    </h2>
    <form action="{{ $pickerAction }}" method="GET" data-permission-picker>
        <div class="row g-3 align-items-end">
            <div class="col-lg-5">
                <label class="form-label" for="{{ $recordField }}-search">Find a {{ $recordLabel }}</label>
                <div class="input-group">
                    <input
                        class="{{ inputClass($searchField) }}"
                        id="{{ $recordField }}-search"
                        name="{{ $searchField }}"
                        type="search"
                        value="{{ getValue($searchField, null, $searchValue) }}"
                        placeholder="{{ ucfirst($recordLabel) }} name starts with..."
                        maxlength="80"
                        {{ inputAttrs($searchField, $recordField.'-search', $recordField.'-search-help') }}
                    >
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fa-solid fa-magnifying-glass me-2" aria-hidden="true"></i>Search
                    </button>
                </div>
                @error($searchField)
                    <div class="invalid-feedback d-block" id="{{ $recordField }}-search-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-lg-5">
                <label class="form-label" for="permission-{{ $recordField }}">{{ ucfirst($recordLabel) }}</label>
                <select
                    class="{{ inputClass($recordField, 'form-select') }}"
                    id="permission-{{ $recordField }}"
                    name="{{ $recordField }}"
                    data-permission-record-select
                    {{ inputAttrs($recordField, 'permission-'.$recordField) }}
                >
                    <option value="">Select a {{ $recordLabel }}</option>
                    @foreach ($records as $record)
                        <option value="{{ $record->id }}" {{ getSelected($record->id, $recordField, null, $selectedRecord?->id) }}>{{ $record->name }}</option>
                    @endforeach
                </select>
                @error($recordField)
                    <div class="invalid-feedback d-block" id="permission-{{ $recordField }}-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-lg-2">
                <button class="btn btn-primary w-100" type="submit">
                    <i class="fa-solid fa-arrow-right me-2" aria-hidden="true"></i>Load
                </button>
            </div>
        </div>
        <p class="form-text mb-0 mt-2" id="{{ $recordField }}-search-help">
            @if ($hasMoreRecords)
                More {{ $recordLabel }}s match this search. Narrow the name to find the {{ $recordLabel }} you need.
            @else
                Search by the start of a {{ $recordLabel }} name, then select a {{ $recordLabel }} to review its permissions.
            @endif
        </p>
    </form>
</section>
