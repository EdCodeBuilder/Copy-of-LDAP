<?php


namespace App\Modules\Passport\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\Passport\src\Resources\AuditResource;
use App\Modules\Passport\src\Constants\Roles;
use Illuminate\Http\JsonResponse;
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
            auth('api')->check() && auth()->user()->isA(...Roles::all()),
            Response::HTTP_FORBIDDEN,
            __('validation.handler.unauthorized')
        );
        $audits = Audit::query()
            ->with('user:id,name,surname')
            ->where('tags', 'like', '%vital_passport%')
            ->latest()
            ->paginate($this->per_page);

        $collection = AuditResource::collection($audits);

        return $this->success_response( $collection, Response::HTTP_OK, [
            'headers' => AuditResource::headers(),
            'expanded' => AuditResource::additionalData(),
        ]);
    }
}
