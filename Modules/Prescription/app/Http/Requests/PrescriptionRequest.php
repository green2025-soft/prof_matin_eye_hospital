<?php

namespace Modules\Prescription\Http\Requests;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\BaseRequest;
use Modules\Core\Models\Setting;
use Modules\Hospital\Models\Doctor;

class PrescriptionRequest extends BaseRequest
{
    protected bool $partialUpdates = false;

    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $actor = $this->user('web');
        if ($actor instanceof Doctor) {
            $this->merge(['doctor_id' => $actor->getKey()]);
        }

        $this->mergeIfMissing(['medicines' => []]);

        $settings = Setting::values('prescription');
        $sectionDetails = $this->input('section_details') ?? [];
        $history = $this->input('history_details', []);
        $examination = $this->input('examination_details');

        if ($settings['general_follow_up_enabled'] && ! $this->filled('follow_up_interval')) {
            $this->merge([
                'follow_up_interval' => null,
                'follow_up_interval_unit' => null,
            ]);
        }

        if (is_array($sectionDetails)) {
            $this->merge(['section_details' => $sectionDetails + [
                'chief_complaints' => [],
                'history' => [],
                'examination' => [],
                'diagnosis' => [],
                'investigations' => [],
                'advice' => [],
            ]]);
        }

        if ($settings['general_history_enabled'] && is_array($history) && is_array($history['personal'] ?? [])) {
            $history['personal'] = ($history['personal'] ?? []) + [
                'smoker' => false,
                'ex_smoker' => false,
                'alcoholic' => false,
                'tobacco' => false,
            ];
            $this->merge(['history_details' => $history]);
        }

