<?php

namespace Modules\Prescription\Http\Requests;

use Modules\Core\Http\Requests\BaseRequest;

class SettingRequest extends BaseRequest
{
    protected bool $partialUpdates = false;

    public function authorize(): bool
    {
        return parent::authorize() && $this->record()->group === 'prescription';
    }

    public function rules(): array
    {
        $fields = config('settings.rules.prescription');
        $rules = ['data' => ['required', 'array:'.implode(',', array_keys($fields))]];

        foreach ($fields as $field => $fieldRules) {
            $rules['data.'.$field] = $fieldRules;
        }

        return $rules;
    }

    public function payload(): array
    {
        $values = $this->validated('data');

        foreach (config('settings.defaults.prescription') as $field => $default) {
            if (is_bool($default)) {
                $values[$field] = $this->boolean('data.'.$field);
            }
        }

        return ['data' => $values];
    }
}
