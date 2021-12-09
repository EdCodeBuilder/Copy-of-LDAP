<?php

namespace App\Modules\Parks\src\Controllers;

use App\Modules\Parks\src\Models\Equipment;
use App\Modules\Parks\src\Resources\EndowmentResourceC;
use App\Modules\Parks\src\Resources\EquipmentResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * @group Parques - Equipamiento
 *
 * API para la gestión y consulta de datos de Equipamiento.
 */
class EquipmentController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @group Parques - Equipamiento
     *
     * Equipamiento
     *
     * Muestra un listado del recurso.
     *
     *
     * @return JsonResponse
     */
    public function index()
    {
        return $this->success_response( EquipmentResource::collection( Equipment::all() ) );
    }

    public function endowments(Equipment $equipment)
    {
        return $this->success_response(
            EndowmentResourceC::collection($equipment->endowments)
        );
    }
}
