<?php

namespace Modules\Hospital\Http\Requests;

use Modules\Core\Http\Requests\BaseRequest;
use Modules\Core\Models\Setting;

class ReferenceRequest extends BaseRequest
{
    public function rules(): array
    {
        $settings = Setting::values('hospital');
        $percentRules = ['required', 'numeric', 'regex:/\A\d{1,3}(?:\.\d{1,2})?\z/', 'between:0,100'];

        return [
            'name' => ['required', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'contact_no' => ['required', 'string', 'max:30', 'regex:/\A(?=.*[0-9])[0-9+() .-]+\z/'],
            'address' => ['nullable', 'string', 'max:2000'],
            'consultation_percent' => $settings['reference_percentages_enabled'] && $settings['reference_consultation_percent_enabled'] ? $percentRules : ['prohibited'],
            'investigation_percent' => $settings['reference_percentages_enabled'] && $settings['reference_investigation_percent_enabled'] ? $percentRules : ['prohibited'],
            'operation_percent' => $settings['reference_percentages_enabled'] && $settings['reference_operation_percent_enabled'] ? $percentRules : ['prohibited'],
        ];
    }
}
