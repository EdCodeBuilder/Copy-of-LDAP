<?php

namespace App\Modules\Parks\src\Controllers;

use App\Modules\Parks\src\Models\Equipment;
use App\Modules\Parks\src\Resources\EquipmentResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EquipmentController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(): JsonResponse
    {
        return $this->success_response( EquipmentResource::collection( Equipment::all() ) );
    }
}
