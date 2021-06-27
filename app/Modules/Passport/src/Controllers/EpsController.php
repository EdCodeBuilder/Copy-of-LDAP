<?php


namespace App\Modules\Passport\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\Passport\src\Models\Eps;
use App\Modules\Passport\src\Resources\EpsResource;
use Illuminate\Http\JsonResponse;

class EpsController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
        return $this->success_response(
            EpsResource::collection(
                $this->setQuery(Eps::query(), 'i_pk_id')->get()
            )
        );
    }
}
