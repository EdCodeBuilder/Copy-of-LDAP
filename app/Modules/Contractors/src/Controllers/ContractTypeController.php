<?php


namespace App\Modules\Contractors\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\Contractors\src\Models\Contractor;
use App\Modules\Contractors\src\Models\ContractType;
use App\Modules\Contractors\src\Resources\ContractorResource;
use App\Modules\Contractors\src\Resources\ContractTypeResource;
use Illuminate\Http\JsonResponse;

class ContractTypeController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        return $this->success_response(
            ContractTypeResource::collection(ContractType::all())
        );
    }
}
