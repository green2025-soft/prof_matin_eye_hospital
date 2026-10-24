<?php

namespace Modules\Core\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rule;

trait HandlesModelQueries
{
    public function listQuery(): Builder
    {
        $model = $this;
        $contract = $model->queryContract();
        $columns = array_unique([...$contract['listColumns'], $contract['defaultSort'], $model->getKeyName()]);

        return $model->newQuery()->where($contract['constraints'])->select(array_map($model->qualifyColumn(...), $columns))
            ->with($contract['listWith'])->withCount($contract['exposedCounts']);
    }

    public function queryRules(): array
    {
        $contract = $this->queryContract();
        $rules = [
            'search' => ['bail', 'nullable', 'string', 'max:80'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', Rule::in(config('pagination.options'))],
            'sort' => ['nullable', 'string', Rule::in($contract['sortable'])],
            'direction' => ['nullable', Rule::in(['asc', 'desc'])],
            'include' => ['nullable', 'string', 'max:500'],
        ];

        foreach ($contract['filterable'] as $field => $options) {
            $rules[$field] = ['nullable', 'string', Rule::in($this->filterValues($options))];
        }

        return $rules;
    }

    public function paginateRecords(array $filters): LengthAwarePaginator
    {
        $query = $this->listQuery();
        $model = $query->getModel();
        $contract = $model->queryContract();
        $applied = [];
        $search = is_string($filters['search'] ?? null) ? trim($filters['search']) : '';

        if ($search !== '' && $contract['searchable'] !== []) {
            $prefix = str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $search).'%';
            $query->where(function (Builder $searchQuery) use ($contract, $model, $prefix): void {
                foreach ($contract['searchable'] as $field) {
                    $column = $searchQuery->getGrammar()->wrap($model->qualifyColumn($field));
                    $searchQuery->orWhereRaw($column." LIKE ? ESCAPE '!'", [$prefix]);
                }
            });
            $applied['search'] = $search;
        }

        foreach ($contract['filterable'] as $field => $options) {
            $value = $filters[$field] ?? null;

            if (is_string($value) && in_array($value, $this->filterValues($options), true)) {
                $query->where($model->qualifyColumn($field), $value);
                $applied[$field] = $value;
            }
        }

        $sort = $filters['sort'] ?? $contract['defaultSort'];
        $sort = in_array($sort, $contract['sortable'], true) ? $sort : $contract['defaultSort'];
        $direction = ($filters['direction'] ?? null) === 'desc' ? 'desc' : 'asc';

        // Retain a unique tie-breaker when the allowed primary order changes.
        foreach (array_unique([$sort, $contract['defaultSort'], $model->getKeyName()]) as $field) {
            $query->orderBy($field, $direction);
        }

        if (array_key_exists('sort', $filters)) {
            $applied['sort'] = $sort;
        }

        if (array_key_exists('direction', $filters)) {
            $applied['direction'] = $direction;
        }

        $includes = is_string($filters['include'] ?? null) ? explode(',', $filters['include']) : [];
        $includes = array_values(array_intersect($contract['allowedIncludes'], $includes));

        foreach ($includes as $relation) {
            $columns = $contract['exposedRelations'][$relation] ?? [];

            if ($columns !== []) {
                $query->with($relation.':'.implode(',', $columns));
            }
        }

        if ($includes !== []) {
            $applied['include'] = implode(',', $includes);
        }

        $perPage = (int) ($filters['per_page'] ?? config('pagination.default'));

        if (! in_array($perPage, config('pagination.options'), true)) {
            $perPage = config('pagination.default');
        }

        $page = max(1, (int) ($filters['page'] ?? 1));

        return $query->paginate($perPage, $query->getQuery()->getColumns(), 'page', $page)
            ->appends($applied + ['per_page' => $perPage]);
    }

    public function prepareForForm(): static
    {
        $with = $this->queryContract()['formWith'];

        if ($this->exists) {
            return $this->loadMissing($with);
        }

        foreach ($with as $relation) {
            $name = explode('.', explode(':', $relation, 2)[0], 2)[0];
            $this->setRelation($name, $this->newCollection());
        }

        return $this;
    }

    private function filterValues(array $options): array
    {
        return array_is_list($options) ? $options : array_keys($options);
    }
}
