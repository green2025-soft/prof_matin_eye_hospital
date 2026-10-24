<?php

namespace Modules\Core\Models\Concerns;

use Closure;
use LogicException;
use Modules\Core\Models\Account;

trait HasCrudContract
{
    use HandlesModelQueries;

    public function writeTransaction(Account $actor, Closure $operation): mixed
    {
        return $this->getConnection()->transaction(fn (): mixed => $operation($actor), 3);
    }

    public function queryContract(): array
    {
        $exposed = $this->exposed ?? [];
        $columns = $this->listColumns ?? $exposed;

        if ($exposed === [] || $columns === [] || array_filter($exposed, fn ($column): bool => ! is_string($column) || ! preg_match('/^[a-z_][a-z0-9_]*$/i', $column))
            || array_diff($columns, $exposed) !== []) {
            throw new LogicException(static::class.' must declare explicit safe CRUD fields.');
        }

        $relations = $this->exposedRelations ?? [];
        $with = $this->listWith ?? [];

        return [
            'constraints' => $this->queryConstraints ?? [],
            'searchable' => $this->searchable ?? ['name'],
            'filterable' => $this->filterable ?? [],
            'sortable' => $this->sortable ?? ['name', 'id'],
            'defaultSort' => $this->defaultSort ?? 'name',
            'listColumns' => $columns,
            'listWith' => $with,
            'formWith' => $this->formWith ?? $with,
            'allowedIncludes' => $this->allowedIncludes ?? array_keys($relations),
            'exposed' => $exposed,
            'exposedRelations' => $relations,
            'exposedCounts' => $this->exposedCounts ?? [],
        ];
    }
}
