<?php


namespace App\Modules\Passport\src\Controllers;


use Adldap\AdldapInterface;
use Adldap\Auth\BindException;
use App\Exceptions\PasswordExpiredException;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Resources\Auth\RoleResource;
use App\Http\Resources\Auth\UserResource;
use App\Models\Security\User;
use App\Modules\Passport\src\Constants\Roles;
use App\Modules\Passport\src\Request\RoleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Silber\Bouncer\Database\Role;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;

class UserController extends LoginController
{
    /**
     * Initialise common request params
     *
     * @param AdldapInterface $ldap
     */
    public function __construct(AdldapInterface $ldap)
    {
        parent::__construct($ldap);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $users = User::whereIs(...Roles::all())
            ->with([
                'roles' => function ($query) {
                    return $query->whereIn('name', Roles::all());
                }
            ])
            ->get();
        return $this->success_response(
            UserResource::collection( $users )
        );
    }

    public function getToken(Request $request)
    {
        $user = User::where('username', $request->get('username'))->first();
        if (is_null($user)) {
            return $this->error_response(
                __('auth.failed'),
                Response::HTTP_UNAUTHORIZED
            );
        } else if ($this->validatePermissions($user) ) {
            $request->request->add([
                'client_id'     =>  env('PASSPORT_CLIENT_ID'),
                'client_secret' =>  env('PASSPORT_CLIENT_SECRET'),
                'grant_type'    =>  env('PASSPORT_GRANT_TYPE'),
            ]);
            $data = (new DiactorosFactory)->createRequest( $request );
            return app( AccessTokenController::class )->issueToken($data);
        } else {
            return $this->error_response(
                __('validation.handler.unauthorized'),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param Request $request
     * @return bool|JsonResponse
     * @throws PasswordExpiredException
     */
    protected function attemptLogin(Request $request)
    {
        try {
            return auth()->attempt($this->credentials($request), $request->get('remember'));
        } catch (BindException $e) {
            $user = User::active()->where('username', $request->get( $this->username() ))->first();
            if (is_null($user)) {
                return false;
            } else if ( $this->validatePermissions($user) ) {
                if ( $user->is_locked ) {
                    throw new PasswordExpiredException(trans('passwords.inactive'));
                }
                if ( $user->password_expired ) {
                    throw new PasswordExpiredException(trans('passwords.expired'));
                }
                return Hash::check($request->get('password'), $user->password);
            }
            return false;
        }
    }

    public function validatePermissions(User $user)
    {
        return $user->isA(...Roles::all());
    }

    /**
     * @return JsonResponse
     */
    public function drawer()
    {
        $menu = collect([
            [
                'icon'  =>  'mdi-security',
                'title' =>  __('passport.menu.admin'),
                'exact' =>  false,
                'can'   =>  auth('api')->user()->isAn(...Roles::all()),
                'children'  => [
                    [
                        'icon'  =>  'mdi-account-multiple-plus',
                        'title' =>  __('passport.menu.users'),
                        'to'    =>  [ 'name' => 'user-admin' ],
                        'exact' =>  true,
                        'can'   =>  auth('api')->user()->isAn(...Roles::all())
                    ],
                    [
                        'icon'  =>  'mdi-pine-tree',
                        'title' =>  __('passport.menu.activities'),
                        'to'    =>  [ 'name' => 'hobbies' ],
                        'exact' =>  true,
                        'can'   =>  auth('api')->user()->isAn(...Roles::all())
                    ],
                    [
                        'icon'  =>  'mdi-hospital-box',
                        'title' =>  __('passport.menu.eps'),
                        'to'    =>  [ 'name' => 'eps' ],
                        'exact' =>  true,
                        'can'   =>  auth('api')->user()->isAn(...Roles::all())
                    ],
                    [
                        'icon'  =>  'mdi-domain',
                        'title' =>  'SuperCades',
                        'to'    =>  [ 'name' => 'cades' ],
                        'exact' =>  true,
                        'can'   =>  auth('api')->user()->isAn(...Roles::all())
                    ],
                ]
            ],
            [
                'icon'  =>  'mdi-view-dashboard',
                'title' =>  __('passport.menu.dashboard'),
                'to'    =>  [ 'name' => 'home' ],
                'exact' =>  true,
                'can'   =>  auth('api')->user()->isAn(...Roles::all())
            ],
            [
                'icon'  =>  'mdi-format-float-left',
                'title' =>  __('passport.menu.page'),
                'to'    =>  [ 'name' => 'page' ],
                'exact' =>  true,
                'can'   =>  auth('api')->user()->isAn(...Roles::all())
            ],
            [
                'icon'  =>  'mdi-credit-card-search-outline',
                'title' =>  __('passport.menu.passports'),
                'to'    =>  [ 'name' => 'passports' ],
                'exact' =>  true,
                'can'   =>  auth('api')->user()->isAn(...Roles::all())
            ],
            [
                'icon'  =>  'mdi-autorenew',
                'title' =>  __('passport.menu.renewals'),
                'to'    =>  [ 'name' => 'renewals' ],
                'exact' =>  true,
                'can'   =>  auth('api')->user()->isAn(...Roles::all())
            ],
            [
                'icon'  =>  'mdi-printer',
                'title' =>  __('passport.menu.printer'),
                'to'    =>  [ 'name' => 'printer' ],
                'exact' =>  true,
                'can'   =>  auth('api')->user()->isAn(...Roles::all())
            ],
            [
                'icon'  =>  'mdi-card-account-details-star',
                'title' =>  __('passport.menu.card'),
                'to'    =>  [ 'name' => 'card' ],
                'exact' =>  true,
                'can'   =>  auth('api')->user()->isAn(...Roles::all())
            ],
            [
                'icon'  =>  'mdi-domain',
                'title' =>  __('passport.menu.companies'),
                'to'    =>  [ 'name' => 'companies' ],
                'exact' =>  true,
                'can'   =>  auth('api')->user()->isAn(...Roles::all())
            ],
            [
                'icon'  =>  'mdi-briefcase-variant',
                'title' =>  __('passport.menu.portfolio'),
                'to'    =>  [ 'name' => 'services' ],
                'exact' =>  true,
                'can'   =>  auth('api')->user()->isAn(...Roles::all())
            ],
            [
                'icon'  =>  'mdi-help-circle',
                'title' =>  __('passport.menu.faq'),
                'to'    =>  [ 'name' => 'faq' ],
                'exact' =>  true,
                'can'   =>  auth('api')->user()->isAn(...Roles::all())
            ],
            [
                'icon'  =>  'mdi-magnify',
                'title' =>  __('passport.menu.audit'),
                'to'    =>  [ 'name' => 'audit' ],
                'exact' =>  true,
                'can'   =>  auth('api')->user()->isAn(...Roles::all())
            ],
        ]);
        return $this->success_message( array_values( $menu->where('can', true)->toArray() ) );
    }

    /**
     * @return JsonResponse
     */
    public function permissions()
    {
        return $this->success_message(
            Roles::permissions()
        );
    }

    public function roles()
    {
        return $this->success_response(
            RoleResource::collection( Role::whereIn('name', Roles::all())->get() )
        );
    }

    public function findUsers(Request $request)
    {
        $users = User::search($request->get('username'))->take(50)->get();
        return $this->success_response(
            UserResource::collection( $users )
        );
    }

    public function store(RoleRequest $request, User $user)
    {
        $user->assign( $request->get('roles') );
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    public function destroy(RoleRequest $request, User $user)
    {
        $user->retract( $request->get('roles') );
        return $this->success_message(
            __('validation.handler.deleted')
        );
    }
}
