<?php


namespace App\Modules\Contractors\src\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\Contractors\src\Models\Font;
use App\Modules\Contractors\src\Resources\FontResource;
use Illuminate\Http\JsonResponse;

class FontController extends Controller
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
        return $this->success_response(
            FontResource::collection(Font::all())
        );
    }
}
