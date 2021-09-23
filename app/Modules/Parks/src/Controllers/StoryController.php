<?php

namespace App\Modules\Parks\src\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Parks\src\Models\Park;
use App\Modules\Parks\src\Models\Rupi;
use App\Modules\Parks\src\Models\Story;
use App\Modules\Parks\src\Request\RupiRequest;
use App\Modules\Parks\src\Request\StoryRequest;
use App\Modules\Parks\src\Resources\RupiResource;
use App\Modules\Parks\src\Resources\StoryResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Parques - Datos de interés
 *
 * API para la gestión y consulta de datos de interés del parque.
 *
 */
class StoryController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @group Parques - Datos de interés
     *
     * Datos de interés
     *
     * Muestra un listado del recurso.
     *
     * @urlParam park string required Id o código IDRD del parque. Example: 03-036
     *
     * @param $park
     * @return JsonResponse
     */
    public function index($park)
    {
        $data = Park::with('story')
            ->where('Id_IDRD', $park)
            ->orWhere('Id', $park)
            ->first();
        if ($data) {
            return $this->success_response(
                StoryResource::collection( $data->story )
            );
        }
        return $this->error_response(__('parks.handler.park_does_not_exist', ['code' => $park]));
    }

    /**
     * @group Parques - Datos de interés
     *
     * Crear Datos de interés
     *
     * Almacena un recurso recién creado en la base de datos.
     *
     * @urlParam park string required Id o código IDRD del parque. Example: 03-036
     * @authenticated
     * @response 201 {
     *      "data": "Datos almacenados satisfactoriamente",
     *      "details": null,
     *      "code": 201,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
     * @param $park
     * @param StoryRequest $request
     * @return JsonResponse
     */
    public function store($park, StoryRequest $request)
    {
        $data = Park::with('story')
            ->where('Id_IDRD', $park)
            ->orWhere('Id', $park)
            ->first();

        if ($data) {
            $data->story()->create([
               'Subtitulo'   => $request->get('title'),
               'Parrafo'   => $request->get('text'),
            ]);
            return $this->success_message(__('validation.handler.success'));
        }
        return $this->error_response(__('parks.handler.park_does_not_exist', ['code' => $park]));
    }

    /**
     * @group Parques - Datos de interés
     *
     * Actualizar Datos de interés
     *
     * Actualiza el recurso especificado en la base de datos.
     *
     * @urlParam park string required Id o código IDRD del parque. Example: 03-036
     * @urlParam story int required Id del Dato de Interés. Example: 3
     *
     * @authenticated
     * @response {
     *      "data": "Datos actualizados satisfactoriamente",
     *      "details": null,
     *      "code": 200,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
     * @param StoryRequest $request
     * @param $park
     * @param Story $story
     * @return JsonResponse
     */
    public function update(StoryRequest $request, $park, Story $story)
    {
        $story->fill([
            'Subtitulo'   => $request->get('title'),
            'Parrafo'   => $request->get('text'),
        ]);
        $story->save();
        return $this->success_message(__('validation.handler.updated'));
    }

    /**
     * @group Parques - Datos de interés
     *
     * Eliminar Datos de interés
     *
     * Elimina el recurso especificado en la base de datos.
     *
     * @urlParam park string required Id o código IDRD del parque. Example: 03-036
     * @urlParam story int required Id del Dato de Interés. Example: 3
     * @authenticated
     * @response {
     *      "data": "Datos eliminados satisfactoriamente",
     *      "details": null,
     *      "code": 204,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
     * @param $park
     * @param Story $story
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy($park, Story $story)
    {
        $story->delete();
        return $this->success_message(__('validation.handler.deleted'));
    }
}
