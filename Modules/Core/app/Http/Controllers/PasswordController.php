<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Modules\Core\Http\Requests\PasswordRequest;
use Modules\Core\Models\Account;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class PasswordController extends Controller
{
    public function create(PasswordRequest $request, ?string $token = null): View
    {
        $tenantPassword = $request->attributes->get('application_provider') === 'users';
        $accountType = $request->validated('account_type', 'user');

        return view('core::auth.password', [
            'pageTitle' => $token === null ? 'Forgot password' : 'Reset password',
            'description' => $token === null
                ? 'Enter your account email to receive a password reset link.'
                : 'Choose a new password for your account.',
            'formAction' => route($token === null ? 'auth.password.email' : 'auth.password.update'),
            'submitLabel' => $token === null ? 'Send' : 'Reset',
            'email' => $request->validated('email') ?? '',
            'token' => $token,
            'passwordMinimum' => config('auth.password_min_length'),
            'tenantPassword' => $tenantPassword,
            'accountType' => $accountType,
            'accountTypes' => config('auth.login_types'),
            'loginUrl' => route('auth.login', $tenantPassword ? ['account_type' => $accountType] : []),
        ]);
    }

    public function store(PasswordRequest $request): RedirectResponse
    {
        $broker = $this->broker($request);
        $parameters = $request->attributes->get('application_provider') === 'users'
            ? ['account_type' => $request->validated('account_type')] : [];

        if ($request->routeIs('auth.password.email')) {
            try {
                $broker->sendResetLink($request->safe()->only('email'));
            } catch (TransportExceptionInterface $exception) {
                report($exception);

                throw ValidationException::withMessages(['email' => 'Email delivery is unavailable. Please try again later.']);
            }

            return redirect()->route('auth.password.request', $parameters)
                ->with('status', 'If an account matches this email, a password reset link has been sent.');
        }

        $status = $broker->reset($request->safe()->only(['email', 'token', 'password', 'password_confirmation']), function (Account $account, #[\SensitiveParameter] string $password): void {
            $account->forceFill(['password' => $password, 'remember_token' => Str::random(60)])->save();
            event(new PasswordReset($account));
        });

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages(['email' => 'This reset link is invalid or expired. Request a new link.']);
        }

        return redirect()->route('auth.login', $parameters)->with('status', 'Your password has been reset. Sign in with your new password.');
    }

    private function broker(PasswordRequest $request): PasswordBroker
    {
        $provider = $request->attributes->get('application_provider');
        if ($provider === 'users') {
            $provider = config('auth.login_types')[$request->validated('account_type')]['broker'];
        }

        return Password::broker($provider);
    }
}
