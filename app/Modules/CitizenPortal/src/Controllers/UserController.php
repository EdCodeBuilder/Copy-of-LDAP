<?php


namespace App\Modules\CitizenPortal\src\Controllers;


use Adldap\AdldapInterface;
use Adldap\Auth\BindException;
use App\Exceptions\PasswordExpiredException;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Auth\RoleResource;
use App\Http\Resources\Auth\UserResource;
use App\Models\Security\User;
use App\Modules\CitizenPortal\src\Constants\Roles;
use App\Modules\CitizenPortal\src\Request\FindUserRequest;
use App\Modules\CitizenPortal\src\Request\RoleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
     * @param Request $request
     * @return JsonResponse
     */
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
     * @return bool
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

    /**
     * @param User $user
     * @return bool
     */
    public function validatePermissions(User $user)
    {
        return $user->isA(...Roles::allAndRoot());
    }

    /**
     * Display a menu of the menu for current user.
     *
     * @return JsonResponse
     */
    public function menu()
    {
        $admins = Roles::onlyAdmin();
        $everybody = Roles::allAndRoot();
        $menu = collect([
            [
                'icon'  =>  'mdi-security',
                'title' =>  __('citizen.menu.roles'),
                'to'    =>  [ 'name' => 'roles-and-permissions' ],
                'exact' =>  true,
                'can'   =>  auth('api')->check() && auth('api')->user()->isA('superadmin'),
            ],
            [
                'icon'  =>  'mdi-account-multiple-plus',
                'title' =>  __('passport.menu.users'),
                'to'    =>  [ 'name' => 'user-admin' ],
                'exact' =>  true,
                'can'   =>  auth('api')->check() && auth('api')->user()->isAn(...$admins)
            ],
            [
                'icon'  =>  'mdi-form-dropdown',
                'title' =>  __('citizen.menu.data-management'),
                'exact' =>  false,
                'can'   =>  auth('api')->check() && auth('api')->user()->isA(...$admins),
                'children'  => [
                    [
                        'title' =>  __('citizen.menu.status'),
                        'to'    =>  [ 'name' => 'status' ],
                        'exact' =>  true,
                        'can'   =>  auth('api')->check() && auth('api')->user()->isA(...$admins)
                    ],
                    [
                        'title' =>  __('citizen.menu.stages'),
                        'to'    =>  [ 'name' => 'stages' ],
                        'exact' =>  true,
                        'can'   =>  auth('api')->check() && auth('api')->user()->isA(...$admins)
                    ],
                    [
                        'title' =>  __('citizen.menu.programs'),
                        'to'    =>  [ 'name' => 'programs' ],
                        'exact' =>  true,
                        'can'   =>  auth('api')->check() && auth('api')->user()->isA(...$admins)
                    ],
                    [
                        'title' =>  __('citizen.menu.activities'),
                        'to'    =>  [ 'name' => 'activities' ],
                        'exact' =>  true,
                        'can'   =>  auth('api')->check() && auth('api')->user()->isA(...$admins)
                    ],
                    [
                        'title' =>  __('citizen.menu.age_group'),
                        'to'    =>  [ 'name' => 'age-groups' ],
                        'exact' =>  true,
                        'can'   =>  auth('api')->check() && auth('api')->user()->isA(...$admins)
                    ],
                    [
                        'title' =>  __('citizen.menu.week_days'),
                        'to'    =>  [ 'name' => 'weekdays' ],
                        'exact' =>  true,
                        'can'   =>  auth('api')->check() && auth('api')->user()->isA(...$admins)
                    ],
                    [
                        'title' =>  __('citizen.menu.daily_hours'),
                        'to'    =>  [ 'name' => 'daily-hours' ],
                        'exact' =>  true,
                        'can'   =>  auth('api')->check() && auth('api')->user()->isA(...$admins)
                    ],
                    [
                        'title' =>  __('citizen.menu.file_types'),
                        'to'    =>  [ 'name' => 'file-types' ],
                        'exact' =>  true,
                        'can'   =>  auth('api')->check() && auth('api')->user()->isA(...$admins)
                    ],
                    [
                        'title' =>  __('citizen.menu.profile_types'),
                        'to'    =>  [ 'name' => 'profile-types' ],
                        'exact' =>  true,
                        'can'   =>  auth('api')->check() && auth('api')->user()->isA(...$admins)
                    ],
                ],
            ],
            [
                'icon'  =>  'mdi-view-dashboard',
                'title' =>  __('citizen.menu.dashboard'),
                'to'    =>  [ 'name' => 'home' ],
                'exact' =>  true,
                'can'   =>  auth('api')->check() && auth('api')->user()->isA(...$everybody),
            ],
            [
                'icon'  =>  'mdi-account-multiple',
                'title' =>  __('citizen.menu.user_validation'),
                'to'    =>  [ 'name' => 'user-validation' ],
                'exact' =>  true,
                'can'   =>  auth('api')->check() && auth('api')->user()->isA(...$everybody),
            ],
            [
                'icon'  =>  'mdi-calendar',
                'title' =>  __('citizen.menu.schedules'),
                'to'    =>  [ 'name' => 'schedules' ],
                'exact' =>  true,
                'can'   =>  auth('api')->check() && auth('api')->user()->isA(...$everybody)
            ],
            [
                'icon'  =>  'mdi-magnify',
                'title' =>  __('citizen.menu.audit'),
                'to'    =>  [ 'name' => 'audit' ],
                'exact' =>  true,
                'can'   =>  auth('api')->check() && auth('api')->user()->isA(...$admins),
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

    /**
     * @return JsonResponse
     */
    public function roles()
    {
        return $this->success_response(
            RoleResource::collection( Role::whereIn('name', Roles::all())->get() )
        );
    }

    /**
     * @param FindUserRequest $request
     * @return JsonResponse
     */
    public function findUsers(FindUserRequest $request)
    {
        $users = User::search($request->get('username'))->take(50)->get();
        return $this->success_response(
            UserResource::collection( $users )
        );
    }

    /**
     * @return JsonResponse
     */
    public function assignors()
    {
        $users = User::whereIs(Roles::ROLE_ASSIGNOR)->get();
        return $this->success_response(
            UserResource::collection( $users )
        );
    }

    /**
     * @return JsonResponse
     */
    public function validators()
    {
        $users = User::whereIs(Roles::ROLE_VALIDATOR)->get(['id', 'name', 'surname', 'document']);
        return $this->success_response(
            UserResource::collection( $users )
        );
    }

    /**
     * @param RoleRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function store(RoleRequest $request, User $user)
    {
        $user->assign( $request->get('roles') );
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    /**
     * @param RoleRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(RoleRequest $request, User $user)
    {
        $user->retract( $request->get('roles') );
        return $this->success_message(
            __('validation.handler.deleted')
        );
    }
}
