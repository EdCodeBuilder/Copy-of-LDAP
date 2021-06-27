<?php

namespace App\Http\Controllers\GlobalData;


use App\Http\Controllers\Controller;
use App\Http\Resources\GlobalData\SexResource;
use App\Models\Security\Sex;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SexController extends Controller
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
        $data = $this->setQuery(Sex::query(), 'Id_Genero')->get();
        return $this->success_response(
            SexResource::collection( $data )
        );
    }
}
