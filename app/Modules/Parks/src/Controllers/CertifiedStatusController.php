<?php

namespace App\Modules\Parks\src\Controllers;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Certified;
use App\Modules\Parks\src\Models\UpzType;
use App\Modules\Parks\src\Request\CertiicateStatusRequest;
use App\Modules\Parks\src\Request\UpzTypeRequest;
use App\Modules\Parks\src\Resources\CertificateStatusResource;
use App\Modules\Parks\src\Resources\UpzTypeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class CertifiedStatusController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api')->except('index');
        $this->middleware(Roles::actions(Certified::class, 'create_or_manage'))->only('store');
        $this->middleware(Roles::actions(Certified::class, 'update_or_manage'))->only('update');
        $this->middleware(Roles::actions(Certified::class, 'destroy_or_manage'))->only('destroy');
    }

    /**
     * Get a listing of the resource
     *
     * @return JsonResponse
     */
    public function index()
    {
        return $this->success_response(
            CertificateStatusResource::collection( Certified::all() )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CertiicateStatusRequest $request
     * @return JsonResponse
     */
    public function store(CertiicateStatusRequest $request)
    {
        $form = new Certified();
        $form->EstadoCertificado = toUpper($request->get('name'));
        $form->save();
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CertiicateStatusRequest $request
     * @param Certified $certified
     * @return JsonResponse
     */
    public function update(CertiicateStatusRequest $request, Certified $certified)
    {
        $certified->EstadoCertificado = toUpper($request->get('name'));
        $certified->save();
        return $this->success_message(__('validation.handler.updated'));
    }

    /**
     * Delete the specified resource from storage.
     *
     * @param Certified $certified
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Certified $certified)
    {
        $certified->delete();
        return $this->success_message(
            __('validation.handler.deleted'),
            Response::HTTP_OK,
            Response::HTTP_NO_CONTENT
        );
    }
}
