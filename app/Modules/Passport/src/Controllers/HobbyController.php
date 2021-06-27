<?php


namespace App\Modules\Passport\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\Passport\src\Models\Hobby;
use App\Modules\Passport\src\Resources\HobbyResource;
use Illuminate\Http\JsonResponse;

class HobbyController extends Controller
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
            HobbyResource::collection(Hobby::all())
        );
    }
}
