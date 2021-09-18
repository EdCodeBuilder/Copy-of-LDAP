<?php

namespace App\Modules\Parks\src\Controllers;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Enclosure;
use App\Modules\Parks\src\Request\EnclosureRequest;
use App\Modules\Parks\src\Resources\EnclosureResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class EnclosureController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api')->except('index');
        $this->middleware(Roles::actions(Enclosure::class, 'create_or_manage'))->only('store');
        $this->middleware(Roles::actions(Enclosure::class, 'update_or_manage'))->only('update');
        $this->middleware(Roles::actions(Enclosure::class, 'destroy_or_manage'))->only('destroy');
    }

    /**
     * Get a listing of the resource
     *
     * @return JsonResponse
     */
    public function index()
    {
        return $this->success_response(
            EnclosureResource::collection( Enclosure::all() )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EnclosureRequest $request
     * @return JsonResponse
     */
    public function store(EnclosureRequest $request)
    {
        $form = new Enclosure();
        $form->Cerramiento = $request->get('name');
        $form->save();
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EnclosureRequest $request
     * @param Enclosure $enclosure
     * @return JsonResponse
     */
    public function update(EnclosureRequest $request, Enclosure $enclosure)
    {
        $enclosure->Cerramiento = $request->get('name');
        $enclosure->save();
        return $this->success_message(__('validation.handler.updated'));
    }

    /**
     * Delete the specified resource from storage.
     *
     * @param Enclosure $enclosure
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Enclosure $enclosure)
    {
        $enclosure->delete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }
}
