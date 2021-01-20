<?php

namespace App\Modules\Parks\src\Controllers;

use App\Modules\Parks\src\Models\Scale;
use App\Modules\Parks\src\Models\Status;
use App\Modules\Parks\src\Resources\ScaleResource;
use App\Modules\Parks\src\Resources\StatusResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StatusController extends Controller
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
        return $this->success_response( StatusResource::collection( Status::all() ) );
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function type_zones()
    {
        $data = [
            [
                'id'    =>  'RESIDENCIAL',
                'name'  =>  'RESIDENCIAL',
            ],
            [
                'id'    =>  'COMERCIAL',
                'name'  =>  'COMERCIAL',
            ],
            [
                'id'    =>  'SENDEROS',
                'name'  =>  'SENDEROS',
            ],
            [
                'id'    =>  'MIXTO',
                'name'  =>  'MIXTO',
            ],
            [
                'id'    =>  'RESIDENCIAL/COMERCIAL',
                'name'  =>  'RESIDENCIAL/COMERCIAL',
            ],
            [
                'id'    =>  'INDUSTRIAL/COMERCIAL',
                'name'  =>  'INDUSTRIAL/COMERCIAL',
            ],
            [
                'id'    =>  'RURAL',
                'name'  =>  'RURAL',
            ],
            [
                'id'    =>  'MONTAÑOSO',
                'name'  =>  'MONTAÑOSO',
            ],
            [
                'id'    =>  'INDUSTRIAL',
                'name'  =>  'INDUSTRIAL',
            ],
            [
                'id'    =>  'INSTITUCIONAL',
                'name'  =>  'INSTITUCIONAL',
            ],
            [
                'id'    =>  'ESCOLAR',
                'name'  =>  'ESCOLAR',
            ],
            [
                'id'    =>  'OTRO',
                'name'  =>  'OTRO',
            ],
            [
                'id'    =>  'NINGUNO',
                'name'  =>  'NINGUNO',
            ],
            [
                'id'    =>  'BUENO',
                'name'  =>  'BUENO',
            ],
            [
                'id'    =>  'MALO',
                'name'  =>  'MALO',
            ],
            [
                'id'    =>  'REGULAR',
                'name'  =>  'REGULAR',
            ],
        ];
        return $this->success_message($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function concerns()
    {
        $data = [
            [
                'id'    =>  'SI',
                'name'  =>  'SI'
            ],
            [
                'id'    =>  'IDRD',
                'name'  =>  'IDRD'
            ],
            [
                'id'    =>  'Junta Administradora Local',
                'name'  =>  'Junta Administradora Local'
            ],
            [
                'id'    =>  'NO',
                'name'  =>  'NO'
            ],
            [
                'id'    =>  'Alcaldía Local',
                'name'  =>  'Alcaldía Local'
            ],
            [
                'id'    =>  'Otros',
                'name'  =>  'Otros'
            ],
            [
                'id'    =>  'Alianza Público Privada',
                'name'  =>  'Alianza Público Privada'
            ],
            [
                'id'    =>  'Indefinido',
                'name'  =>  'Indefinido'
            ],
        ];
        return $this->success_message($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function vigilance()
    {
        $data = [
            [
                'id'    =>  'Sin vigilancia',
                'name'  =>  'Sin vigilancia'
            ],
            [
                'id'    =>  'Con vigilancia',
                'name'  =>  'Con vigilancia'
            ],
        ];
        return $this->success_message($data);
    }
}
