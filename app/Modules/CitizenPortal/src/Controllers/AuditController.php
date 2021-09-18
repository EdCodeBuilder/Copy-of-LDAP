<?php

namespace App\Modules\CitizenPortal\src\Controllers;

use App\Modules\CitizenPortal\src\Constants\Roles;
use App\Modules\CitizenPortal\src\Resources\AuditResource;
use Illuminate\Http\JsonResponse;
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
        $this->middleware(Roles::actions(Audit::class, 'view'))
            ->only('index');
    }

    /**
     * Display a listing of the resource with few data.
     *
     * @return JsonResponse
     */
    public function index()
    {
       $audits = Audit::query()
            ->with('user:id,name,surname')
            ->where('tags', 'like', '%citizen%')
            ->latest()
            ->paginate($this->per_page);

       $collection = AuditResource::collection($audits);

       return $this->success_response( $collection, Response::HTTP_OK, [
           'headers' => AuditResource::headers(),
           'expanded' => AuditResource::additionalData(),
       ]);
    }
}
