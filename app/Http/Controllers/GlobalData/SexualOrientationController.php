<?php

namespace App\Http\Controllers\GlobalData;

use App\Http\Resources\GlobalData\SexualOrientationResource;
use App\Models\Security\SexualOrientation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SexualOrientationController extends Controller
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
        $data = $this->setQuery(SexualOrientation::query(), (new SexualOrientation())->getKeyName())->get();
        return $this->success_response(
            SexualOrientationResource::collection($data)
        );
    }
}
