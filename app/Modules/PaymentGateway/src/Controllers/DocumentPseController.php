<?php

namespace App\Modules\PaymentGateway\src\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Modules\PaymentGateway\src\Models\Document;
use App\Modules\PaymentGateway\src\Resources\DocumentPseResource;

/**
 * @group Pasarela de pagos - Parques
 *
 * API para la gestión y consulta de datos de Parques Pse
 */
class DocumentPseController extends Controller
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
        return $this->success_response(DocumentPseResource::collection(Document::all()));
    }
}
