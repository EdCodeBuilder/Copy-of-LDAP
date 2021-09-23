<?php

namespace App\Modules\Parks\src\Controllers;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Enclosure;
use App\Modules\Parks\src\Models\Origin;
use App\Modules\Parks\src\Models\Park;
use App\Modules\Parks\src\Request\EnclosureRequest;
use App\Modules\Parks\src\Request\OriginRequest;
use App\Modules\Parks\src\Resources\EnclosureResource;
use App\Modules\Parks\src\Resources\OriginResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

/**
 * @group Parques - Historia del Parque
 *
 * @description API para la gestión y consulta de datos de historia del parque.
 *
 */
class OriginController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api')->except('index');
        $this->middleware(Roles::actions(Origin::class, 'create_or_manage'))->only('store');
        $this->middleware(Roles::actions(Origin::class, 'update_or_manage'))->only('update');
        $this->middleware(Roles::actions(Origin::class, 'destroy_or_manage'))->only('destroy');
    }

    /**
     * @group Parques - Historia del Parque
     *
     * Historia del Parque
     *
     * Muestra un listado del recurso.
     *
     * Breve con fotografías del parque.
     *
     * @responseFile responses/parks/origin.get.json
     * @urlParam park string required Id o código IDRD del parque. Example: 03-036
     * @apiResourceCollection App\Modules\Parks\src\Resources\OriginResource
     *
     * @return JsonResponse
     */
    public function index($park)
    {
        try {
            $data = Park::with('history')
                ->where('Id_IDRD', $park)
                ->orWhere('Id', $park)
                ->firstOrFail();
            return isset($data->history)
                    ? $this->success_response(
                            new OriginResource($data->history)
                        )
                    : $this->success_message(null);
        } catch (\Exception $exception) {
            return $this->error_response(
                __('validation.handler.resource_not_found'),
                Response::HTTP_NOT_FOUND,
                $exception->getMessage()
            );
        }
    }

    /**
     * @group Parques - Historia del Parque
     *
     * Crear Historia del Parque
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
     * @param OriginRequest $request
     * @param $park
     * @return JsonResponse
     */
    public function store(OriginRequest $request, $park)
    {
        try {
            $data = Park::with('history')
                ->where('Id_IDRD', $park)
                ->orWhere('Id', $park)
                ->firstOrFail();

            $data->history()->create([
                'IdParque' => $request->get('park_id'),
                'Parrafo1' => $request->get('paragraph_1'),
                'Parrafo2' => $request->get('paragraph_2'),
                'imagen1' => $this->processFile($request, 'image_1'),
                'imagen2' => $this->processFile($request, 'image_2'),
                'imagen3' => $this->processFile($request, 'image_3'),
                'imagen4' => $this->processFile($request, 'image_4'),
                'imagen5' => $this->processFile($request, 'image_5'),
                'imagen6' => $this->processFile($request, 'image_6'),
            ]);
            return $this->success_message(
                __('validation.handler.success'),
                Response::HTTP_CREATED
            );
        } catch (\Exception $exception) {
            return $this->error_response(
                __('validation.handler.resource_not_found'),
                Response::HTTP_NOT_FOUND,
                $exception->getMessage()
            );
        }
    }

    /**
     * @group Parques - Historia del Parque
     *
     * Actualizar Historia del Parque
     *
     * Actualiza el recurso especificado en la base de datos.
     *
     * @urlParam park string required Id o código IDRD del parque. Example: 03-036
     * @urlParam origin int required Id de la Historia del Parque. Example: 3
     *
     * @authenticated
     * @response {
     *      "data": "Datos actualizados satisfactoriamente",
     *      "details": null,
     *      "code": 200,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
     * @param OriginRequest $request
     * @param $park
     * @param Origin $origin
     * @return JsonResponse
     */
    public function update(OriginRequest $request, $park, Origin $origin)
    {
        $origin->Parrafo1 = $request->get('paragraph_1');
        $origin->Parrafo2 = $request->get('paragraph_2');
        $origin->imagen1 = $this->updateFile($origin, 'imagen1', $request, 'image_1');
        $origin->imagen2 = $this->updateFile($origin, 'imagen2', $request, 'image_2');
        $origin->imagen3 = $this->updateFile($origin, 'imagen3', $request, 'image_3');
        $origin->imagen4 = $this->updateFile($origin, 'imagen4', $request, 'image_4');
        $origin->imagen5 = $this->updateFile($origin, 'imagen5', $request, 'image_5');
        $origin->imagen6 = $this->updateFile($origin, 'imagen6', $request, 'image_6');
        $origin->save();
        return $this->success_message(__('validation.handler.updated'));
    }

    /**
     * @group Parques - Historia del Parque
     *
     * Eliminar Historia del Parque
     *
     * Elimina el recurso especificado en la base de datos.
     *
     * @urlParam park string required Id o código IDRD del parque. Example: 03-036
     * @urlParam origin int required Id de la Historia del Parque. Example: 3
     *
     * @authenticated
     * @response {
     *      "data": "Datos eliminados satisfactoriamente",
     *      "details": null,
     *      "code": 204,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
     * @param $park
     * @param Origin $origin
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy($park, Origin $origin)
    {
        foreach (range(1, 6) as $value) {
            if (
                isset($origin->{"imagen$value"}) &&
                !is_null($origin->{"imagen$value"}) &&
                $origin->{"imagen$value"} != '' &&
                Storage::disk('public')->exists(Origin::IMAGES_PATH.'/'.$origin->{"imagen$value"})
            ) {
                Storage::disk('public')->delete(Origin::IMAGES_PATH.'/'.$origin->{"imagen$value"});
            }
        }
        $origin->forceDelete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * @param Request $request
     * @param $key
     * @return string|null
     */
    private function processFile(Request $request, $key) {
        if ($request->hasFile($key)) {
            $ext = $request->file($key)->getClientOriginalExtension();
            $name = random_img_name().".$ext";
            $request->file($key)->storeAs(Origin::IMAGES_PATH, $name, ['disk' => 'public']);
            return $name;
        }
        return null;
    }

    /**
     * @param Origin $origin
     * @param $key
     * @param Request $request
     * @param $requestKey
     * @return mixed|string|null
     */
    private function updateFile(Origin $origin, $key, Request $request, $requestKey) {
        if ($request->hasFile($requestKey) && isset($origin->{$key}) && ! is_null($origin->{$key}) && $origin->{$key} != '') {
            if (Storage::disk('public')->exists(Origin::IMAGES_PATH.'/'.$origin->{$key})) {
                Storage::disk('public')->delete(Origin::IMAGES_PATH.'/'.$origin->{$key});
            }
            return $this->processFile($request, $requestKey);
        }
        return isset($origin->{$key}) ? $origin->{$key} : null;
    }
}
