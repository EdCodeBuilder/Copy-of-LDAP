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
                'icon'  =>  'mdi-account-multiple-plus',
                'title' =>  'Usuarios',
                'to'    =>  [ 'name' => 'user-admin' ],
                'exact' =>  true,
                'can'   =>  auth('api')->user()->isAn(Roles::ROLE_SUPER_ADMIN)
            ],
            [
                'icon'  =>  'mdi-view-dashboard',
                'title' =>  'Dashboard',
                'to'    =>  [ 'name' => 'home' ],
                'exact' =>  true,
                'can'   =>  auth('api')->user()->isAn(...Roles::all())
            ],
            [
                'icon'  =>  'mdi-domain',
                'title' =>  'Entidades',
                'to'    =>  [ 'name' => 'companies' ],
                'exact' =>  true,
                'can'   =>  auth('api')->user()->isAn(...Roles::all())
            ],
            [
                'icon'  =>  'mdi-briefcase-variant',
                'title' =>  'Portafolio',
                'to'    =>  [ 'name' => 'services' ],
                'exact' =>  true,
                'can'   =>  auth('api')->user()->isAn(...Roles::all())
            ],
            [
                'icon'  =>  'mdi-help-circle',
                'title' =>  'FAQ',
                'to'    =>  [ 'name' => 'faq' ],
                'exact' =>  true,
                'can'   =>  auth('api')->user()->isAn(Roles::ROLE_SUPER_ADMIN)
            ],
            [
                'icon'  =>  'mdi-magnify',
                'title' =>  'Auditoría',
                'to'    =>  [ 'name' => 'audit' ],
                'exact' =>  true,
                'can'   =>  auth('api')->user()->isAn(Roles::ROLE_SUPER_ADMIN)
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
        $users = User::when($request->has('username'), function ($query) use ($request) {
            $username = toLower( $request->get('username') );
            return $query->where('username', 'like', "%{$username}%")
                ->orWhere('name', 'like', "%{$username}%")
                ->orWhere('surname', 'like', "%{$username}%")
                ->orWhere('document', 'like', "%{$username}%");
        })
            ->take(50)
            ->get();
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
