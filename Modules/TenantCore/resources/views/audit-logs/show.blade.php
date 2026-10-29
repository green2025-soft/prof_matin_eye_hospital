@extends('core::layouts.app')

@section('title', $pageTitle)

@section('content')
    @include('core::partials.heading', ['title' => $pageTitle, 'description' => 'Review the activity details and recorded changes.'])

    <div class="mb-4">
        <a class="btn btn-outline-secondary btn-sm" href="{{ route($routePrefix.'.index') }}"><i class="fa-solid fa-arrow-left me-2" aria-hidden="true"></i>Audit Logs</a>
    </div>

    <section class="app-card p-3 p-md-4 mb-4" aria-labelledby="audit-log-details-heading">
        <h2 class="h6 mb-3" id="audit-log-details-heading">Activity details</h2>
        <dl class="row gy-2 mb-0">
            <dt class="col-sm-3">Time</dt>
            <dd class="col-sm-9 mb-0"><time datetime="{{ $auditLog->created_at?->toIso8601String() }}">{{ $auditLog->created_at?->format('d M Y H:i:s') }}</time></dd>
            <dt class="col-sm-3">Actor</dt>
            <dd class="col-sm-9 text-break mb-0">{{ $auditLog->actor_name ?? 'System' }}@if ($auditLog->actor_id !== null) <span class="text-body-secondary">({{ \Illuminate\Support\Str::headline(\Illuminate\Support\Str::singular($auditLog->actor_type)) }} #{{ $auditLog->actor_id }})</span>@endif</dd>
            <dt class="col-sm-3">Action</dt>
            <dd class="col-sm-9 mb-0">{{ $eventLabel }}</dd>
            <dt class="col-sm-3">Resource</dt>
            <dd class="col-sm-9 text-break mb-0">{{ $auditLog->resource }}</dd>
            <dt class="col-sm-3">Record</dt>
            <dd class="col-sm-9 text-break mb-0">{{ $auditLog->record_name ?? '-' }}@if ($auditLog->record_id !== null) <span class="text-body-secondary">(#{{ $auditLog->record_id }})</span>@endif</dd>
            <dt class="col-sm-3">Branch</dt>
            <dd class="col-sm-9 text-break mb-0">
                @if ($auditLog->branch_id !== null)
                    {{ $auditLog->branch_name ?? 'Branch' }} <span class="text-body-secondary">(#{{ $auditLog->branch_id }})</span>
                @else
                    No branch
                @endif
            </dd>
            <dt class="col-sm-3">IP address</dt>
            <dd class="col-sm-9 text-break mb-0">{{ $auditLog->ip_address ?? '-' }}</dd>
            <dt class="col-sm-3">User agent</dt>
            <dd class="col-sm-9 small text-break mb-0">{{ $auditLog->user_agent ?? '-' }}</dd>
        </dl>
    </section>

    <div class="row g-4">
        <div class="col-lg-6">
            <section class="app-card p-3 p-md-4" aria-labelledby="audit-log-old-values-heading">
                <h2 class="h6 mb-3" id="audit-log-old-values-heading">Old values</h2>
                <pre class="small overflow-auto mb-0"><code>{{ $oldValues }}</code></pre>
            </section>
        </div>
        <div class="col-lg-6">
            <section class="app-card p-3 p-md-4" aria-labelledby="audit-log-new-values-heading">
                <h2 class="h6 mb-3" id="audit-log-new-values-heading">New values</h2>
                <pre class="small overflow-auto mb-0"><code>{{ $newValues }}</code></pre>
            </section>
        </div>
    </div>
@endsection
