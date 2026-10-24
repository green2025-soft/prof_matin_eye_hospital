<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Modules\Core\Http\Requests\BaseRequest;
use Modules\Core\Http\Requests\ListRequest;
use Modules\Core\Models\Account;

abstract class BaseController extends Controller
{
    protected string $model;

    protected string $title;

    protected string $service;

    protected string $views;

    protected string $parameter;

    public string $routePrefix;

    public function modelClass(): string
    {
        return $this->model;
    }

    public function queryRules(): array
    {
        return (new $this->model)->queryRules();
    }

    public function isUpdate(Request $request): bool
    {
        return $request->routeIs($this->routePrefix.'.update');
    }

    public function record(Request $request): ?Model
    {
        if ($request->routeIs($this->routePrefix.'.store', $this->routePrefix.'.create')) {
            return null;
        }

        $record = $request->route($this->parameter);
        $model = new $this->model;

        abort_unless($record instanceof Model && get_class($record) === $this->model && $record->exists
            && $record->getConnection()->getName() === $model->getConnection()->getName(), 404);

        return $record;
    }

    public function index(ListRequest $request): View
    {
        $filters = $request->validated();

        return view($this->views.'.index', [
            'records' => (new $this->model)->paginateRecords($filters),
            'filters' => $filters,
            'filterOptions' => (new $this->model)->queryContract()['filterable'],
            'pageTitle' => Str::plural($this->title),
            'routePrefix' => $this->routePrefix,
        ]);
    }

    public function create(Request $request): View
    {
        Gate::authorize('create', $this->model);

        return $this->form($request, new $this->model);
    }

    public function edit(Request $request): View
    {
        $record = $this->record($request);
        Gate::authorize('update', $record);

        return $this->form($request, $record);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $record = $this->record($request);
        Gate::authorize('delete', $record);
        app($this->service)->delete($request->user('web'), $record);

        return $this->redirectAfterWrite()->with('status', $this->title.' deleted.');
    }

    protected function saveData(BaseRequest $request): RedirectResponse
    {
        $record = app($this->service)->save($request->user('web'), $this->record($request), $request->validated());

        if ($record instanceof Account && $request->user('web')->is($record)) {
            Auth::guard('web')->setUser($record);
            $request->session()->regenerate();
        }

        return $this->redirectAfterWrite($record)->with('status', $this->title.($this->isUpdate($request) ? ' updated.' : ' created.'));
    }

    private function form(Request $request, Model $record): View
    {
        $record = $record->prepareForForm();

        return view($this->views.'.form', [
            ...app($this->service)->formData($request->user('web'), $record),
            'formAction' => $record->exists ? route($this->routePrefix.'.update', $record) : route($this->routePrefix.'.store'),
            'formMethod' => $record->exists ? 'PUT' : 'POST',
            'pageTitle' => ($record->exists ? 'Edit ' : 'Create ').Str::lower($this->title),
            'submitLabel' => $record->exists ? 'Save changes' : 'Create '.Str::lower($this->title),
            'routePrefix' => $this->routePrefix,
        ]);
    }

    private function redirectAfterWrite(?Model $record = null): RedirectResponse
    {
        if (Gate::allows('viewAny', $this->model)) {
            return redirect()->route($this->routePrefix.'.index');
        }

        if ($record && Gate::allows('update', $record)) {
            return redirect()->route($this->routePrefix.'.edit', $record);
        }

        return redirect()->route('home');
    }
}
