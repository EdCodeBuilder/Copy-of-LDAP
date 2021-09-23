<?php

namespace App\Modules\Parks\src\Controllers;

use App\Http\Resources\Auth\RoleResource;
use App\Http\Resources\Auth\UserResource;
use App\Models\Security\User;
use App\Modules\CitizenPortal\src\Models\Stage;
use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\AssignedPark;
use App\Modules\Parks\src\Models\Certified;
use App\Modules\Parks\src\Models\Enclosure;
use App\Modules\Parks\src\Models\Location;
use App\Modules\Parks\src\Models\Neighborhood;
use App\Modules\Parks\src\Models\Park;
use App\Modules\Parks\src\Models\Scale;
use App\Modules\Parks\src\Models\StageType;
use App\Modules\Parks\src\Models\Status;
use App\Modules\Parks\src\Models\Upz;
use App\Modules\Parks\src\Models\UpzType;
use App\Modules\Parks\src\Models\Vocation;
use App\Modules\Parks\src\Request\FindUserRequest;
use App\Modules\Parks\src\Request\RoleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use OwenIt\Auditing\Models\Audit;
use Silber\Bouncer\BouncerFacade;
use Silber\Bouncer\Database\Role;

/**
 * @group Parques - Gestión de Usuarios
 *
 * Api para la gestión de usuarios en el módulo de parques.
 */
