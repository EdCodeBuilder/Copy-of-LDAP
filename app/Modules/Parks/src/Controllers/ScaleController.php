<?php

namespace App\Modules\Parks\src\Controllers;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Scale;
use App\Modules\Parks\src\Request\ScaleRequest;
use App\Modules\Parks\src\Resources\ScaleResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ScaleController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api')->except('index');
        $this->middleware(Roles::actions(Scale::class, 'create_or_manage'))->only('store');
        $this->middleware(Roles::actions(Scale::class, 'update_or_manage'))->only('update');
        $this->middleware(Roles::actions(Scale::class, 'destroy_or_manage'))->only('destroy');
    }

    /**
     * Get a listing of the resource
     *
     * @return JsonResponse
     */
    public function index()
    {
        return $this->success_response( ScaleResource::collection( Scale::all() ) );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ScaleRequest $request
     * @return JsonResponse
     */
    public function store(ScaleRequest $request)
    {
        $form = new Scale();
        $form->Tipo = $request->get('name');
        $form->Descripcion = $request->get('description');
        $form->save();
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ScaleRequest $request
     * @param Scale $scale
     * @return JsonResponse
     */
    public function update(ScaleRequest $request, Scale $scale)
    {
        $scale->Tipo = $request->get('name');
        $scale->Descripcion = $request->get('description');
        $scale->save();
        return $this->success_message(__('validation.handler.updated'));
    }

    /**
     * Delete the specified resource from storage.
     *
     * @param Scale $scale
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Scale $scale)
    {
        $scale->delete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }
}
