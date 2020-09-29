<?php

namespace App\Http\Controllers\Auth;

use Adldap\AdldapException;
use Adldap\AdldapInterface;
use Adldap\Auth\BindException;
use Adldap\Auth\PasswordRequiredException;
use Adldap\Auth\UsernameRequiredException;
use Adldap\Laravel\Facades\Adldap;
use Adldap\Models\UserPasswordIncorrectException;
use Adldap\Models\UserPasswordPolicyException;
use App\Http\Controllers\Controller;
use App\Http\Resources\Auth\ActiveRecordResource;
use App\Http\Resources\Auth\UserResource;
use App\Models\Security\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\ServerRequest;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * The maximum number of attempts to allow.
     *
     * @var  int
     */
    protected $maxAttempts = 3;

    /**
     * @var Adldap
     */
    protected $ldap;

    /**
     * Constructor.
     *
     * @param AdldapInterface $ldap
     */
    public function __construct(AdldapInterface $ldap)
    {
        $this->ldap = $ldap;
    }

    /**
     * Handle a login request to the application.
     *
     * @param Request $request
     * @return JsonResponse|RedirectResponse|Response|\Symfony\Component\HttpFoundation\Response|void
     *
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->getToken( $request );
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        $token = auth('api')->user()->token();
        $token->revoke();
        $this->guard()->logout();
        $request->session()->invalidate();
        return $this->success_message(__('validation.handler.logout'), Response::HTTP_OK);
    }

    /**
     * Log the user out of the application and revoke all tokens associated.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logoutAllDevices(Request $request)
    {
        $tokens = auth('api')->user()->tokens;
        foreach ($tokens as $token) {
            $token->revoke();
        }
        $this->guard()->logout();
        $request->session()->invalidate();
        return $this->success_message(__('validation.handler.logout'), Response::HTTP_OK);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param Request $request
     * @return bool|JsonResponse
     * @throws PasswordRequiredException
     * @throws UsernameRequiredException
     */
    protected function attemptLogin(Request $request)
    {
        try {
            return Adldap::auth()->attempt($this->credentials($request)[ $this->username() ], $this->credentials($request)['password'], $bindAsUser = true);
        } catch (BindException $e) {
            $user = User::active()->where('username', $request->get( $this->username() ))->first();
            if ( $user ) {
                return Hash::check($request->get('password'), $user->password);
            }
            return false;
        }
    }

    /**
     * Get the failed login response instance.
     *
     * @param Request $request
     * @return JsonResponse
     *
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return $this->error_response(
            __('auth.failed'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /**
     * Redirect the user after determining they are locked out.
     *
     * @param Request $request
     * @return JsonResponse
     *
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        return $this->error_response(
            __('auth.throttle', ['seconds' => $seconds]),
            Response::HTTP_TOO_MANY_REQUESTS
        );
    }

    /**
     * Return token if user is successful logged in.
     *
     * @param Request $request
     * @return JsonResponse
     *
     */
    protected function getToken(Request $request)
    {
        $request->request->add([
            'client_id'     =>  env('PASSPORT_CLIENT_ID'),
            'client_secret' =>  env('PASSPORT_CLIENT_SECRET'),
            'grant_type'    =>  env('PASSPORT_GRANT_TYPE'),
        ]);
        $data = (new DiactorosFactory)->createRequest( $request );
        return app( AccessTokenController::class )->issueToken($data);
    }

    /**
     * Return authenticated user.
     *
     * @return JsonResponse
     *
     */
    public function user()
    {
        $ldap = null;
        try {
            $ldap = $this->ldap->search()->findByGuid(auth('api')->user()->guid);
        } catch (\Exception $e) {}
        auth('api')->user()->ldap = $ldap ? $ldap : null;
        return $this->success_response( new UserResource( auth('api')->user() ), Response::HTTP_OK );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function changePassword(Request $request)
    {
        $this->validateChangePassword( $request );
        // Find authenticated user and change password
        $user = Adldap::search()->findByGuid(auth('api')->user()->guid);
        if ( $user instanceof \Adldap\Models\User) {
            try {
                if ( $user->changePassword($request->get('old_password'), $request->get('password')) ) {
                    auth()->user()->password = Hash::make( $request->get('password') );
                    auth()->user()->save();
                    return $this->success_message(
                        __('auth.password_changed'),
                        Response::HTTP_OK
                    );
                }
            } catch (UserPasswordIncorrectException $e) {
                return $this->error_response(
                    __('auth.failed'),
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            } catch (UserPasswordPolicyException $e) {
                return $this->error_response(
                    __('auth.password_policy'),
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            } catch (AdldapException $e) {
                return $this->error_response(
                    __('validation.handler.unexpected_failure'),
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
            }
        }
        return $this->error_response(
            __('auth.failed'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /**
     * Validate the user password change.
     *
     * @param Request $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateChangePassword(Request $request)
    {
        $request->validate([
            'old_password'  => 'required|string',
            'password'  => 'required|string|confirmed',
        ]);
    }
}
