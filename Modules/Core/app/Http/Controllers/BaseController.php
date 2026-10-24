<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Modules\Core\Http\Requests\BaseRequest;
use Modules\Core\Models\Account;

abstract class BaseController extends Controller
{
    protected string $model;

    protected string $title;

    protected string $service = '';

    protected string $views;

    protected string $parameter;

    public function modelClass(): string
    {
        return $this->model;
    }

    public function queryRules(): array
    {
        $model = $this->modelClass();

        return (new $model)->queryRules();
    }

    public function isUpdate(Request $request): bool
    {
        return $request->routeIs($this->routePrefix().'.update');
    }

    public function record(Request $request): ?Model
    {
        if ($request->routeIs($this->routePrefix().'.store', $this->routePrefix().'.create')) {
            return null;
        }

        $record = $request->route($this->parameter);
        $modelClass = $this->modelClass();
        $model = new $modelClass;

        abort_unless($record instanceof Model && get_class($record) === $modelClass && $record->exists
            && $record->getConnection()->getName() === $model->getConnection()->getName(), 404);

        return $record;
    }

    protected function listData(BaseRequest $request, ?array $data = null): View
    {
        $filters = $request->validated();
        $model = $this->modelClass();

        return view($this->views.'.index', ($data ?? [
            'records' => (new $model)->paginateRecords($filters),
            'filterOptions' => (new $model)->queryContract()['filterable'],
        ]) + [
            'filters' => $filters,
            'pageTitle' => Str::plural($this->title),
            'routePrefix' => $this->routePrefix(),
        ]);
    }

    public function create(Request $request): View
    {
        $model = $this->modelClass();
        Gate::authorize('create', $model);

        return $this->form($request, new $model);
    }

    public function edit(Request $request): View|RedirectResponse
    {
        $record = $this->record($request);
        Gate::authorize('update', $record);

        return $this->form($request, $record);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $record = $this->record($request);
        Gate::authorize('delete', $record);
        if ($this->service !== '') {
            app($this->service)->delete($request->user('web'), $record);
        } else {
            $record->writeTransaction($request->user('web'), function (Account $freshActor) use ($record): void {
                $target = $record->newQuery()->whereKey($record->getKey())->lockForUpdate()->firstOrFail();
                Gate::forUser($freshActor)->authorize('delete', $target);
                $target->delete();
            });
        }

        return $this->redirectAfterWrite()->with('status', $this->title.' deleted.');
    }

    protected function saveData(BaseRequest $request): RedirectResponse
    {
        $actor = $request->user('web');
        $record = $this->record($request);

        if ($this->service !== '') {
            $record = app($this->service)->save($actor, $record, $request->payload());
        } else {
            $model = $this->modelClass();

            try {
                $record = (new $model)->writeTransaction($actor, function (Account $freshActor) use ($record, $model, $request): Model {
                    $target = $record ? $record->newQuery()->whereKey($record->getKey())->lockForUpdate()->firstOrFail() : new $model;
                    Gate::forUser($freshActor)->authorize($target->exists ? 'update' : 'create', $target->exists ? $target : $model);
                    $target->fill($request->payload());
                    $target->save();

                    return $target;
                });
            } catch (UniqueConstraintViolationException) {
                throw ValidationException::withMessages(['record' => 'A record with these details already exists.']);
            }
        }

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
            ...$this->formData($request->user('web'), $record),
            $this->parameter => $record,
            'formAction' => $record->exists ? route($this->routePrefix().'.update', $record) : route($this->routePrefix().'.store'),
            'formMethod' => $record->exists ? 'PUT' : 'POST',
            'pageTitle' => ($record->exists ? 'Edit ' : 'Create ').Str::lower($this->title),
            'submitLabel' => $record->exists ? 'Save changes' : 'Create '.Str::lower($this->title),
            'routePrefix' => $this->routePrefix(),
        ]);
    }

    protected function formData(Account $actor, Model $record): array
    {
        return $this->service !== '' ? app($this->service)->formData($actor, $record) : [];
    }

    protected function redirectAfterWrite(?Model $record = null): RedirectResponse
    {
        if (Gate::allows('viewAny', $this->modelClass())) {
            return redirect()->route($this->routePrefix().'.index');
        }

        if ($record && Gate::allows('update', $record)) {
            return redirect()->route($this->routePrefix().'.edit', $record);
        }

        return redirect()->route('home');
    }

    protected function routePrefix(): string
    {
        return Str::beforeLast(request()->route()->getName(), '.');
    }
}
