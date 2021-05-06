<?php


namespace App\Modules\Contractors\src\Controllers;


use Adldap\AdldapInterface;
use Adldap\Auth\BindException;
use App\Exceptions\PasswordExpiredException;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Controller;
use App\Models\Security\User;
use App\Modules\Contractors\src\Constants\Roles;
use App\Modules\Contractors\src\Models\WareHouse;
use App\Modules\Contractors\src\Request\LoginRequest;
use App\Modules\Contractors\src\Resources\WareHouseResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Silber\Bouncer\Database\Role;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;

class PayrollController extends LoginController
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
                'can'   =>  auth('api')->user()->isAn(Roles::ROLE_ADMIN)
            ],
            [
                'icon'  =>  'mdi-view-dashboard',
                'title' =>  'Dashboard',
                'to'    =>  [ 'name' => 'index' ],
                'exact' =>  true,
                'can'   =>  auth('api')->user()->isAn(...Roles::all())
            ],
            [
                'icon'  =>  'mdi-account-check',
                'title' =>  'Contratistas',
                'to'    =>  [ 'name' => 'contractors' ],
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
        return $this->success_message([
            ['name'  =>  'contractors-portal-super-admin', 'can' => auth('api')->user()->isA(Roles::ROLE_ADMIN)],
            ['name'  =>  'contractors-portal-arl'        , 'can' => auth('api')->user()->isA(Roles::ROLE_ARL)],
            ['name'  =>  'contractors-portal-hiring'     , 'can' => auth('api')->user()->isA(Roles::ROLE_HIRING)],
            ['name'  =>  'contractors-portal-legal'      , 'can' => auth('api')->user()->isA(Roles::ROLE_LEGAL)],
            ['name'  =>  'contractors-portal-rp'         , 'can' => auth('api')->user()->isA(Roles::ROLE_RP)],
            ['name'  =>  'contractors-portal-third-party', 'can' => auth('api')->user()->isA(Roles::ROLE_THIRD_PARTY)],
            ['name'  =>  'contractors-portal-observer'   , 'can' => auth('api')->user()->isA(Roles::ROLE_OBSERVER)],
        ]);
    }

    public function oracle(Request $request)
    {
        $data = WareHouse::query()
                ->when($request->has('document'), function ($query) use ($request) {
                  return $query->where('ter_carg', $request->get('document'));
                })->paginate($this->per_page);
        return  $this->success_response(
            WareHouseResource::collection( $data )
        );
    }
}