        if ($settings['general_examination_enabled'] && is_array($examination)) {
            $this->merge(['examination_details' => $examination + [
                'temperature_normal' => false,
                'weight_visible' => false,
                'height_visible' => false,
            ]]);
        }
    }

    public function rules(): array
    {
        $branchId = (int) $this->attributes->get('branch_id', 0);
        $settings = Setting::values('prescription');

        return [
            'patient_id' => ['bail', 'required', 'integer', 'min:1', $this->existsIn('patients')->where('branch_id', $branchId)],
            'doctor_id' => ['bail', 'required', 'integer', 'min:1', $this->existsIn('doctors')->where('branch_id', $branchId)],
            'prescription_date' => ['required', 'date_format:Y-m-d'],
            'chief_complaints' => [Rule::excludeIf(! $settings['general_chief_complaints_enabled']), 'nullable', 'string', 'max:5000'],
            'history' => [Rule::excludeIf(! $settings['general_history_enabled']), 'nullable', 'string', 'max:5000'],
            'history_details' => [
                Rule::excludeIf(! $settings['general_history_enabled']),
                'nullable',
                Rule::array(['drug', 'investigation', 'personal', 'personal_notes', 'family', 'surgical', 'allergic', 'other']),
            ],
            'history_details.drug' => ['nullable', 'string', 'max:5000'],
            'history_details.investigation' => ['nullable', 'string', 'max:5000'],
            'history_details.personal' => ['nullable', 'array:smoker,ex_smoker,alcoholic,tobacco'],
            'history_details.personal.smoker' => ['boolean'],
            'history_details.personal.ex_smoker' => ['boolean'],
            'history_details.personal.alcoholic' => ['boolean'],
            'history_details.personal.tobacco' => ['boolean'],
            'history_details.personal_notes' => ['nullable', 'string', 'max:5000'],
            'history_details.family' => ['nullable', 'string', 'max:5000'],
            'history_details.surgical' => ['nullable', 'string', 'max:5000'],
            'history_details.allergic' => ['nullable', 'string', 'max:5000'],
            'history_details.other' => ['nullable', 'string', 'max:5000'],
            'examination' => [Rule::excludeIf(! $settings['general_examination_enabled']), 'nullable', 'string', 'max:5000'],
            'examination_details' => [
                Rule::excludeIf(! $settings['general_examination_enabled']),
                'nullable',
                Rule::array([
                    'bp_systolic', 'bp_diastolic', 'pulse', 'temperature', 'weight', 'height_feet',
                    'height_inches', 'spo2', 'bmi', 'bsa', 'bmr', 'tdee', 'activity',
                    'pulse_rhythm', 'temperature_unit', 'temperature_normal', 'weight_visible', 'height_visible',
                    'ideal_weight', 'ideal_bmi', 'extra_calories', 'target_calories',
                ]),
            ],
            'examination_details.bp_systolic' => ['nullable', 'string', 'max:50'],
            'examination_details.bp_diastolic' => ['nullable', 'string', 'max:50'],
            'examination_details.pulse' => ['nullable', 'string', 'max:50'],
            'examination_details.pulse_rhythm' => ['nullable', 'string', Rule::in(['regular', 'irregular', 'drop_beat'])],
            'examination_details.temperature' => ['nullable', 'string', 'max:50'],
            'examination_details.temperature_unit' => ['nullable', 'string', Rule::in(['f', 'c'])],
            'examination_details.temperature_normal' => ['boolean'],
            'examination_details.weight' => ['nullable', 'string', 'max:50'],
            'examination_details.weight_visible' => ['boolean'],
            'examination_details.height_visible' => ['boolean'],
            'examination_details.height_feet' => ['nullable', 'string', 'max:50'],
            'examination_details.height_inches' => ['nullable', 'string', 'max:50'],
            'examination_details.spo2' => ['nullable', 'string', 'max:50'],
            'examination_details.bmi' => ['nullable', 'string', 'max:50'],
            'examination_details.bsa' => ['nullable', 'string', 'max:50'],
            'examination_details.bmr' => ['nullable', 'string', 'max:50'],
            'examination_details.tdee' => ['nullable', 'string', 'max:50'],
            'examination_details.activity' => ['nullable', 'string', 'max:50'],
            'examination_details.ideal_weight' => ['nullable', 'string', 'max:50'],
            'examination_details.ideal_bmi' => ['nullable', 'string', 'max:50'],
            'examination_details.extra_calories' => ['nullable', 'string', 'max:50'],
            'examination_details.target_calories' => ['nullable', 'string', 'max:50'],
            'diagnosis' => [Rule::excludeIf(! $settings['general_diagnosis_enabled']), 'nullable', 'string', 'max:5000'],
            'investigations' => [Rule::excludeIf(! $settings['general_investigations_enabled']), 'nullable', 'string', 'max:5000'],
            'advice' => [Rule::excludeIf(! $settings['general_advice_enabled']), 'nullable', 'string', 'max:5000'],
            'follow_up_date' => [
                Rule::excludeIf(! $settings['general_follow_up_enabled'] || $this->filled('follow_up_interval')),
                'nullable', 'date_format:Y-m-d', 'after_or_equal:prescription_date',
            ],
            'follow_up_notes' => [Rule::excludeIf(! $settings['general_follow_up_enabled']), 'nullable', 'string', 'max:5000'],
            'follow_up_interval' => [
                Rule::excludeIf(! $settings['general_follow_up_enabled']),
                'nullable', 'integer', 'between:1,3650',
            ],
            'follow_up_interval_unit' => [
                Rule::excludeIf(! $settings['general_follow_up_enabled']),
                'nullable', 'required_with:follow_up_interval', Rule::in(['days', 'months']),
            ],
            'section_details' => [
                'nullable',
                Rule::array(['chief_complaints', 'history', 'examination', 'diagnosis', 'investigations', 'advice']),
            ],
            'section_details.chief_complaints' => [Rule::excludeIf(! $settings['general_chief_complaints_enabled']), 'array', 'max:100'],
            'section_details.history' => [Rule::excludeIf(! $settings['general_history_enabled']), 'array', 'max:100'],
            'section_details.examination' => [Rule::excludeIf(! $settings['general_examination_enabled']), 'array', 'max:100'],
            'section_details.diagnosis' => [Rule::excludeIf(! $settings['general_diagnosis_enabled']), 'array', 'max:100'],
            'section_details.investigations' => [Rule::excludeIf(! $settings['general_investigations_enabled']), 'array', 'max:100'],
            'section_details.advice' => [Rule::excludeIf(! $settings['general_advice_enabled']), 'array', 'max:100'],
            'section_details.*.*' => ['array:name,duration,duration_unit,notes'],
            'section_details.*.*.name' => ['required', 'string', 'max:255'],
            'section_details.*.*.duration' => ['nullable', 'string', 'max:30'],
            'section_details.*.*.duration_unit' => [
                'nullable',
                Rule::in(['hours', 'days', 'weeks', 'months', 'years', 'long_ago']),
            ],
            'section_details.*.*.notes' => ['nullable', 'string', 'max:1000'],
            'medicines' => ['array', 'max:100'],
            'medicines.*' => ['array:product_id,medicine_name,dose,frequency,duration,instructions'],
            'medicines.*.product_id' => ['bail', 'nullable', 'integer', 'min:1', $this->existsIn('phr_products')->where('type', 'medicine')],
            'medicines.*.medicine_name' => ['required', 'string', 'max:255'],
            'medicines.*.dose' => ['nullable', 'string', 'max:100'],
            'medicines.*.frequency' => ['nullable', 'string', 'max:100'],
            'medicines.*.duration' => ['nullable', 'string', 'max:100'],
            'medicines.*.instructions' => ['nullable', 'string', 'max:255'],
        ];
    }
}
