<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Validation\Rules\Unique;
use Modules\Core\Http\Controllers\BaseController;

class BaseRequest extends FormRequest
{
    protected array $rules = [];

    protected array $checkboxes = [];

    protected array $fieldAbilities = [];

    public function authorize(): bool
    {
        $record = $this->record();
        $subject = $record ?? $this->controller()->modelClass();

        if (! Gate::allows($this->isUpdate() ? 'update' : 'create', $subject)) {
            return false;
        }

        foreach ($this->fieldAbilities as $field => $ability) {
            $marker = $this->checkboxes[$field] ?? null;

            if (($this->exists($field) || ($marker && $this->exists($marker))) && ! Gate::allows($ability, $subject)) {
                return false;
            }
        }

        return true;
    }

    public function rules(): array
    {
        return $this->rules;
    }

    public function record(): ?Model
    {
        return $this->controller()->record($this);
    }

    public function isUpdate(): bool
    {
        return $this->controller()->isUpdate($this);
    }

    protected function validationRules(): array
    {
        $rules = parent::validationRules();

        if (! $this->isUpdate()) {
            return $rules;
        }

        foreach ($rules as $field => $fieldRules) {
            if (str_contains($field, '.')) {
                continue;
            }

            $rules[$field] = array_map(
                fn ($rule) => in_array($rule, ['required', 'present'], true) ? 'sometimes' : $rule,
                is_array($fieldRules) ? $fieldRules : explode('|', $fieldRules),
            );
        }

        return $rules;
    }

    protected function onCreate(array|string $createRules, array|string $updateRules = 'prohibited'): array|string
    {
        return $this->isUpdate() ? $updateRules : $createRules;
    }

    protected function unique(string $field): Unique
    {
        return Rule::unique($this->controller()->modelClass(), $field)->ignore($this->record());
    }

    protected function existsIn(string $table, string $field = 'id'): Exists
    {
        $modelClass = $this->controller()->modelClass();
        $model = new $modelClass;

        return Rule::exists($model->getConnection()->getName().'.'.$table, $field);
    }

    protected function prepareForValidation(): void
    {
        foreach ($this->checkboxes as $field => $marker) {
            if (in_array($this->input($marker), ['1', 1], true) && ! $this->exists($field)) {
                $this->merge([$field => []]);
            }
        }
    }

    protected function controller(): BaseController
    {
        $controller = $this->route()->getController();
        abort_unless($controller instanceof BaseController, 404);

        return $controller;
    }
}
