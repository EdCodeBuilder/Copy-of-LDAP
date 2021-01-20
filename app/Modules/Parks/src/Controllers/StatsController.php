<?php

namespace App\Modules\Parks\src\Controllers;

use App\Modules\Parks\src\Models\Location;
use App\Modules\Parks\src\Models\Park;
use App\Modules\Parks\src\Models\Scale;
use App\Modules\Parks\src\Resources\ScaleResource;
use App\Modules\Parks\src\Resources\ScaleStatsResource;
use App\Modules\Parks\src\Resources\StatsLocationResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource by scale.
     *
     * @return JsonResponse
     */
    public function stats()
    {
        $stats = Scale::withCount('parks')->get();
        return $this->success_response( ScaleStatsResource::collection( $stats ) );
    }

    /**
     * Display a listing of the resource by administration.
     *
     * @return JsonResponse
     */
    public function count()
    {
        $data = [
            'total'     =>  Park::count(),
            'admin'     =>  Park::idrd()->count(),
            'not_admin' =>  Park::notIdrd()->count(),
        ];
        return $this->success_message($data);
    }

    /**
     * Display a listing of the resource by enclosure.
     *
     * @return JsonResponse
     */
    public function enclosure()
    {
        $data = Park::selectRaw(DB::raw('Cerramiento AS enclosure, COUNT(*) as parks_count'))
            ->groupBy(['Cerramiento'])
            ->get()
            ->map(function ($model) {
                return [
                    'name'     => isset($model->enclosure) ? toUpper($model->enclosure) : null,
                    'parks_count'   => isset($model->parks_count) ? (int) $model->parks_count : 0,
                ];
            });
        return $this->success_message($data);
    }

    /**
     * Display a listing of the resource by certification.
     *
     * @return JsonResponse
     */
    public function certified()
    {
        $data = Park::where('EstadoCertificado', 1)->count();
        $total = Park::count();
        return $this->success_message([
            'name'  =>  'Parques Certificados',
            'value' =>  $data,
            'percent' => round($data * 100 / $total, 2)
        ]);
    }

    /**
     * Display a listing of the resource by locality.
     *
     * @return JsonResponse
     */
    public function localities()
    {
        $stats = Location::withCount('parks')->get();
        return $this->success_response(StatsLocationResource::collection( $stats ));
    }

    /**
     * Display a listing of the resource by upz.
     *
     * @return JsonResponse
     */
    public function upz()
    {
        $stats = Park::query()
            ->selectRaw(DB::raw('upz.Upz as name, parque.Upz as code, COUNT(*) as parks_count'))
            ->leftJoin('upz', 'parque.Upz', '=', 'upz.cod_upz')
            ->groupBy(['parque.Upz'])
            ->get()
            ->map(function ($model) {
                return [
                    'name'   => isset( $model->name ) ? toUpper($model->name) : 'SIN UPZ',
                    'code'   => isset( $model->code ) ? toUpper($model->code) : null,
                    'parks_count'   => isset( $model->parks_count ) ? (int) $model->parks_count : 0,
                ];
            });
        return $this->success_message($stats);
    }
}
