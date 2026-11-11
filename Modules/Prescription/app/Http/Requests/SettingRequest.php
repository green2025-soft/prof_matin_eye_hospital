<?php

namespace Modules\Prescription\Http\Requests;

use Illuminate\Support\Arr;
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

        return [
            'data' => ['required', 'array:'.implode(',', array_keys($fields))],
        ] + Arr::prependKeysWith($fields, 'data.');
    }

    public function payload(): array
    {
        $values = $this->validated('data');

        // Setting reads switches as booleans; form values arrive as "0" or "1".
        foreach (config('settings.defaults.prescription') as $field => $default) {
            if (is_bool($default)) {
                $values[$field] = $this->boolean('data.'.$field);
            }
        }

        return ['data' => $values];
    }
}
