<?php

namespace App\Modules\PaymentGateway\src\Controllers;

use App\Modules\PaymentGateway\src\Models\ParkPse;
use App\Modules\PaymentGateway\src\Resources\ParkPseResource;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Modules\PaymentGateway\src\Models\ParkService;
use App\Modules\PaymentGateway\src\Request\ParkPaymentCreateUpdateRequest;
use App\Modules\PaymentGateway\src\Resources\ServicePseResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @group Pasarela de pagos - Parques
 *
 * API para la gestión y consulta de datos de Parques Pse
 */
class ParkController extends Controller
{
      /**
       * Initialise common request params
       */
      public function __construct()
      {
            parent::__construct();
      }

      /**
       * @group Pasarela de pagos - Parques
       *
       * Parques
       *
       * Muestra un listado del recurso.
       *
       *
       * @return JsonResponse
       */
      public function index()
      {
            return $this->success_response(ParkPseResource::collection(ParkPse::all()));
      }

      /**
       * @group Pasarela de pagos - Parques
       *
       * Parques
       *
       * Crear un parque con aprovechamiento economico.
       *
       *
       * @return JsonResponse
       */
      public function create(ParkPaymentCreateUpdateRequest $request)
      {
            $request->validated();
            $park = new ParkPse;
            $park->nombre_parque = toUpper($request->name_park);
            $park->codigo_parque = $request->code_park;
            $park->nombre_contacto = toUpper($request->contac_park);
            $park->telefonos = toUpper($request->phones_park);
            $park->direccion = toUpper($request->address_park);
            $park->email = toUpper($request->email_park);
            $park->save();
            return $this->success_message(['park' => $park, 'message' => 'Parque creado'], Response::HTTP_CREATED);
      }

      /**
       * @group Pasarela de pagos - Parques
       *
       * Parques
       *
       * Actualizar un parque aprovechamiento economico.
       *
       *
       * @return JsonResponse
       */
      public function update(ParkPaymentCreateUpdateRequest $request, $id)
      {
            $request->validated();
            $park = ParkPse::find($id);
            $park->nombre_parque = toUpper($request->name_park);
            $park->codigo_parque = $request->code_park;
            $park->nombre_contacto = toUpper($request->contac_park);
            $park->telefonos = toUpper($request->phones_park);
            $park->direccion = toUpper($request->address_park);
            $park->email = toUpper($request->email_park);
            $park->save();
            return $this->success_message(['park' => $park, 'message' => 'Parque actualizado']);
      }

      /**
       * @group Pasarela de pagos - Parques
       *
       * Parques
       *
       * Eliminar un parque aprovechamiento economico.
       *
       *
       * @return JsonResponse
       */
      public function delete($id)
      {
            $park = ParkPse::find($id);
            if ($park->servicesOffered) {
                  foreach ($park->servicesOffered as $service) {
                        ParkService::destroy($service->pivot->id_parque_servicio);
                  }
            }
            $park->destroy($id);

            return $this->success_message(['park_id' => $id, 'message' => 'Parque eliminado']);
      }

      /**
       * @group Pasarela de pagos - Servicios
       *
       * Servicios
       *
       * Muestra un listado de los servicios de un parque.
       *
       *
       * @return JsonResponse
       */
      public function services($id)
      {
            return $this->success_response(ServicePseResource::collection(ParkPse::find($id)->servicesOffered));
      }

      /**
       * @group Pasarela de pagos - Parques
       *
       * parks busqueda
       *
       * Muestra un listado de parques por busqueda.
       *
       *
       * @return JsonResponse
       */
      public function search(Request $request)
      {
            $parks = ParkPse::where('nombre_parque', 'like', '%' . $request->word . '%')
                  ->orWhere('codigo_parque', 'like', '%' .  $request->word . '%')
                  ->get();
            return $this->success_response(ParkPseResource::collection($parks));
      }
}
