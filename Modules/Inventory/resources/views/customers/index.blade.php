@extends('core::layouts.app')
@section('title', $pageTitle)
@section('content')
    @include('core::partials.heading', ['title' => $pageTitle])
    <section class="app-card overflow-hidden">
        <form class="p-3 border-bottom" method="GET" action="{{ route('inventory.customers.index') }}">
            <input name="per_page" type="hidden" value="{{ $filters['per_page'] ?? config('pagination.default') }}">
            <div class="row g-2 align-items-end">
                <div class="col-sm-8 col-lg-5">
                    <label class="form-label" for="customer_search">Patient ID, name or mobile</label>
                    <input class="form-control" id="customer_search" name="search" value="{{ $filters['search'] ?? '' }}" maxlength="80">
                </div>
                <div class="col-sm-4">
                    <button class="btn btn-primary" type="submit">
                        Search
                    </button>
                </div>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Patient ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Mobile</th>
                        <th scope="col">Type</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                        <tr>
                            <td>{{ $record['patient_id'] }}</td>
                            <td>{{ $record['name'] }}</td>
                            <td>{{ $record['mobile_no'] }}</td>
                            <td>{{ ucfirst($record['type']) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="text-center text-body-secondary py-4" colspan="4">No customers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @include('core::partials.pagination', ['paginator' => $records, 'filters' => $filters, 'routePrefix' => 'inventory.customers'])
    </section>
@endsection
