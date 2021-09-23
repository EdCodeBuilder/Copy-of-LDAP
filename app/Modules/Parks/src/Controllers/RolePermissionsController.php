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

/**
 * @group Parques - Roles y Permisos
 *
 * Api para la asociación de roles y permisos.
 */
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
     * @group Parques - Roles y Permisos
     *
     * Roles y Permisos
     *
     * Muestra el listado de permisos asociados a un rol.
     *
     * @urlParam role int required Id del rol. Example 1
     *
     * @authenticated
     * @responseFile responses/roles_and_permissions.json
     *
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
     * @group Parques - Roles y Permisos
     *
     * Asociar Rol a Permisos
     *
     * Asocia un rol a un permiso específico
     *
     * @urlParam role int required Id del rol. Example 1
     * @urlParam permission int required Id del permiso. Example 2
     *
     * @authenticated
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
     * @group Parques - Roles y Permisos
     *
     * Desasociar Rol a Permisos
     *
     * Elimina un permiso de un rol especificado
     *
     * @urlParam role int required Id del rol. Example 1
     * @urlParam permission int required Id del permiso. Example 2
     * @authenticated
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
