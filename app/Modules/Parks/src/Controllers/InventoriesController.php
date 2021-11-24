<?php

namespace App\Modules\Parks\src\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Endowment;
use App\Modules\Parks\src\Models\Material;
use App\Modules\Parks\src\Models\ParkEndowment;
use App\Modules\Parks\src\Request\ParkEndowmentCreateRequest;
use App\Modules\Parks\src\Request\ParkEndowmentUpdateRequest;
use App\Modules\Parks\src\Resources\EndowmentResource;
use App\Modules\Parks\src\Resources\EndowmentResourceC;
use App\Modules\Parks\src\Resources\MaterialResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class InventoriesController extends Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->middleware('auth:api')->only(['inventory', 'create', 'update', 'destroy', 'endowments', 'material']);
		$this->middleware(Roles::actions(ParkEndowment::class, 'create_or_manage'))->only('create');
		$this->middleware(Roles::actions(ParkEndowment::class, 'update_or_manage'))->only('update');
		$this->middleware(Roles::actions(ParkEndowment::class, 'destroy_or_manage'))->only('destroy');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		//
	}

	/**
	 * @group Parques - Dotaciones
	 *create
	 * Dotaciones
	 *
	 * En desarrollo. Muestra el listado de docationes de un parque especificado y un equipamiento especificado.
	 *
	 * @urlParam park int required Id del parque. Example: 9478
	 * @urlParam equipment int required Id del equipamiento. Example: 4
	 *
	 * @param $park
	 * @param $equipment
	 * @return JsonResponse
	 */
	public function inventory($park, $equipment)
	{
		$parks = ParkEndowment::whereHas('endowment', function ($query) use ($equipment) {
			return $query->where('Id_Equipamento', $equipment);
		})
			->where('Id_Parque', $park)
			->paginate($this->per_page);
		return $this->success_response(EndowmentResource::collection($parks));
	}

	/**
	 * @group Parques
	 *
	 * Crear dotacion
	 *
	 * Crea una dotacion para el parque especifico.
	 *
	 * @authenticated
	 * @response 201 {
	 *      "data": "Datos almacenados satisfactoriamente",
	 *      "details": null,
	 *      "code": 201,
	 *      "requested_at": "2021-09-20T17:52:01-05:00"
	 * }
	 *
	 * @param ParkEndowmentCreateRequest $request
	 * @return JsonResponse
	 */
	public function create(ParkEndowmentCreateRequest $request)
	{
		try {
			$Parkendowment = new ParkEndowment();
			$filled = $Parkendowment->transformRequest($request->validated(), 'create');
			$Parkendowment->fill($filled);
			$Parkendowment->save();
			return $this->success_message(__('validation.handler.success'), Response::HTTP_CREATED);
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}

	/**
	 * @group Parques
	 *
	 * Actualizar dotación parque.
	 *
	 * Actualiza información de la dotacion de un parque en específico
	 *
	 * @authenticated
	 * @response {
	 *      "data": "Datos actualizados satisfactoriamente",
	 *      "details": null,
	 *      "code": 200,
	 *      "requested_at": "2021-09-20T17:52:01-05:00"
	 * }
	 * @param ParkEndowmentUpdateRequest $request
	 * @param $id int
	 * @return JsonResponse
	 */
	public function update(ParkEndowmentUpdateRequest $request, $id)
	{
		try {
			$parkendowment = ParkEndowment::find($id);
			$filled = $parkendowment->transformRequest($request->validated(), 'update', $parkendowment->Imagen);
			$parkendowment->fill($filled);
			$parkendowment->save();
			return $this->success_message(__('validation.handler.updated'));
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}

	/**
	 * @group Parques
	 *
	 * Eliminar dotacion parque.
	 *
	 * @authenticated
	 * @response {
	 *      "data": "Datos eliminados satisfactoriamente",
	 *      "details": null,
	 *      "code": 204,
	 *      "requested_at": "2021-09-20T17:52:01-05:00"
	 * }
	 * @param $id
	 * @return JsonResponse
	 */
	public function destroy($id)
	{
		try {
			$parkendowment = ParkEndowment::find($id);
			$existImage =  Storage::disk('public')->exists("parks/{$parkendowment->Imagen}");
			if ($existImage) {
				Storage::disk('public')->delete("parks/{$parkendowment->Imagen}");
			}
			$parkendowment->delete();
			return $this->success_message(__('validation.handler.deleted'), Response::HTTP_OK, Response::HTTP_NO_CONTENT);
		} catch (\Exception $e) {
			return $e->getMessage();
		}
	}

	/**
	 * @group Parques
	 *
	 * Muestra el listado de dotaciones existentes.
	 *
	 * @authenticated
	 *
	 * @return JsonResponse
	 */
	public function endowments()
	{
		return $this->success_response(
			EndowmentResourceC::collection(Endowment::all())
		);
	}

	/**
	 * @group Parques
	 *
	 * Muestra el listado de materiales existentes.
	 *
	 * @authenticated
	 *
	 * @return JsonResponse
	 */
	public function material()
	{
		return $this->success_response(
			MaterialResource::collection(Material::all())
		);
	}
}
