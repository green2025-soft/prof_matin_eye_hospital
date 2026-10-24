@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle, 'description' => 'Manage role names. Assign their access separately in Permissions.', 'create' => ['model' => \Modules\Core\Models\Role::class, 'url' => route('core.roles.create'), 'label' => 'New role']])
    <section class="app-card overflow-hidden">
        <div class="app-card-heading d-flex align-items-center gap-2 px-3 px-md-4 py-3 border-bottom"><i class="fa-solid fa-shield-halved text-body-secondary" aria-hidden="true"></i><h2 class="h6 mb-0">Role directory</h2></div>
        <form class="px-3 px-md-4 py-3 border-bottom" action="{{ route('core.roles.index') }}" method="GET" aria-label="Filter roles">
            <div class="row g-2 align-items-end">
                <div class="col-md-8 col-xl-7">
                    <label class="form-label small" for="search">Search roles</label>
                    <div class="app-input-wrap app-search-field">
                        <i class="fa-solid fa-magnifying-glass app-search-icon" aria-hidden="true"></i>
                        <input class="{{ inputClass('search') }}" id="search" name="search" type="search" value="{{ getValue('search', null, $filters['search'] ?? '') }}" placeholder="Role name starts with…" maxlength="80" {{ inputAttrs('search') }}>
                    </div>
                    @error('search')<div class="invalid-feedback d-block" id="search-error">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4 col-xl-5">@include('core::partials.filter-actions', ['routePrefix' => 'core.roles'])</div>
            </div>
        </form>
        @if ($records->isEmpty())
            @include('core::partials.empty', ['title' => 'No roles found', 'description' => 'Try a different role name.', 'icon' => 'key'])
        @else
            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <caption class="visually-hidden">Roles and their creation dates</caption>
                    <thead><tr><th scope="col">Role</th><th scope="col">Created</th><th class="text-end" scope="col">Actions</th></tr></thead>
                    <tbody>
                        @foreach ($records as $role)
                            <tr>
                                <th scope="row">
                                    <div class="app-table-person d-flex align-items-center gap-3">
                                        <span class="app-table-avatar" aria-hidden="true"><i class="fa-solid fa-shield-halved"></i></span>
                                        <div class="fw-semibold">{{ $role->name }}</div>
                                    </div>
                                </th>
                                <td class="small text-body-secondary text-nowrap">{{ $role->created_at?->format('d M Y') ?? '—' }}</td>
                                <td>
                                    <div class="d-flex justify-content-end gap-2">
                                        @can('viewPermissions', \Modules\Core\Models\Role::class)
                                            <a class="btn btn-outline-secondary btn-sm app-action-button" href="{{ route('core.permissions.index', ['role' => $role->id]) }}" aria-label="Permissions for {{ $role->name }}" title="Permissions for {{ $role->name }}"><i class="fa-solid fa-key" aria-hidden="true"></i></a>
                                        @endcan
                                        @include('core::partials.row-actions', ['model' => $role, 'routePrefix' => 'core.roles'])
                                    </div>
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
