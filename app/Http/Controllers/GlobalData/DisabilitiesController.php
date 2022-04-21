<?php

namespace App\Http\Controllers\GlobalData;


use App\Http\Resources\GlobalData\DisabilityResource;
use App\Models\Security\Disability;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DisabilitiesController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $data = $this->setQuery(Disability::query(), (new Disability())->getKeyName())->get();
        return $this->success_response(
            DisabilityResource::collection($data)
        );
    }
}
