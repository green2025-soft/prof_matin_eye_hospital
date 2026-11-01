<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Models\Account;
use Modules\Core\Services\AccessManager;

class SelectorRequest extends BaseRequest
{
    protected array $rules = [
        'search' => ['nullable', 'string', 'max:80'],
        'page' => ['nullable', 'integer', 'min:1'],
    ];

    public function authorize(): bool
    {
        $actor = $this->user('web');

        return $actor instanceof Account && app(AccessManager::class)->actorMatchesContext($actor);
    }

    public function rules(): array
    {
        $rules = parent::rules();
        $model = $this->controller()->modelClass();

        $instance = new $model;
        $rules['search'] = $instance->queryRules()['search'];
        $filters = $instance->queryContract()['filterable'];

        foreach ($instance->selectorFilters() as $field) {
            $choices = $filters[$field] ?? null;
            $rules[$field] = is_array($choices)
                ? ['nullable', Rule::in(array_is_list($choices) ? $choices : array_keys($choices))]
                : ['nullable', 'integer', 'min:1'];
        }

        return $rules;
    }
}
