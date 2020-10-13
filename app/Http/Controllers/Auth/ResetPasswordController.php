<?php

namespace App\Http\Controllers\Auth;

use Adldap\AdldapException;
use Adldap\Laravel\Facades\Adldap;
use App\Models\Security\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Reset the given user's password.
     *
     * @param Request $request
     * @return RedirectResponse|JsonResponse
     */
    public function reset(Request $request)
    {
        $request->validate($this->rules(), $this->validationErrorMessages());

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
            $this->resetPassword($user, $password);
        }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response == Password::PASSWORD_RESET
            ? $this->sendResetResponse($request, $response)
            : $this->sendResetFailedResponse($request, $response);
    }

    /**
     * Get the password reset credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only(
            'email', 'username', 'password', 'password_confirmation', 'token'
        );
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token'    => 'required',
            'email'    => 'required|email',
            'username' => 'required|exists:mysql_ldap.users',
            'password' => 'required|confirmed|min:8',
        ];
    }

    /**
     * Reset the given user's password.
     *
     * @param CanResetPassword $user
     * @param string $password
     * @return JsonResponse|void
     * @throws AdldapException
     */
    protected function resetPassword($user, $password)
    {
        try {
            $ldapUser = Adldap::search()->findByGuid($user->guid);
            if ( $ldapUser instanceof \Adldap\Models\User) {
                $ldapUser->setPassword( $password );
                if ( $ldapUser->save() ) {
                    $user->password = Hash::make($password);
                    $user->setRememberToken(Str::random(60));
                    $user->save();
                    event(new PasswordReset($user));
                }
            }
        } catch (AdldapException $exception) {
            return $this->error_response(
                __('validation.handler.unexpected_failure'),
                Response::HTTP_INTERNAL_SERVER_ERROR,
                $exception
            );
        }
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('api');
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param Request $request
     * @param  string  $response
     * @return RedirectResponse|JsonResponse
     */
    protected function sendResetResponse(Request $request, $response)
    {
        return $this->success_message(
            __($response),
            Response::HTTP_OK
        );
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param Request $request
     * @param  string  $response
     * @return RedirectResponse|JsonResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return $this->error_response(
            __($response),
         Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}