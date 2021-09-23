<?php

namespace App\Modules\Parks\src\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StorePermissionRequest;
use App\Http\Requests\Auth\UpdatePermissionRequest;
use App\Http\Resources\Auth\AbilityResource;
use App\Models\Security\User;
use App\Modules\Parks\src\Constants\Roles;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use OwenIt\Auditing\Models\Audit;
use Silber\Bouncer\BouncerFacade;
use Silber\Bouncer\Database\Ability;

/**
 * @group Parques - Permisos
 *
 * Api para la gestión de permisos en el módulo de parques.
 */
class PermissionsController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @group Parques - Permisos
     *
     * Permisos
     *
     * Muestra un listado de permisos asociados al módulo.
     * @authenticated
     * @responseFile responses/permissions.json
     *
     * @return JsonResponse
     */
    public function index()
    {
        $abilities = Ability::query()
            ->where('name', 'like', '%-'.Roles::IDENTIFIER)
            ->whereNull('entity_id')
            ->get();
        return $this->success_response(
            AbilityResource::collection( $abilities )
        );
    }

    /**
     * @group Parques - Modelos
     *
     * Modelos
     *
     * Muestra un listado de entidades del módulo al cual se le asociarán permisos.
     * @authenticated
     * @responseFile responses/models.json
     *
     * @return JsonResponse
     */
    public function models()
    {
        return $this->success_message(
            $this->getModels(app_path('/Modules/Parks/src/Models'))
        );
    }

    /**
     * Obtiene el listado de modelos del módulo.
     *
     * @param $path
     * @return \Illuminate\Support\Collection|\Tightenco\Collect\Support\Collection
     */
    private function getModels($path) {
        $models = collect(File::allFiles($path))
            ->map(function ($item) {
                $path = $item->getRelativePathName();
                $class = sprintf('%s%s',
                    'App\Modules\Parks\src\Models\\',
                    strtr(substr($path, 0, strrpos($path, '.')), '/', '\\'));
                return [
                    'id'    => $class,
                    'name'  => __("parks.classes.{$class}")
                ];
            })
            ->filter(function ($item) {
               return $item['name'] != '';
            });

        return $models->merge([ ['id' => User::class, 'name' => __("parks.classes.".User::class)], ['id' => Audit::class, 'name' => __("parks.classes.".Audit::class)], ])->values();
    }

    /**
     * @group Parques - Permisos
     *
     * Crear Permisos
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
     * @param StorePermissionRequest $request
     * @return JsonResponse
     */
    public function store(StorePermissionRequest $request)
    {
        $name = explode('-', toLower($request->get('name')));
        $name = end($name);
        $name = $name == Roles::IDENTIFIER
            ? toLower($request->get('name'))
            : toLower("{$request->get('name')}-".Roles::IDENTIFIER);

        BouncerFacade::ability()->createForModel($request->get('entity_type'), [
            'name'  => $name,
            'title' =>  $request->get('title')
        ]);
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    /**
     * @group Parques - Permisos
     *
     * Actualizar Permisos
     *
     * Actualiza el recurso especificado en la base de datos.
     *
     * @urlParam permission int Id del permiso a actualizar. Example: 3
     *
     * @authenticated
     * @response {
     *      "data": "Datos actualizados satisfactoriamente",
     *      "details": null,
     *      "code": 200,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
     * @param UpdatePermissionRequest $request
     * @param Ability $permission
     * @return JsonResponse
     */
    public function update(UpdatePermissionRequest $request, Ability $permission)
    {
        $name = explode('-', toLower($request->get('name')));
        $name = end($name);
        $name = $name == Roles::IDENTIFIER
            ? toLower($request->get('name'))
            : toLower("{$request->get('name')}-".Roles::IDENTIFIER);

        $permission->forceFill([
            'name'  => $name,
            'title' =>  $request->get('title'),
            'entity_type' => $request->get('entity_type'),
        ]);
        $permission->save();
        return $this->success_message(__('validation.handler.updated'));
    }

    /**
     * @group Parques - Permisos
     *
     * Eliminar Permisos
     *
     * Elimina el recurso especificado en la base de datos.
     *
     * @urlParam permission int required Id del tipo de escenario: Example: 1
     * @authenticated
     * @response {
     *      "data": "Datos eliminados satisfactoriamente",
     *      "details": null,
     *      "code": 204,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
     * @param Ability $permission
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Ability $permission)
    {
        $permission->delete();
        return $this->success_message(__('validation.handler.deleted'));
    }
}
