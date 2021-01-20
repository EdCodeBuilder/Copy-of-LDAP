<?php

namespace App\Modules\Parks\src\Controllers;

use App\Modules\Parks\src\Models\Scale;
use App\Modules\Parks\src\Resources\ScaleResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ScaleController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return $this->success_response( ScaleResource::collection( Scale::all() ) );
    }
}
