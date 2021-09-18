<?php

namespace App\Modules\Parks\src\Controllers;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\UpzType;
use App\Modules\Parks\src\Request\UpzTypeRequest;
use App\Modules\Parks\src\Resources\UpzTypeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class UpzTypeController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api')->except('index');
        $this->middleware(Roles::actions(UpzType::class, 'create_or_manage'))->only('store');
        $this->middleware(Roles::actions(UpzType::class, 'update_or_manage'))->only('update');
        $this->middleware(Roles::actions(UpzType::class, 'destroy_or_manage'))->only('destroy');
    }

    /**
     * Get a listing of the resource
     *
     * @return JsonResponse
     */
    public function index()
    {
        return $this->success_response(
            UpzTypeResource::collection( UpzType::all() )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UpzTypeRequest $request
     * @return JsonResponse
     */
    public function store(UpzTypeRequest $request)
    {
        $form = new UpzType();
        $form->Tipo = toUpper($request->get('name'));
        $form->save();
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpzTypeRequest $request
     * @param UpzType $types
     * @return JsonResponse
     */
    public function update(UpzTypeRequest $request, UpzType $types)
    {
        $types->Tipo = toUpper($request->get('name'));
        $types->save();
        return $this->success_message(__('validation.handler.updated'));
    }

    /**
     * Delete the specified resource from storage.
     *
     * @param UpzType $types
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(UpzType $types)
    {
        $types->delete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }
}