class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(Roles::actions(User::class, 'view_or_manage'))->only('index', 'findUsers', 'roles');
        $this->middleware(Roles::actions(User::class, 'create_or_manage'))->only('store');
        $this->middleware(Roles::actions(User::class, 'destroy_or_manage'))->only('destroy');
    }

    /**
     * @group Parques - Gestión de Usuarios
     *
     * Menú
     *
     * Despliega el menú dinámico dependendo de los permisos asignados al usuario.
     *
     * @authenticated
     * @response {
     *  "data": [{ "icon": "mdi-view-dashboard", "title": "Dashboard", "to": { "name": "dashboard" }, "exact": true, "can": true}],
     *  "details": null,
     *  "code": 200,
     *  "requested_at": "2021-09-12T16:45:59"
     * }
     *
     * @return JsonResponse
     */
    public function menu()
    {
        $manageActions = ['manage', 'view', 'create', 'update', 'destroy'];
        $superAdmin = auth('api')->user()->isA('superadmin');
        $user = auth('api')->user();
        $menu = collect([
            [
                'icon'  =>  'mdi-security',
                'title' =>  __('parks.menu.roles'),
                'to'    =>  [ 'name' => 'parks-roles-and-permissions' ],
                'exact' =>  true,
                'can'   =>  $superAdmin,
            ],
            [
                'icon'  =>  'mdi-account-multiple-plus',
                'title' =>  __('parks.menu.users'),
                'to'    =>  [ 'name' => 'parks-users' ],
                'exact' =>  true,
                'can'   => $user->hasAnyPermission(
                        Roles::can( User::class, 'view_or_manage', true)
                    ) || $superAdmin,
            ],
            [
                'icon'  =>  'mdi-view-dashboard',
                'title' =>  __('parks.menu.dashboard'),
                'to'    =>  [ 'name' => 'parks' ],
                'exact' =>  true,
                'can'   =>  true,
            ],
            [
                'icon'  =>  'mdi-magnify-scan',
                'title' =>  __('parks.menu.finder'),
                'to'    =>  [ 'name' => 'parks-finder' ],
                'exact' =>  false,
                'can'   =>  true,
            ],
            [
                'icon'  =>  'mdi-clipboard-list-outline',
                'title' =>  __('parks.menu.manage'),
                'exact' =>  false,
                'can'   => $user->hasAnyPermission(
                    Roles::canAny(
                        [
                            ['actions'   => 'create_or_manage', 'model'   => Park::class],
                            ['actions'   => 'view_or_manage', 'model'     => Location::class],
                            ['actions'   => 'view_or_manage', 'model'     => Upz::class],
                            ['actions'   => 'view_or_manage', 'model'     => Neighborhood::class],
                            ['actions'   => 'view_or_manage', 'model'     => Enclosure::class],
                            ['actions'   => 'view_or_manage', 'model'     => Scale::class],
                            ['actions'   => 'view_or_manage', 'model'     => StageType::class],
                            ['actions'   => 'view_or_manage', 'model'     => Vocation::class],
                            ['actions'   => 'view_or_manage', 'model'     => Status::class],
                            ['actions'   => 'view_or_manage', 'model'     => UpzType::class],
                            ['actions'   => 'view_or_manage', 'model'     => Certified::class],
                        ], false, true)
                    ) || $user->isA(Roles::ROLE_ASSIGNED) || $superAdmin,
                'children' => array_values( collect([
                    [
                        'title' =>  __('parks.menu.owned'),
                        'to'    =>  [ 'name' => 'parks-owned' ],
                        'exact' =>  true,
                        'can' =>  $user->isA(Roles::ROLE_ASSIGNED),
                    ],
                    [
                        'title' =>  __('parks.menu.parks'),
                        'to'    =>  [ 'name' => 'parks-create' ],
                        'exact' =>  true,
                        'can' =>  $user->hasAnyPermission(Roles::can(Park::class, 'create_or_manage', true)) || $superAdmin,
                    ],
                    [
                        'title' =>  __('parks.menu.locality'),
                        'to'    =>  [ 'name' => 'parks-manage-locations' ],
                        'exact' =>  true,
                        'can' =>  $user->hasAnyPermission(Roles::canAny(
                            [
                                [
                                    'actions'   => $manageActions,
                                    'model'     => Location::class
                                ],
                                [
                                    'actions'   => $manageActions,
                                    'model'     => Upz::class
                                ],
                                [
                                    'actions'   => $manageActions,
                                    'model'     => Neighborhood::class
                                ]
                            ], false, true
                            )) || $superAdmin,
                    ],
                    [
                        'title' =>  __('parks.menu.enclosure'),
                        'to'    =>  [ 'name' => 'parks-manage-enclosure' ],
                        'exact' =>  true,
                        'can' =>  $user->hasAnyPermission(Roles::can(Enclosure::class, 'view_or_manage', true)) || $superAdmin,
                    ],
                    [
                        'title' =>  __('parks.menu.scales'),
                        'to'    =>  [ 'name' => 'parks-manage-scales' ],
                        'exact' =>  true,
                        'can' =>  $user->hasAnyPermission(Roles::can(Scale::class, 'view_or_manage', true)) || $superAdmin,
                    ],
                    [
                        'title' =>  __('parks.menu.stages'),
                        'to'    =>  [ 'name' => 'parks-manage-stages' ],
                        'exact' =>  true,
                        'can' =>  $user->hasAnyPermission(Roles::can(StageType::class, 'view_or_manage', true)) || $superAdmin,
                    ],
                    [
                        'title' =>  __('parks.menu.vocation'),
                        'to'    =>  [ 'name' => 'parks-manage-vocations' ],
                        'exact' =>  true,
                        'can' =>  $user->hasAnyPermission(Roles::can(Vocation::class, 'view_or_manage', true)) || $superAdmin,
                    ],
                    [
                        'title' =>  __('parks.menu.status'),
                        'to'    =>  [ 'name' => 'parks-manage-status' ],
                        'exact' =>  true,
                        'can' =>  $user->hasAnyPermission(Roles::can(Status::class, 'view_or_manage', true)) || $superAdmin,
                    ],
                    [
                        'title' =>  __('parks.menu.upz_types'),
                        'to'    =>  [ 'name' => 'parks-manage-upz-types' ],
                        'exact' =>  true,
                        'can' =>  $user->hasAnyPermission(Roles::can(UpzType::class, 'view_or_manage', true)) || $superAdmin,
                    ],
                    [
                        'title' =>  __('parks.menu.certified'),
                        'to'    =>  [ 'name' => 'parks-manage-certificate-status' ],
                        'exact' =>  true,
                        'can' =>  $user->hasAnyPermission(Roles::can(Certified::class, 'view_or_manage', true)) || $superAdmin,
                    ],
                ])->where('can', true)->toArray())
            ],
            [
                'icon'  =>  'mdi-map',
                'title' =>  __('parks.menu.map'),
                'to'    =>  [ 'name' => 'parks-map' ],
                'exact' =>  true,
                'can'   =>  true,
            ],
            [
                'icon'  =>  'mdi-magnify',
                'title' =>  __('parks.menu.audit'),
                'to'    =>  [ 'name' => 'parks-audit' ],
                'exact' =>  true,
                'can'   =>  $user->hasAnyPermission(Roles::can(Audit::class, 'view', true)) || $superAdmin,
            ],
        ]);

        return $this->success_message( array_values( $menu->where('can', true)->toArray() ) );
    }

    /**
     * @group Parques - Gestión de Usuarios
     *
     * Permisos
     *
     * Despliega el listado de permisos asociados al usuario y módulo autenticado.
     *
     * @authenticated
     * @response {
     *  "data": {"id": 1, "abilities": [], "roles": [] },
     *  "details": null,
     *  "code": 200,
     *  "requested_at": "2021-09-12T16:45:59"
     * }
     *
     * @return JsonResponse
     */
    public function permissions()
    {
        return $this->success_message(
            Roles::permissions()
        );
    }

    /**
     * @group Parques - Gestión de Usuarios
     *
     * Usuarios
     *
     * Muestra el listado de usuarios asociados al módulo de parques.
     *
     * @authenticated
     * @responseFile responses/users.json
     *
     * @return JsonResponse
     */
    public function index()
    {
        $users = User::whereIs(...Roles::roles())
            ->with([
                'roles' => function ($query) {
                    return $query->whereIn('name', Roles::roles());
                }
            ])
            ->get();
        return $this->success_response(
            UserResource::collection( $users )
        );
    }

    /**
     * @group Parques - Gestión de Usuarios
     *
     * Roles
     *
     * Muestra el listado de roles asociados al módulo.
     * @authenticated
     * @responseFile responses/roles.json
     *
     * @return JsonResponse
     */
    public function roles()
    {
        return $this->success_response(
            RoleResource::collection( Role::whereIn('name', Roles::roles())->get() )
        );
    }

    /**
     * @group Parques - Gestión de Usuarios
     *
     * Buscador de usuarios
     *
     * Muestra un listado de coincidencias según los parámetros establecidos.
     *
     * @authenticated
     * @responseFile responses/users.json
     *
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
     * @group Parques - Gestión de Usuarios
     *
     * Asignación de Roles
     *
     * Asigna un rol o varios roles especificados a un usuario.
     *
     * @urlParam user int required Id del usuario a asignar roles.
     *
     * @authenticated
     * @response 201 {
     *      "data": "Datos almacenados satisfactoriamente",
     *      "details": null,
     *      "code": 201,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
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
     * @group Parques - Gestión de Usuarios
     *
     * Eliminación de Roles
     *
     * Elimina un rol o roles asociados a un usuario especificado..
     *
     * @urlParam user int required Id del usuario a asignar roles.
     *
     * @authenticated
     * @response {
     *      "data": "Datos eliminados satisfactoriamente",
     *      "details": null,
     *      "code": 204,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
     * @param RoleRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(RoleRequest $request, User $user)
    {
        $user->retract( $request->get('roles') );
        if (in_array(Roles::ROLE_ASSIGNED, $request->get('roles'))) {
            $parks = AssignedPark::where('user_id', $user->id)->get();
            foreach ($parks as $park) {
                $p = Park::find($park->park_id);
                $user->disallow(Roles::can(Park::class, 'update'), $p);
            }
            AssignedPark::where('user_id', $user->id)->delete();
        }
        return $this->success_message(
            __('validation.handler.deleted')
        );
    }
}
