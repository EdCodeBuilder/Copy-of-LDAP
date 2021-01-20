<?php

namespace App\Modules\Parks\src\Controllers;

use App\Modules\Parks\src\Models\Enclosure;
use App\Modules\Parks\src\Resources\EnclosureResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EnclosureController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get a listing of the resource
     *
     * @return JsonResponse
     */
    public function index()
    {
        return $this->success_response( EnclosureResource::collection( Enclosure::all() ) );
    }
}
