<?php

namespace Modules\Core\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Validation\Rule;
use LogicException;

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
            'per_page' => ['nullable', Rule::in(config('pagination.options'))],
            'sort' => ['nullable', 'string', Rule::in($contract['sortable'])],
            'direction' => ['nullable', Rule::in(['asc', 'desc'])],
            'include' => ['nullable', 'string', 'max:500'],
        ];

        foreach ($contract['filterable'] as $field => $options) {
            $rules[$field] = ['nullable', 'string', Rule::in($this->filterValues($options))];
        }

        foreach ($contract['filterableIds'] as $field => $column) {
            $rules[$field] = ['nullable', 'integer', 'min:1'];
        }

        return $rules;
    }

    public function paginateRecords(array $filters, ?Builder $query = null): LengthAwarePaginator
    {
        $query ??= $this->listQuery();
        $model = $query->getModel();
        $contract = $model->queryContract();
        $applied = [];
        $search = is_string($filters['search'] ?? null) ? trim($filters['search']) : '';

        if ($search !== '' && $contract['searchable'] !== []) {
            $this->applySearch($query, $search, $contract['searchable'], $contract['containsSearch']);
            $applied['search'] = $search;
        }

        foreach ($contract['filterable'] as $field => $options) {
            $value = $filters[$field] ?? null;

            if (is_string($value) && in_array($value, $this->filterValues($options), true)) {
                $query->where($model->qualifyColumn($field), $value);
                $applied[$field] = $value;
            }
        }

        foreach ($contract['filterableIds'] as $field => $column) {
            if (isset($filters[$field])) {
                $query->where($model->qualifyColumn($column), $filters[$field]);
                $applied[$field] = $filters[$field];
            }
        }

        $sort = $filters['sort'] ?? $contract['defaultSort'];
        $sort = in_array($sort, $contract['sortable'], true) ? $sort : $contract['defaultSort'];
        $direction = in_array($filters['direction'] ?? null, ['asc', 'desc'], true)
            ? $filters['direction'] : $contract['defaultDirection'];

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

        $size = $filters['per_page'] ?? config('pagination.default');
        $all = $size === 'all';
        $size = $all ? 'all' : (int) $size;

        if (! in_array($size, config('pagination.options'), true)) {
            $size = config('pagination.default');
        }

        $page = $all ? 1 : max(1, (int) ($filters['page'] ?? 1));
        $perPage = $all ? fn (int $total): int => max(1, $total) : $size;

        return $query->paginate($perPage, $query->getQuery()->getColumns(), 'page', $page)
            ->appends($applied + ['per_page' => $size]);
    }

    public function optionRecords(array $filters, array $fields): Paginator
    {
        $contract = $this->queryContract();
        $relations = array_intersect_key($contract['exposedRelations'], array_flip($fields));
        $columns = array_diff($fields, array_keys($relations));
        if (array_diff($columns, $contract['exposed']) !== []) {
            throw new LogicException('Selector fields must belong to the model exposure contract.');
        }

        $columns = array_unique([...$columns, $this->getKeyName()]);
        $query = $this->newQuery()->where($contract['constraints'])
            ->select(array_map($this->qualifyColumn(...), $columns));

        foreach ($relations as $relation => $relationColumns) {
            $query->with($relation.':'.implode(',', $relationColumns));
        }

        foreach ($this->selectorFilters ?? [] as $field) {
            if (isset($filters[$field])) {
                $query->where($this->qualifyColumn($field), $filters[$field]);
            }
        }

        $search = trim($filters['search'] ?? '');
        if ($search !== '' && $contract['searchable'] !== []) {
            $this->applySearch($query, $search, $contract['searchable'], $contract['containsSearch']);
        }

        foreach (array_unique([$contract['defaultSort'], $this->getKeyName()]) as $field) {
            $query->orderBy($this->qualifyColumn($field));
        }

        return $query->simplePaginate(20, $columns, 'page', $filters['page'] ?? 1);
    }

    private function applySearch(Builder $query, string $search, array $fields, array $containsSearch): void
    {
        $escaped = str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $search);
        $query->where(function (Builder $searchQuery) use ($fields, $escaped, $containsSearch): void {
            foreach ($fields as $field) {
                $column = $searchQuery->getGrammar()->wrap($searchQuery->getModel()->qualifyColumn($field));
                $pattern = in_array($field, $containsSearch, true) ? '%'.$escaped.'%' : $escaped.'%';
                $searchQuery->orWhereRaw($column." LIKE ? ESCAPE '!'", [$pattern]);
            }
        });
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
