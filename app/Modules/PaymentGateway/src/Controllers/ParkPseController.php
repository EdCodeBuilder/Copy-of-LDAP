<?php

namespace App\Modules\PaymentGateway\src\Controllers;

use App\Modules\PaymentGateway\src\Models\ParkPse;
use App\Modules\PaymentGateway\src\Resources\ParkPseResource;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Modules\PaymentGateway\src\Resources\ServicePseResource;

/**
 * @group Pasarela de pagos - Parques
 *
 * API para la gestión y consulta de datos de Parques Pse
 */
class ParkPseController extends Controller
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
}
