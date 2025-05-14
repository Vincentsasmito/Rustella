<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login (if they _are_ verified).
     *
     * @var string
     */
    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * After the user is “authenticated” (i.e. credentials correct),
     * Laravel calls this.  We’ll intercept unverified users here.
     */
    protected function authenticated(Request $request, $user)
    {
        if (! $user->hasVerifiedEmail()) {
            auth()->logout();

            // throw a validation error or redirect to the notice:
            return redirect()
                ->route('verification.notice')
                ->with('warning', 'You must verify your email before logging in.');
        }
    }

    /**
     * If you still want to customize the “wrong‐password” message
     * to also catch the “not verified” case, you can override:
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (
            $user
            && Hash::check($request->password, $user->password)
            && ! $user->hasVerifiedEmail()
        ) {
            throw ValidationException::withMessages([
                $this->username() => ['You must verify your email before logging in.'],
            ]);
        }

        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    protected function loggedOut(Request $request)
    {
        // e.g. back to login page, or anywhere else you like:
        return redirect()->route('login');
    }
}
