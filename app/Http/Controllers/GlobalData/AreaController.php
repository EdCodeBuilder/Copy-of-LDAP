<?php

namespace App\Http\Controllers\GlobalData;

use App\Http\Controllers\Controller;
use App\Http\Resources\GlobalData\AreaResource;
use App\Http\Resources\GlobalData\SubdirectorateResource;
use App\Models\Security\Subdirectorate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class AreaController extends Controller
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
    public function office()
    {
        return $this->success_response(
            SubdirectorateResource::collection(
                $this->setQuery(Subdirectorate::query(), 'id')->get()
            )
        );
    }

    /**
     * @param Subdirectorate $office
     * @return JsonResponse
     */
    public function areas(Subdirectorate $office)
    {
        return $this->success_response(
            AreaResource::collection( $office->areas )
        );
    }
}
