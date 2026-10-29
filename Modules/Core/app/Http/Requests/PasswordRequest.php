<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'email' => [$this->isMethod('GET') ? 'nullable' : 'required', 'string', 'email', 'max:255'],
            'account_type' => $this->attributes->get('application_provider') === 'users'
                ? ['required', 'string', Rule::in(array_keys(config('auth.login_types')))] : ['missing'],
        ];

        if ($this->routeIs('auth.password.update')) {
            $rules += [
                'token' => ['required', 'string', 'max:255'],
                'password' => ['required', 'string', 'min:'.config('auth.password_min_length'), 'max:4096', 'confirmed'],
                'password_confirmation' => ['required', 'string', 'max:4096'],
            ];
        }

        return $rules;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['email' => normalizeEmail($this->input('email'))]);

        if ($this->attributes->get('application_provider') === 'users' && ! $this->exists('account_type')) {
            $this->merge(['account_type' => 'user']);
        }
    }
}
