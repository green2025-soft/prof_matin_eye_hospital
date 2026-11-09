<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Modules\Core\Http\Requests\BaseRequest;
use Modules\Core\Http\Requests\SelectorRequest;
use Modules\Core\Models\Account;
use RuntimeException;

abstract class BaseController extends Controller
{
    protected string $model;

    protected string $title;

    protected string $service = '';

    protected string $views;

    protected string $parameter;

    protected bool $inlineForm = false;

    protected array $selectorFields = ['id', 'name'];

    protected array $uploads = [];

    protected array $routeFilters = [];

    protected array $defaultFilters = [];

    protected string $uploadDirectory = '';

    public function modelClass(): string
    {
        return $this->model;
    }

    public function queryRules(): array
    {
        $model = $this->modelClass();

        return (new $model)->queryRules();
    }

    public function options(SelectorRequest $request): JsonResponse
    {
        $model = $this->modelClass();
        $records = (new $model)->optionRecords($request->validated(), $this->selectorFields);

        return response()
            ->json([
                'results' => $records->getCollection()
                    ->setVisible($this->selectorFields),
                'pagination' => [
                    'more' => $records->hasMorePages(),
                ],
            ]);
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

        // Inherited CRUD actions resolve their declared model without provider bindings.
        if (is_string($record) || is_int($record)) {
            $record = $model->resolveRouteBinding(
                $record,
                $request->route()
                    ->bindingFieldFor($this->parameter),
            );
        }

        abort_unless(
            $record instanceof Model && get_class($record) === $modelClass && $record->exists
                && $record->getConnection()
                    ->getName() === $model->getConnection()
                    ->getName(),
            404,
        );

        $request->route()
            ->setParameter($this->parameter, $record);

        return $record;
    }

    protected function listData(BaseRequest $request, ?array $data = null): View
    {
        $filters = array_replace($this->defaultFilters, array_filter($request->validated(), fn ($value): bool => $value !== null && $value !== ''));
        $model = $this->modelClass();

        $data = ($data ?? [
            'records' => (new $model)->paginateRecords($filters),
            'filterOptions' => (new $model)->queryContract()['filterable'],
        ]) + [
            'filters' => $filters,
            // 'pageTitle' => Str::plural($this->title),
            'pageTitle' => $this->title,
            'routePrefix' => $this->routePrefix(),
            'routeParameters' => $this->routeParameters(),
        ];

        if ($this->inlineForm) {
            $data['form'] = Gate::allows('create', $model) ? $this->formValues($request, new $model) : null;
        }

        return view($this->views.'.index', $data);
    }

    protected function paginateQuery(Builder|QueryBuilder $query, array $validatedFilters, array $serverColumns = ['*']): LengthAwarePaginator
    {
        $size = $validatedFilters['per_page'] ?? config('pagination.default');
        $size = $size === 'all' ? 'all' : (int) $size;
        if (! in_array($size, config('pagination.options'), true)) {
            $size = config('pagination.default');
        }

        $all = $size === 'all';
        $page = $all ? 1 : max(1, (int) ($validatedFilters['page'] ?? 1));
        $perPage = $all ? static fn (int $total): int => max(1, $total) : $size;
        unset($validatedFilters['page']);
        $validatedFilters['per_page'] = $size;

        return $query->paginate($perPage, $serverColumns, 'page', $page)
            ->appends($validatedFilters);
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
        $actor = $request->user('web');
        $record = $this->record($request);
        Gate::authorize('delete', $record);
        try {
            if ($this->service !== '') {
                app($this->service)
                    ->delete($actor, $record);
            } else {
                $record->writeTransaction($actor, function (Account $freshActor) use ($record): void {
                    $target = $record->newQuery()
                        ->whereKey($record->getKey())
                        ->lockForUpdate()
                        ->firstOrFail();
                    Gate::forUser($freshActor)
                        ->authorize('delete', $target);
                    $target->delete();

                    foreach ($this->uploads as $column) {
                        if ($path = $target->getAttribute($column)) {
                            $disk = Storage::disk('public');
                            $target->getConnection()
                                ->afterCommit(fn () => $disk->delete($path));
                        }
                    }
                });
            }
        } catch (QueryException $exception) {
            if ((int) ($exception->errorInfo[1] ?? 0) !== 1451) {
                throw $exception;
            }

            throw ValidationException::withMessages([
                'record' => 'This record is in use and cannot be deleted.',
            ]);
        }

        return $this->redirectAfterWrite($record)
            ->with('status', $this->title.' deleted.');
    }

