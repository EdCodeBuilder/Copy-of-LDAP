<?php

namespace App\Modules\Parks\src\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StorePermissionRequest;
use App\Http\Requests\Auth\UpdatePermissionRequest;
use App\Http\Resources\Auth\AbilityResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Silber\Bouncer\BouncerFacade;
use Silber\Bouncer\Database\Ability;
use Silber\Bouncer\Database\Role;

class RolePermissionsController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Role $role
     * @return JsonResponse
     */
    public function index(Role $role)
    {
        return $this->success_response(
            AbilityResource::collection( $role->abilities )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Role $role
     * @param Ability $permission
     * @return JsonResponse
     */
    public function update(Role $role, Ability $permission)
    {
        BouncerFacade::allow($role)->to($permission);
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     * @param Ability $permission
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Role $role, Ability $permission)
    {
        BouncerFacade::disallow($role)->to($permission);
        return $this->success_message(__('validation.handler.deleted'));
    }
}
