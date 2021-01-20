<?php

namespace App\Modules\Parks\src\Controllers;

use App\Modules\Parks\src\Models\Scale;
use App\Modules\Parks\src\Resources\AuditResource;
use App\Modules\Parks\src\Resources\ScaleResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

    public function index()
    {
       $audits = Audit::query()
            ->with('user:id,name,surname')
            ->where('tags', 'like', '%park%')
            ->paginate($this->per_page);

       return $this->success_response( AuditResource::collection($audits) );
    }
}
