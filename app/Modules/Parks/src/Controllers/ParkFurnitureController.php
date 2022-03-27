<?php

namespace App\Modules\Parks\src\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Parks\src\Models\Park;
use App\Modules\Parks\src\Resources\ParkFurnitureResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ParkFurnitureController extends Controller
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
    public function index(Park $park)
    {
        $furnishings = $park->park_furnitures()->paginate($this->per_page);
        return $this->success_response(ParkFurnitureResource::collection($furnishings));
    }
}
