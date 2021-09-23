<?php

namespace App\Modules\Parks\src\Controllers;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Scale;
use App\Modules\Parks\src\Resources\AuditResource;
use App\Modules\Parks\src\Resources\ScaleResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use OwenIt\Auditing\Models\Audit;

/**
 * @group Parques - Auditoría
 *
 * API para visualización de auditoría o control de cambios de Parques.
 *
 */
class AuditController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(Roles::actions(Audit::class, 'view'))->only('index');
    }

    /**
     * @group Parques - Auditoría
     *
     * Auditoría
     *
     * Muestra la cantidad de parques por escalas.
     * @authenticated
     * @responseFile responses/audits.json
     *
     * @return JsonResponse
     */
    public function index()
    {
        abort_unless(
            auth('api')->check() && (auth()->user()->isA('superadmin') || auth()->user()->can(Roles::can(Audit::class, 'view'), Audit::class) ),
            Response::HTTP_FORBIDDEN,
            __('validation.handler.unauthorized')
        );
       $audits = Audit::query()
            ->with('user:id,name,surname')
            ->where('tags', 'like', '%park%')
            ->latest()
            ->paginate($this->per_page);

       $collection = AuditResource::collection($audits);

       return $this->success_response( $collection, Response::HTTP_OK, [
           'headers' => AuditResource::headers(),
           'expanded' => AuditResource::additionalData(),
       ]);
    }
}
