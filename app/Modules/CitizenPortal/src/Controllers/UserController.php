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
use App\Modules\CitizenPortal\src\Models\Activity;
use App\Modules\CitizenPortal\src\Models\AgeGroup;
use App\Modules\CitizenPortal\src\Models\CitizenSchedule;
use App\Modules\CitizenPortal\src\Models\Day;
use App\Modules\CitizenPortal\src\Models\File;
use App\Modules\CitizenPortal\src\Models\FileType;
use App\Modules\CitizenPortal\src\Models\Hour;
use App\Modules\CitizenPortal\src\Models\Profile;
use App\Modules\CitizenPortal\src\Models\ProfileType;
use App\Modules\CitizenPortal\src\Models\Program;
use App\Modules\CitizenPortal\src\Models\Schedule;
use App\Modules\CitizenPortal\src\Models\Stage;
use App\Modules\CitizenPortal\src\Models\Status;
use App\Modules\CitizenPortal\src\Request\FindUserRequest;
use App\Modules\CitizenPortal\src\Request\RoleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use OwenIt\Auditing\Models\Audit;
use Silber\Bouncer\BouncerFacade;
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
        $this->middleware(Roles::actions(User::class, 'view_or_manage'))->only('index', 'findUsers', 'roles');
        $this->middleware(Roles::actions(User::class, 'create_or_manage'))->only('store');
        $this->middleware(Roles::actions(User::class, 'destroy_or_manage'))->only('destroy');
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
        $manageActions = ['manage', 'view', 'create', 'update', 'destroy'];
        $isAuth = auth('api')->check();
        $superAdmin = auth('api')->user()->isA('superadmin');
        $menu = collect([
            [
                'icon'  =>  'mdi-security',
                'title' =>  __('citizen.menu.roles'),
                'to'    =>  [ 'name' => 'roles-and-permissions' ],
                'exact' =>  true,
                'can'   =>  $isAuth && $superAdmin,
            ],
            [
                'icon'  =>  'mdi-account-multiple-plus',
                'title' =>  __('passport.menu.users'),
                'to'    =>  [ 'name' => 'user-admin' ],
                'exact' =>  true,
                'can'   =>  Roles::authCan(
                    ['manage', 'view', 'create', 'update', 'destroy'],
                    User::class,
                    'or'
                ) || $superAdmin
            ],
            [
                'icon'  =>  'mdi-form-dropdown',
                'title' =>  __('citizen.menu.data-management'),
                'exact' =>  true,
                'can'   =>  auth('api')->user()->hasAnyPermission(
                    Roles::canAny(
                        [
                            [
                                'actions'   => $manageActions,
                                'model'     => Status::class
                            ],
                            [
                                'actions'   => $manageActions,
                                'model'     => Stage::class
                            ],
                            [
                                'actions'   => $manageActions,
                                'model'     => Program::class
                            ],
                            [
                                'actions'   => $manageActions,
                                'model'     => Activity::class
                            ],
                            [
                                'actions'   => $manageActions,
                                'model'     => AgeGroup::class
                            ],
                            [
                                'actions'   => $manageActions,
                                'model'     => Day::class
                            ],
                            [
                                'actions'   => $manageActions,
                                'model'     => Hour::class
                            ],
                            [
                                'actions'   => $manageActions,
                                'model'     => FileType::class
                            ],
                            [
                                'actions'   => $manageActions,
                                'model'     => ProfileType::class
                            ],
                        ],
                        false,
                        true
                    )
                ) || $superAdmin,
                'children'  => array_values(
                    collect([
                        [
                            'title' =>  __('citizen.menu.status'),
                            'to'    =>  [ 'name' => 'status' ],
                            'exact' =>  false,
                            'can'   =>  Roles::authCan(
                                $manageActions,
                                Status::class,
                                'or'
                            ) || $superAdmin,
                        ],
                        [
                            'title' =>  __('citizen.menu.stages'),
                            'to'    =>  [ 'name' => 'stages' ],
                            'exact' =>  false,
                            'can'   =>  Roles::authCan(
                                $manageActions,
                                Stage::class,
                                'or'
                            ) || $superAdmin,
                        ],
                        [
                            'title' =>  __('citizen.menu.programs'),
                            'to'    =>  [ 'name' => 'programs' ],
                            'exact' =>  false,
                            'can'   =>  Roles::authCan(
                                $manageActions,
                                Program::class,
                                'or'
                            ) || $superAdmin,
                        ],
                        [
                            'title' =>  __('citizen.menu.activities'),
                            'to'    =>  [ 'name' => 'activities' ],
                            'exact' =>  false,
                            'can'   =>  Roles::authCan(
                                $manageActions,
                                Activity::class,
                                'or'
                            ) || $superAdmin,
                        ],
                        [
                            'title' =>  __('citizen.menu.age_group'),
                            'to'    =>  [ 'name' => 'age-groups' ],
                            'exact' =>  false,
                            'can'   =>  Roles::authCan(
                                $manageActions,
                                AgeGroup::class,
                                'or'
                            ) || $superAdmin,
                        ],
                        [
                            'title' =>  __('citizen.menu.week_days'),
                            'to'    =>  [ 'name' => 'weekdays' ],
                            'exact' =>  false,
                            'can'   =>  Roles::authCan(
                                $manageActions,
                                Day::class,
                                'or'
                            ) || $superAdmin,
                        ],
                        [
                            'title' =>  __('citizen.menu.daily_hours'),
                            'to'    =>  [ 'name' => 'daily-hours' ],
                            'exact' =>  false,
                            'can'   =>  Roles::authCan(
                                $manageActions,
                                Hour::class,
                                'or'
                            ) || $superAdmin,
                        ],
                        [
                            'title' =>  __('citizen.menu.file_types'),
                            'to'    =>  [ 'name' => 'file-types' ],
                            'exact' =>  false,
                            'can'   =>  Roles::authCan(
                                $manageActions,
                                FileType::class,
                                'or'
                            ) || $superAdmin,
                        ],
                        [
                            'title' =>  __('citizen.menu.profile_types'),
                            'to'    =>  [ 'name' => 'profile-types' ],
                            'exact' =>  false,
                            'can'   =>  Roles::authCan(
                                $manageActions,
                                ProfileType::class,
                                'or'
                            ) || $superAdmin,
                        ],
                    ])->where('can', true)->toArray()
                ),
            ],
            [
                'icon'  =>  'mdi-view-dashboard',
                'title' =>  __('citizen.menu.dashboard'),
                'to'    =>  [ 'name' => 'home' ],
                'exact' =>  true,
                'can'   =>  true,
            ],
            [
                'icon'  =>  'mdi-account-multiple',
                'title' =>  __('citizen.menu.user_validation'),
                'to'    =>  [ 'name' => 'user-validation' ],
                'exact' =>  false,
                'can'   => auth('api')->user()->hasAnyPermission(
                    Roles::canAny(
                        [
                            [
                                'actions'   => array_merge($manageActions, ['status', 'validator']),
                                'model'     => Profile::class
                            ],
                            [
                                'actions'   => array_merge($manageActions, ['status']),
                                'model'     => File::class
                            ],
                        ],
                        false,
                        true
                    )
                ) || $superAdmin,
            ],
            [
                'icon'  =>  'mdi-calendar',
                'title' =>  __('citizen.menu.schedules'),
                'to'    =>  [ 'name' => 'schedules' ],
                'exact' =>  false,
                'can'   => auth('api')->user()->hasAnyPermission(
                    Roles::canAny(
                        [
                            [
                                'actions'   => $manageActions,
                                'model'     => Schedule::class
                            ],
                            [
                                'actions'   => array_merge($manageActions, ['status']),
                                'model'     => CitizenSchedule::class
                            ],
                        ],
                        true,
                        true
                    )
                )  || $superAdmin
            ],
            [
                'icon'  =>  'mdi-magnify',
                'title' =>  __('citizen.menu.audit'),
                'to'    =>  [ 'name' => 'audit' ],
                'exact' =>  true,
                'can'   =>  Roles::authCan(
                            ['view'],
                            Audit::class,
                            'or'
                        )  || $superAdmin,
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
     * @throws \Exception
     */
    public function store(RoleRequest $request, User $user)
    {
        BouncerFacade::refresh();
        cache()->flush();
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
     * @throws \Exception
     */
    public function destroy(RoleRequest $request, User $user)
    {
        BouncerFacade::refresh();
        cache()->flush();
        $user->retract( $request->get('roles') );
        return $this->success_message(
            __('validation.handler.deleted')
        );
    }
}
