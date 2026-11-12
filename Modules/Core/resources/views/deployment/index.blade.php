@extends('core::layouts.app')

@section('title', 'Update Application')

@section('content')
    @include('core::partials.heading', ['title' => 'Update Application'])

    @php
        $statusLabels = [
            'idle' => 'Ready',
            'running' => 'In progress',
            'failed' => 'Stopped',
            'completed' => 'Completed',
            'completed_with_errors' => 'Completed with errors',
        ];
        $continueUpdate = in_array($deployment['status'], ['running', 'failed'], true);
    @endphp

    <section class="card">
        <div class="card-body">
            <p>Run pending central and tenant migrations, sync permissions and defaults, and clear framework caches.</p>
            <p class="text-body-secondary" id="deployment-instructions">
                Upload the application code, built assets, and vendor files first. Back up the central and tenant databases before running.
                After a new upload, use Run Update. Use Continue Update only to resume the same uploaded version.
            </p>

            <form id="deployment-form" action="{{ route('core.deploy.store') }}" method="POST"
                data-run-url="{{ route('core.deploy.run') }}" aria-describedby="deployment-instructions deployment-keep-open">
                @csrf
                <div class="d-flex flex-wrap gap-2">
                    <button class="btn btn-primary" type="submit" id="deployment-submit" name="restart" value="1">
                        <span class="spinner-border spinner-border-sm me-2" id="deployment-spinner" aria-hidden="true" hidden></span>
                        <span id="deployment-submit-label">Run Update</span>
                    </button>
                    <button class="btn btn-outline-secondary" type="submit" id="deployment-continue" name="restart" value="0"
                        @if (! $continueUpdate) hidden @endif>Continue Update</button>
                </div>
                <p class="small text-body-secondary mt-2 mb-4" id="deployment-keep-open">
                    Keep this page open until the update finishes. An interrupted update can be continued.
                </p>
            </form>

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                <span class="badge text-bg-secondary" id="deployment-status">{{ $statusLabels[$deployment['status']] }}</span>
                <span class="small" id="deployment-progress-value">{{ $deployment['progress'] }}%</span>
            </div>
            <div class="progress mb-3" id="deployment-progress" role="progressbar" aria-label="Update progress"
                aria-valuenow="{{ $deployment['progress'] }}" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar" id="deployment-progress-bar" style="width: {{ $deployment['progress'] }}%"></div>
            </div>
            <p id="deployment-message" role="status" aria-live="polite" aria-atomic="true">{{ $deployment['message'] }}</p>

            <dl class="row small mb-0">
                <dt class="col-sm-3 col-lg-2">Tenants processed</dt>
                <dd class="col-sm-9 col-lg-10" id="deployment-tenants">{{ $deployment['tenant_processed'] }} of {{ $deployment['tenant_total'] }}</dd>
                <dt class="col-sm-3 col-lg-2">Started</dt>
                <dd class="col-sm-9 col-lg-10" id="deployment-started">{{ $deployment['started_at'] ?? 'Not started' }}</dd>
                <dt class="col-sm-3 col-lg-2">Finished</dt>
                <dd class="col-sm-9 col-lg-10" id="deployment-finished">{{ $deployment['finished_at'] ?? 'Not finished' }}</dd>
            </dl>
        </div>

        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <caption class="visually-hidden">Update results</caption>
                <thead>
                    <tr>
                        <th scope="col" class="ps-3">Update step</th>
                        <th scope="col">Result</th>
                        <th scope="col" class="pe-3">Details</th>
                    </tr>
                </thead>
                <tbody id="deployment-results">
                    @forelse ($deployment['results'] as $result)
                        <tr>
                            <th scope="row" class="ps-3 fw-normal">{{ $result['label'] }}</th>
                            <td>
                                <span class="badge {{ $result['success'] ? 'text-bg-success' : 'text-bg-danger' }}">
                                    {{ $result['success'] ? 'Success' : 'Failed' }}
                                </span>
                            </td>
                            <td class="pe-3 text-break">{{ $result['message'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-3 text-body-secondary">No update steps have run yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
