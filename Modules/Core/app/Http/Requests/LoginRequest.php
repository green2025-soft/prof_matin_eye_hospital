<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'account_type' => $this->attributes->get('application_provider') === 'users'
                ? ['required', 'string', Rule::in(array_keys(config('auth.login_types')))] : ['missing'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:'.config('auth.password_min_length', 5), 'max:4096'],
            'remember' => ['sometimes', 'boolean'],
        ];
    }

    public function authenticate(): void
    {
        $credentials = $this->safe()->only(['email', 'password']);
        if ($this->attributes->get('application_provider') === 'users' && $this->validated('account_type') !== 'user') {
            $credentials['account_type'] = $this->validated('account_type');
        }

        if (! Auth::guard('web')->attempt($credentials, $this->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => normalizeEmail($this->input('email')),
        ]);

        if ($this->attributes->get('application_provider') === 'users' && ! $this->exists('account_type')) {
            $this->merge(['account_type' => 'user']);
        }
    }
}
