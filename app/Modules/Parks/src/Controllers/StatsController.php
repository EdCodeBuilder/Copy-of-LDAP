<?php

namespace App\Modules\Parks\src\Controllers;

use App\Modules\Parks\src\Exports\DashboardExport;
use App\Modules\Parks\src\Models\Location;
use App\Modules\Parks\src\Models\Park;
use App\Modules\Parks\src\Models\Scale;
use App\Modules\Parks\src\Resources\ScaleResource;
use App\Modules\Parks\src\Resources\ScaleStatsResource;
use App\Modules\Parks\src\Resources\StatsLocationResource;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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
     * @param Request $request
     * @return JsonResponse
     */
    public function stats(Request $request)
    {
        $stats = Scale::withCount([
                            'parks' => function ($q) use ($request) {
                                return $this->makeFilters($q, $request);
                            }
                        ])
                        ->get();
        return $this->success_response( ScaleStatsResource::collection( $stats ) );
    }

    /**
     * Display a listing of the resource by administration.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function count(Request $request)
    {
        $total = Park::query();
        $total = $this->makeFilters($total, $request);
        $total = $total->count();

        $idrd = Park::idrd();
        $idrd = $this->makeFilters($idrd, $request);
        $idrd = $idrd->count();

        $not_idrd = Park::notIdrd();
        $not_idrd = $this->makeFilters($not_idrd, $request);
        $not_idrd = $not_idrd->count();

        $data = [
            'total'     =>  $total,
            'admin'     =>  $idrd,
            'not_admin' =>  $not_idrd,
        ];
        return $this->success_message($data);
    }

    /**
     * Display a listing of the resource by enclosure.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function enclosure(Request $request)
    {
        $data = Park::selectRaw(DB::raw('Cerramiento AS enclosure, COUNT(*) as parks_count'));
        $data = $this->makeFilters($data, $request);
        $data = $data
            ->groupBy(['Cerramiento'])
            ->get()
            ->map(function ($model) {
                return [
                    'name'     => isset($model->enclosure) ? toUpper($model->enclosure) : '',
                    'parks_count'   => isset($model->parks_count) ? (int) $model->parks_count : 0,
                ];
            });
        return $this->success_message($data);
    }

    /**
     * Display a listing of the resource by certification.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function certified(Request $request)
    {
        $data = Park::where('EstadoCertificado', 1);
        $data = $this->makeFilters($data, $request);
        $data = $data->count();
        $total = Park::query();
        $total = $this->makeFilters($total, $request);
        $total = $total->count();
        return $this->success_message([
            'name'  =>  'Parques Certificados',
            'value' =>  $data,
            'percent' => round($data * 100 / $total, 2)
        ]);
    }

    /**
     * Display a listing of the resource by locality.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function localities(Request $request)
    {
        $stats = Location::withCount([
            'parks' => function ($q) use ($request) {
                return $this->makeFilters($q, $request);
            }
        ])->get();

        return $this->success_response(StatsLocationResource::collection( $stats ));
    }

    /**
     * Display a listing of the resource by upz.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function upz(Request $request)
    {
        $stats = Park::query();
        $stats = $this->makeFilters($stats, $request);
        $stats = $stats
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

    /**
     * @param Request $request
     * @return Response|BinaryFileResponse
     */
    public function excel(Request $request)
    {
        return (new DashboardExport($request))->download('REPORTE_PARQUES.xlsx', Excel::XLSX);
    }

    public function makeFilters($query, Request $request)
    {
        return $query
                ->when($request->has('location'), function ($query) use ($request) {
                    if (count($request->get('location')) > 0)
                        return $query->whereIn('Id_Localidad', $request->get('location'));

                    return $query;
                })
                ->when($request->has('certified'), function ($query) use ($request) {
                    if ($request->get('certified') == 'certified')
                        return $query->where('EstadoCertificado', 1);
                    if ($request->get('certified') == 'not_certified')
                        return $query->where('EstadoCertificado', '!=', 1);

                    return $query;
                })
                ->when($request->has('enclosure'), function ($query) use ($request) {
                    $types = $request->get('enclosure');
                    if (is_array($types) && count($types) > 0)
                        return $query->whereIn('Cerramiento', $types);

                    return $query;
                })
                ->when($request->has('park_type'), function ($query) use ($request) {
                    if (is_array($request->get('park_type')) && count($request->get('park_type')) > 0)
                        return $query->whereIn('Id_Tipo', $request->get('park_type'));
                    return $query;
                })
                ->when($request->has('location'), function ($query) use ($request) {
                    if (count($request->get('location')) > 0)
                        return $query->whereIn('Id_Localidad', $request->get('location'));

                    return $query;
                })
                ->when($request->has('upz'), function ($query) use ($request) {
                    if (count($request->get('upz')) > 0)
                        return $query->whereIn('Upz', $request->get('upz'));

                    return $query;
                })
                ->when($request->has('admin'), function ($query) use ($request) {
                    if ($request->get('admin') == 'admin')
                        return $query->where('Administracion', 'IDRD');
                    if ($request->get('admin') == 'is_not_admin')
                        return $query->where('Administracion', '!=', 'IDRD');
                    return $query;
                });
    }
}
