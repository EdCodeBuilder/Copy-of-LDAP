<?php

namespace App\Modules\Parks\src\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreRoleRequest;
use App\Http\Requests\Auth\UpdateRoleRequest;
use App\Http\Resources\Auth\RoleResource;
use App\Models\Security\User;
use App\Modules\Parks\src\Constants\Roles;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Silber\Bouncer\BouncerFacade;
use Silber\Bouncer\Database\Role;

/**
 * @group Parques - Roles
 *
 * Api para la gestión de roles en el módulo de parques.
 */
class RoleController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @group Parques - Roles
     *
     * Roles
     *
     * Muestra un listado de roles asociados al módulo.
     * @authenticated
     * @responseFile responses/roles.json
     *
     * @return JsonResponse
     */
    public function index()
    {
        $roles = Role::query()->where('name', 'like', 'park-%')->get();
        return $this->success_response(
            RoleResource::collection( $roles )
        );
    }

    /**
     * @group Parques - Roles
     *
     * Crear Roles
     *
     * Almacena un recurso recién creado en la base de datos.
     * @authenticated
     * @response 201 {
     *      "data": "Datos almacenados satisfactoriamente",
     *      "details": null,
     *      "code": 201,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
     * @param StoreRoleRequest $request
     * @return JsonResponse
     */
    public function store(StoreRoleRequest $request)
    {
        $name = substr( toLower($request->get('name')), 0, 5 ) === Roles::IDENTIFIER."-"
            ? toLower($request->get('name'))
            : toLower(Roles::IDENTIFIER."-{$request->get('name')}");
        BouncerFacade::role()->firstOrCreate([
            'name'  =>  $name,
            'title' =>  $request->get('title')
        ]);
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    /**
     * @group Parques - Roles
     *
     * Actualizar Roles
     *
     * Actualiza el recurso especificado en la base de datos.
     *
     * @urlParam role int Id del permiso a actualizar. Example: 3
     *
     * @authenticated
     * @response {
     *      "data": "Datos actualizados satisfactoriamente",
     *      "details": null,
     *      "code": 200,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
     * @param UpdateRoleRequest $request
     * @param Role $role
     * @return JsonResponse
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $name = substr( toLower($request->get('name')), 0, 5 ) === Roles::IDENTIFIER."-"
                ? toLower($request->get('name'))
                : toLower(Roles::IDENTIFIER."-{$request->get('name')}");
        $role->fill([
            'name'  =>  $name,
            'title' =>  $request->get('title')
        ]);
        $role->save();
        return $this->success_message(__('validation.handler.updated'));
    }

    /**
     * @group Parques - Roles
     *
     * Eliminar Roles
     *
     * Elimina el recurso especificado en la base de datos.
     *
     * @urlParam role int required Id del tipo de escenario: Example: 1
     * @authenticated
     * @response {
     *      "data": "Datos eliminados satisfactoriamente",
     *      "details": null,
     *      "code": 204,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
     * @param Role $role
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Role $role)
    {
        User::whereIs($role->name)->chunk(100, function ($users) use ($role) {
           foreach ($users as $user) {
               $user->retract($role->name);
           }
        });
        $role->delete();
        return $this->success_message(__('validation.handler.deleted'));
    }
}
