<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = ['email' => ['required', 'string', 'email', 'min:5', 'max:255']];

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
    }
}
