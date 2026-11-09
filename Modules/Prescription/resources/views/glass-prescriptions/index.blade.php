@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', [
        'title' => $pageTitle,
        'description' => 'Glass prescriptions at the selected branch.',
        'create' => ['model' => \Modules\Prescription\Models\GlassPrescription::class, 'url' => route($routePrefix.'.create'), 'label' => 'New glass prescription'],
    ])

    <section class="app-card overflow-hidden">
        <form class="p-3 border-bottom" action="{{ route($routePrefix.'.index') }}" method="GET" aria-label="Search glass prescriptions">
            <div class="row g-2 align-items-end">
                <div class="col-md-7 col-xl-5">
                    <label class="form-label small" for="glass_search">Search</label>
                    <input class="{{ inputClass('search') }}" id="glass_search" name="search" type="search" maxlength="80" value="{{ getValue('search', null, $filters['search'] ?? '') }}" placeholder="Rx number, ID or date" {{ inputAttrs('search', 'glass_search') }}>
                    @error('search')<div class="invalid-feedback d-block" id="glass_search-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-5">
                    @include('core::partials.filter-actions', ['routePrefix' => $routePrefix])
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table app-table align-middle mb-0">
                <caption class="visually-hidden">Saved glass prescriptions with patient and doctor details</caption>
                <thead>
                    <tr>
                        <th scope="col">Prescription</th>
                        <th scope="col">Date</th>
                        <th scope="col">Patient</th>
                        <th scope="col">Doctor</th>
                        <th class="text-end" scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $record)
                        <tr>
                            <th scope="row">{{ $record->rx_number ?? '#'.$record->id }}</th>
                            <td class="text-nowrap">{{ $record->prescription_date?->format('d M Y') }}</td>
                            <td><strong class="d-block">{{ $record->patient?->name }}</strong><small class="text-body-secondary">{{ $record->patient?->patient_id }}</small></td>
                            <td>{{ $record->doctor?->name }}</td>
                            <td>
                                <div class="d-flex justify-content-end gap-2">
                                    @can('view', $record)
                                        <a class="btn btn-outline-primary btn-sm app-action-button" href="{{ route($routePrefix.'.show', $record) }}" aria-label="View glass prescription #{{ $record->id }}" title="View / Print"><i class="fa-solid fa-print" aria-hidden="true"></i></a>
                                    @endcan
                                    @can('update', $record)
                                        <a class="btn btn-outline-secondary btn-sm app-action-button" href="{{ route($routePrefix.'.edit', $record) }}" aria-label="Edit glass prescription #{{ $record->id }}" title="Edit"><i class="fa-solid fa-pen-to-square" aria-hidden="true"></i></a>
                                    @endcan
                                    @can('delete', $record)
                                        <form action="{{ route($routePrefix.'.destroy', $record) }}" method="POST" data-confirm="Delete this glass prescription? This cannot be undone.">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm app-action-button" type="submit" aria-label="Delete glass prescription #{{ $record->id }}" title="Delete"><i class="fa-solid fa-trash-can" aria-hidden="true"></i></button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="text-center text-body-secondary py-4" colspan="5">No glass prescriptions match these filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters])
    </section>
@endsection
