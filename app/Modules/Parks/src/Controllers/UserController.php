<?php

namespace App\Modules\Parks\src\Controllers;

use App\Http\Resources\Auth\RoleResource;
use App\Http\Resources\Auth\UserResource;
use App\Models\Security\User;
use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Request\RoleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
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
                'icon'  =>  'mdi-account-multiple-plus',
                'title' =>  __('parks.menu.users'),
                'to'    =>  [ 'name' => 'parks-users' ],
                'exact' =>  true,
                'can'   =>  auth()->check() && auth()->user()->can('manage-parks-users'),
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
                'can'   =>  auth()->check() && auth()->user()->can('manage-parks'),
                'children' => [
                    [
                        'title' =>  __('parks.menu.parks'),
                        'to'    =>  [ 'name' => 'parks-create' ],
                        'exact' =>  auth()->check() && auth()->user()->can('manage-parks'),
                    ],
                    [
                        'title' =>  __('parks.menu.locality'),
                        'to'    =>  [ 'name' => 'parks-locations' ],
                        'exact' =>  auth()->check() && auth()->user()->can('manage-parks'),
                    ],
                ]
            ],
            [
                'icon'  =>  'mdi-map',
                'title' =>  __('parks.menu.map'),
                'to'    =>  [ 'name' => 'parks-map' ],
                'exact' =>  true,
                'can'   =>  true,
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
        $users = User::query()
            ->when($request->has('username'), function ($query) use ($request) {
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
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }
}
