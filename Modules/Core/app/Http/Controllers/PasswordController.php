<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Modules\Core\Http\Requests\PasswordRequest;
use Modules\Core\Models\Account;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class PasswordController extends Controller
{
    public function create(Request $request, ?string $token = null): View
    {
        return view('core::auth.password', [
            'pageTitle' => $token === null ? 'Forgot password' : 'Reset password',
            'description' => $token === null
                ? 'Enter your account email to receive a password reset link.'
                : 'Choose a new password for your account.',
            'formAction' => route($token === null ? 'auth.password.email' : 'auth.password.update'),
            'submitLabel' => $token === null ? 'Send reset link' : 'Reset password',
            'email' => is_string($request->query('email')) ? $request->query('email') : '',
            'token' => $token,
            'passwordMinimum' => config('auth.password_min_length'),
        ]);
    }

    public function store(PasswordRequest $request): RedirectResponse
    {
        $broker = $this->broker($request);

        if ($request->routeIs('auth.password.email')) {
            try {
                $broker->sendResetLink($request->safe()->only('email'));
            } catch (TransportExceptionInterface $exception) {
                report($exception);

                throw ValidationException::withMessages(['email' => 'Email delivery is unavailable. Please try again later.']);
            }

            return back()->with('status', 'If an account matches this email, a password reset link has been sent.');
        }

        $status = $broker->reset($request->validated(), function (Account $account, #[\SensitiveParameter] string $password): void {
            $account->forceFill(['password' => $password, 'remember_token' => Str::random(60)])->save();
            event(new PasswordReset($account));
        });

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages(['email' => 'This reset link is invalid or expired. Request a new link.']);
        }

        return redirect()->route('auth.login')->with('status', 'Your password has been reset. Sign in with your new password.');
    }

    private function broker(Request $request): PasswordBroker
    {
        return Password::broker($request->attributes->get('application_provider'));
    }
}
