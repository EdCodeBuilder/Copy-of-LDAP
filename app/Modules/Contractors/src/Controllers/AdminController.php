<?php


namespace App\Modules\Contractors\src\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Resources\Auth\RoleResource;
use App\Http\Resources\Auth\UserResource;
use App\Models\Security\User;
use App\Modules\Contractors\src\Constants\Roles;
use App\Modules\Contractors\src\Models\Contractor;
use App\Modules\Contractors\src\Models\ContractType;
use App\Modules\Contractors\src\Models\FileType;
use App\Modules\Contractors\src\Request\RoleRequest;
use App\Modules\Contractors\src\Resources\ContractorResource;
use App\Modules\Contractors\src\Resources\ContractTypeResource;
use App\Modules\Contractors\src\Resources\FileTypeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Silber\Bouncer\Database\Role;

class AdminController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
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
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }
}
