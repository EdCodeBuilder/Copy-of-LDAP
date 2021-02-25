<?php

namespace App\Modules\Parks\src\Controllers;

use App\Http\Resources\Auth\RoleResource;
use App\Http\Resources\Auth\UserResource;
use App\Models\Security\User;
use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\AssignedPark;
use App\Modules\Parks\src\Models\Enclosure;
use App\Modules\Parks\src\Models\Location;
use App\Modules\Parks\src\Models\Park;
use App\Modules\Parks\src\Models\Scale;
use App\Modules\Parks\src\Models\StageType;
use App\Modules\Parks\src\Models\Vocation;
use App\Modules\Parks\src\Request\RoleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Silber\Bouncer\BouncerFacade;
use Silber\Bouncer\Database\Role;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a menu of the menu for current user.
     *
     * @return JsonResponse
     */
    public function menu()
    {
        $menu = collect([
            [
                'icon'  =>  'mdi-security',
                'title' =>  __('parks.menu.roles'),
                'to'    =>  [ 'name' => 'parks-roles-and-permissions' ],
                'exact' =>  true,
                'can'   =>  auth('api')->check() && auth('api')->user()->isA('superadmin'),
            ],
            [
                'icon'  =>  'mdi-account-multiple-plus',
                'title' =>  __('parks.menu.users'),
                'to'    =>  [ 'name' => 'parks-users' ],
                'exact' =>  true,
                'can'   =>  auth('api')->check() && auth('api')->user()->can('manage-users-parks', Park::class),
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
                'exact' =>  true,
                'can'   =>  true,
            ],
            [
                'icon'  =>  'mdi-clipboard-list-outline',
                'title' =>  __('parks.menu.manage'),
                'exact' =>  false,
                'can'   => auth('api')->check() && (auth('api')->user()->can('manage-parks', Park::class) || auth('api')->user()->isA('park-assigned-parks')),
                'children' => array_values( collect([
                    [
                        'title' =>  __('parks.menu.owned'),
                        'to'    =>  [ 'name' => 'parks-owned' ],
                        'exact' =>  true,
                        'can' =>  auth('api')->check() && auth('api')->user()->isA('park-assigned-parks'),
                    ],
                    [
                        'title' =>  __('parks.menu.parks'),
                        'to'    =>  [ 'name' => 'parks-create' ],
                        'exact' =>  true,
                        'can' =>  auth('api')->check() && auth('api')->user()->can('manage-parks', Park::class),
                    ],
                    [
                        'title' =>  __('parks.menu.locality'),
                        'to'    =>  [ 'name' => 'parks-manage-locations' ],
                        'exact' =>  true,
                        'can' =>  auth('api')->check() && auth('api')->user()->can('manage-localities-parks', Location::class),
                    ],


                    [
                        'title' =>  __('parks.menu.enclosure'),
                        'to'    =>  [ 'name' => 'parks-manage-enclosure' ],
                        'exact' =>  true,
                        'can' =>  auth('api')->check() && auth('api')->user()->can('manage-enclosures-parks', Enclosure::class),
                    ],
                    [
                        'title' =>  __('parks.menu.scales'),
                        'to'    =>  [ 'name' => 'parks-manage-scales' ],
                        'exact' =>  true,
                        'can' =>  auth('api')->check() && auth('api')->user()->can('manage-scales-parks', Scale::class),
                    ],
                    [
                        'title' =>  __('parks.menu.stages'),
                        'to'    =>  [ 'name' => 'parks-manage-stages' ],
                        'exact' =>  true,
                        'can' =>  auth('api')->check() && auth('api')->user()->can('manage-stages-parks', StageType::class),
                    ],
                    [
                        'title' =>  __('parks.menu.vocation'),
                        'to'    =>  [ 'name' => 'parks-manage-vocations' ],
                        'exact' =>  true,
                        'can' =>  auth('api')->check() && auth('api')->user()->can('manage-vocations-parks', Vocation::class),
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
                'can'   =>  auth('api')->check() && auth()->user()->isA(...['park-administrator', 'superadmin']),
            ],
        ]);

        return $this->success_message( array_values( $menu->where('can', true)->toArray() ) );
    }

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

    public function roles()
    {
        return $this->success_response(
            RoleResource::collection( Role::whereIn('name', Roles::roles())->get() )
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
        if (in_array(Roles::ROLE_ASSIGNED, $request->get('roles'))) {
            $parks = AssignedPark::where('user_id', $user->id)->get();
            foreach ($parks as $park) {
                $p = Park::find($park->park_id);
                $user->disallow('manage-assigned-parks', $p);
            }
            AssignedPark::where('user_id', $user->id)->delete();
        }
        return $this->success_message(
            __('validation.handler.deleted')
        );
    }
}
