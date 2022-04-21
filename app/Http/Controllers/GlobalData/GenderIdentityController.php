<?php

namespace App\Http\Controllers\GlobalData;

use App\Http\Resources\GlobalData\GenderIdentityResource;
use App\Models\Security\GenderIdentity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GenderIdentityController extends Controller
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
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $data = $this->setQuery(GenderIdentity::query(), (new GenderIdentity())->getKeyName())->get();
        return $this->success_response(
            GenderIdentityResource::collection($data)
        );
    }
}
