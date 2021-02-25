<?php

namespace App\Http\Controllers\Auth;

use App\Http\Resources\Auth\ModuleResource;
use App\Models\Security\Module;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ModuleController extends Controller
{
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
        return $this->success_response(
            ModuleResource::collection( Module::active()->paginate( $this->per_page ) )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function store(Request $request)
    {
        $module = new Module();
        $module->fill( $request->all() );
        if ( $module->save() ) {
            return $this->success_message(__('validation.handler.success'));
        }
        return $this->error_response(
            __('validation.handler.unexpected_failure'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /**
     * Display the specified resource.
     *
     * @param Module $module
     * @return JsonResponse
     */
    public function show(Module $module)
    {
        return $this->success_response(
            new ModuleResource( $module )
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Module $module
     * @return JsonResponse
     */
    public function update(Request $request, Module $module)
    {
        $module->fill( $request->all() );
        if ( $module->save() ) {
            return $this->success_message(__('validation.handler.updated'));
        }
        return $this->error_response(
            __('validation.handler.unexpected_failure'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Module $module
     * @return JsonResponse
     */
    public function destroy(Module $module)
    {
        if ( $module->save() ) {
            return $this->success_message(__('validation.handler.deleted'));
        }
        return $this->error_response(
            __('validation.handler.unexpected_failure'),
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }
}
