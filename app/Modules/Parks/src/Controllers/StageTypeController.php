<?php

namespace App\Modules\Parks\src\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Parks\src\Models\Location;
use App\Modules\Parks\src\Models\StageType;
use App\Modules\Parks\src\Models\Upz;
use App\Modules\Parks\src\Request\StageTypeRequest;
use App\Modules\Parks\src\Resources\LocationResource;
use App\Modules\Parks\src\Resources\NeighborhoodResource;
use App\Modules\Parks\src\Resources\StageTypeResource;
use App\Modules\Parks\src\Resources\UpzResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class StageTypeController extends Controller
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
        return $this->success_response( StageTypeResource::collection( StageType::all() ) );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StageTypeRequest $request
     * @return JsonResponse
     */
    public function store(StageTypeRequest $request)
    {
        $form = new StageType();
        $form->tipo = toUpper($request->get('name'));
        $form->save();
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StageTypeRequest $request
     * @param StageType $stage
     * @return JsonResponse
     */
    public function update(StageTypeRequest $request, StageType $stage)
    {
        $stage->tipo = $request->get('name');
        $stage->save();
        return $this->success_message(__('validation.handler.updated'));
    }
}
