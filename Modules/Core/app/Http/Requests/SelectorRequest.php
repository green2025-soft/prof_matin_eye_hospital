<?php

namespace Modules\Core\Http\Requests;

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

        foreach ((new $model)->selectorFilters() as $field) {
            $rules[$field] = ['nullable', 'integer', 'min:1'];
        }

        return $rules;
    }
}