    protected function saveData(BaseRequest $request, bool $jsonResponse = false): RedirectResponse|JsonResponse
    {
        $actor = $request->user('web');
        $record = $this->record($request);

        if ($this->service !== '') {
            $record = app($this->service)
                ->save($actor, $record, $request->payload());
        } else {
            $model = $this->modelClass();

            try {
                $record = (new $model)->writeTransaction($actor, function (Account $freshActor) use ($record, $model, $request): Model {
                    $target = $record
                        ? $record->newQuery()
                            ->whereKey($record->getKey())
                            ->lockForUpdate()
                            ->firstOrFail()
                        : new $model;
                    Gate::forUser($freshActor)
                        ->authorize($target->exists ? 'update' : 'create', $target->exists ? $target : $model);
                    $values = $request->payload();

                    foreach ($this->uploads as $input => $column) {
                        if (! $request->hasFile($input)) {
                            continue;
                        }

                        $disk = Storage::disk('public');
                        $path = $disk->putFile($this->uploadDirectory, $request->file($input));

                        if ($path === false) {
                            throw new RuntimeException('The uploaded image could not be stored.');
                        }

                        $target->getConnection()
                            ->afterRollBack(fn () => $disk->delete($path));

                        if ($previous = $target->getAttribute($column)) {
                            $target->getConnection()
                                ->afterCommit(fn () => $disk->delete($previous));
                        }

                        $values[$column] = $path;
                    }

                    $target->fill($values);
                    $target->save();

                    return $target;
                });
            } catch (UniqueConstraintViolationException) {
                throw ValidationException::withMessages([
                    'record' => 'A record with these details already exists.',
                ]);
            } catch (QueryException $exception) {
                if ((int) ($exception->errorInfo[1] ?? 0) !== 1451) {
                    throw $exception;
                }

                throw ValidationException::withMessages([
                    'record' => 'This record is in use. Its referenced fields cannot be changed.',
                ]);
            }
        }

        if ($record instanceof Account && $actor->is($record)) {
            Auth::guard('web')
                ->setUser($record);
            $request->session()
                ->regenerate();
        }

        if ($jsonResponse) {
            return response()
                ->json($record->only($record->queryContract()['exposed']), $this->isUpdate($request) ? 200 : 201);
        }

        return $this->redirectAfterWrite($record)
            ->with('status', $this->title.($this->isUpdate($request) ? ' updated.' : ' created.'));
    }

    private function form(Request $request, Model $record): View
    {
        $data = $this->formValues($request, $record);

        if ($this->inlineForm) {
            $model = $this->modelClass();
            $filters = array_replace($this->defaultFilters, $this->routeParameters($record));

            return view($this->views.'.index', [
                'records' => Gate::allows('viewAny', $model) ? (new $model)->paginateRecords($filters) : null,
                'filters' => $filters,
                'form' => $data,
            ] + $data);
        }

        return view($this->views.'.form', $data);
    }

    private function formValues(Request $request, Model $record): array
    {
        $record = $record->prepareForForm();
        $data = $this->formData($request->user('web'), $record);
        $routeParameters = $this->routeParameters($record);

        return [
            ...$data,
            $this->parameter => $record,
            'formAction' => $record->exists
                ? route($this->routePrefix().'.update', [
                    $this->parameter => $record,
                ] + $routeParameters)
                : route($this->routePrefix().'.store', $routeParameters),
            'formMethod' => $record->exists ? 'PUT' : 'POST',
            'pageTitle' => $data['pageTitle'] ?? ($record->exists ? 'Edit ' : 'Create ').$this->title,
            'submitLabel' => $record->exists ? 'Update' : 'Save',
            'routePrefix' => $this->routePrefix(),
            'routeParameters' => $routeParameters,
        ];
    }

    protected function formData(Account $actor, Model $record): array
    {
        return $this->service !== ''
            ? app($this->service)
                ->formData($actor, $record)
            : [];
    }

    protected function selectedOption(Builder $query, mixed $selectedId, array $columns = ['id', 'name']): ?Model
    {
        if ((! is_int($selectedId) && ! is_string($selectedId)) || blank($selectedId)) {
            return null;
        }

        return $query->find($selectedId, $columns);
    }

    protected function oldRows(string $field, array $defaults = [], int $limit = 100): array
    {
        $rows = old($field, $defaults);
        if (! is_array($rows)) {
            return [];
        }
        $rows = array_slice($rows, 0, $limit, true);
        foreach ($rows as $index => $row) {
            if (! is_array($row)) {
                unset($rows[$index]);

                continue;
            }
            $rows[$index] = array_filter($row, static fn ($value): bool => is_scalar($value) || $value === null);
        }

        return $rows;
    }

    protected function redirectAfterWrite(?Model $record = null): RedirectResponse
    {
        if (Gate::allows('viewAny', $this->modelClass())) {
            return redirect()
                ->route($this->routePrefix().'.index', $this->routeParameters($record));
        }

        if ($record && Gate::allows('update', $record)) {
            return redirect()
                ->route($this->routePrefix().'.edit', [
                    $this->parameter => $record,
                ] + $this->routeParameters($record));
        }

        return redirect()
            ->route('home');
    }

    protected function routeParameters(?Model $record = null): array
    {
        $parameters = menuParameters();
        foreach ($this->routeFilters as $queryKey => $column) {
            $value = $record?->getAttribute($column) ?? request()
                ->query($queryKey) ?? ($this->defaultFilters[$queryKey] ?? null);
            if ((is_string($value) || is_int($value)) && filled($value)) {
                $parameters[$queryKey] = $value;
            }
        }

        return $parameters;
    }

    protected function routePrefix(): string
    {
        return Str::beforeLast(
            request()
                ->route()
                ->getName(),
            '.',
        );
    }
}
