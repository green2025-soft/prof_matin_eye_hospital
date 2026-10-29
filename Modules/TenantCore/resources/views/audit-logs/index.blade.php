@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle, 'description' => 'Review account activity and record changes across branches.'])

    <section class="app-card overflow-hidden">
        <div class="app-card-heading d-flex align-items-center gap-2 px-3 px-md-4 py-3 border-bottom">
            <i class="fa-solid fa-clock-rotate-left text-body-secondary" aria-hidden="true"></i>
            <h2 class="h6 mb-0">Audit Logs</h2>
        </div>
        <form class="px-3 px-md-4 py-3 border-bottom" action="{{ route($routePrefix.'.index') }}" method="GET" aria-label="Filter audit logs">
            <div class="row g-3 align-items-end">
                <div class="col-lg-6">
                    <label class="form-label small" for="search">Search activity</label>
                    <div class="app-input-wrap app-search-field">
                        <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                        <input class="{{ inputClass('search') }}" id="search" name="search" type="search" value="{{ getValue('search', null, $filters['search'] ?? '') }}" maxlength="80" placeholder="Account or record name starts with..." {{ inputAttrs('search') }}>
                    </div>
                    @error('search')<div class="invalid-feedback d-block" id="search-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-lg-3">
                    <label class="form-label small" for="event">Action</label>
                    <select class="{{ inputClass('event', 'form-select') }}" id="event" name="event" {{ inputAttrs('event') }}>
                        <option value="">All actions</option>
                        @foreach ($eventOptions as $value => $label)
                            <option value="{{ $value }}" {{ getSelected($value, 'event', null, $filters['event'] ?? '') }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('event')<div class="invalid-feedback d-block" id="event-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-lg-3">
                    <label class="form-label small" for="branch_id">Branch</label>
                    <select class="{{ inputClass('branch_id', 'form-select') }}" id="branch_id" name="branch_id" data-ajax-select data-url="{{ route('tenantcore.audit-logs.branches') }}" data-value-field="id" data-label-field="name" data-placeholder="All branches" {{ inputAttrs('branch_id') }}>
                        <option value=""></option>
                        @if ($selectedBranch !== null)
                            <option value="{{ $selectedBranch->id }}" selected>{{ $selectedBranch->name }}</option>
                        @endif
                    </select>
                    @error('branch_id')<div class="invalid-feedback d-block" id="branch_id-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-lg-4">
                    <label class="form-label small" for="date_from">From date</label>
                    <div class="input-group" data-datepicker>
                        <input
                            class="{{ inputClass('date_from') }}"
                            id="date_from"
                            name="date_from"
                            type="text"
                            inputmode="numeric"
                            autocomplete="off"
                            placeholder="YYYY-MM-DD"
                            value="{{ getValue('date_from', null, $filters['date_from'] ?? '') }}"
                            data-input
                            {{ inputAttrs('date_from') }}
                        >
                        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="date_from" aria-label="Choose from date" title="Choose from date">
                            <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                        </button>
                        <button class="btn btn-outline-secondary" type="button" data-clear aria-controls="date_from" aria-label="Clear from date" title="Clear from date">
                            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                        </button>
                    </div>
                    @error('date_from')<div class="invalid-feedback d-block" id="date_from-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-sm-6 col-lg-4">
                    <label class="form-label small" for="date_to">To date</label>
                    <div class="input-group" data-datepicker>
                        <input
                            class="{{ inputClass('date_to') }}"
                            id="date_to"
                            name="date_to"
                            type="text"
                            inputmode="numeric"
                            autocomplete="off"
                            placeholder="YYYY-MM-DD"
                            value="{{ getValue('date_to', null, $filters['date_to'] ?? '') }}"
                            data-input
                            {{ inputAttrs('date_to') }}
                        >
                        <button class="btn btn-outline-secondary" type="button" data-toggle aria-controls="date_to" aria-label="Choose to date" title="Choose to date">
                            <i class="fa-solid fa-calendar-days" aria-hidden="true"></i>
                        </button>
                        <button class="btn btn-outline-secondary" type="button" data-clear aria-controls="date_to" aria-label="Clear to date" title="Clear to date">
                            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                        </button>
                    </div>
                    @error('date_to')<div class="invalid-feedback d-block" id="date_to-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-lg-4">
                    @include('core::partials.filter-actions', ['routePrefix' => $routePrefix])
                </div>
            </div>
        </form>

        @if ($records->isEmpty())
            @include('core::partials.empty', ['title' => 'No audit logs found', 'description' => 'Try different filters or clear the filters to see all activity.', 'icon' => 'clock-rotate-left'])
        @else
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Account activity and record changes across all branches</caption>
                    <thead>
                        <tr>
                            <th scope="col">Time</th>
                            <th scope="col">Actor</th>
                            <th scope="col">Action</th>
                            <th scope="col">Resource</th>
                            <th scope="col">Record</th>
                            <th scope="col">Branch</th>
                            <th class="text-end" scope="col">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $auditLog)
                            <tr>
                                <td class="small text-nowrap">
                                    <time datetime="{{ $auditLog->created_at?->toIso8601String() }}">{{ $auditLog->created_at?->format('d M Y H:i:s') }}</time>
                                </td>
                                <td class="small text-break">
                                    <div>{{ $auditLog->actor_name ?? 'System' }}</div>
                                    @if ($auditLog->actor_id !== null)
                                        <div class="text-body-secondary">{{ \Illuminate\Support\Str::headline(\Illuminate\Support\Str::singular($auditLog->actor_type)) }} #{{ $auditLog->actor_id }}</div>
                                    @endif
                                </td>
                                <td class="small">{{ $eventOptions[$auditLog->event] ?? $auditLog->event }}</td>
                                <td class="small text-break">{{ $auditLog->resource }}</td>
                                <td class="small text-break">
                                    <div>{{ $auditLog->record_name ?? '-' }}</div>
                                    @if ($auditLog->record_id !== null)
                                        <div class="text-body-secondary">#{{ $auditLog->record_id }}</div>
                                    @endif
                                </td>
                                <td class="small text-break">
                                    @if ($auditLog->branch_id !== null)
                                        <div><a href="{{ route($routePrefix.'.index', ['branch_id' => $auditLog->branch_id]) }}">{{ $auditLog->branch_name ?? 'Branch' }}</a></div>
                                        <div class="text-body-secondary">#{{ $auditLog->branch_id }}</div>
                                    @else
                                        No branch
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a class="btn btn-outline-secondary btn-sm text-nowrap" href="{{ route($routePrefix.'.show', $auditLog) }}" aria-label="View audit log #{{ $auditLog->id }}"><i class="fa-solid fa-eye me-2" aria-hidden="true"></i>Details</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
    </section>
@endsection
