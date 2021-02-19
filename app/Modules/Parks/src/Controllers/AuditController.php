<?php

namespace App\Modules\Parks\src\Controllers;

use App\Modules\Parks\src\Models\Scale;
use App\Modules\Parks\src\Resources\AuditResource;
use App\Modules\Parks\src\Resources\ScaleResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use OwenIt\Auditing\Models\Audit;

class AuditController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource with few data.
     *
     * @return JsonResponse
     */
    public function index()
    {
        abort_unless(
            auth('api')->check() && auth()->user()->isA(...['park-administrator', 'superadmin']),
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
