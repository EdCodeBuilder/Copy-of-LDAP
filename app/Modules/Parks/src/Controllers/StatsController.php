<?php

namespace App\Modules\Parks\src\Controllers;

use App\Modules\Parks\src\Exports\DashboardExport;
use App\Modules\Parks\src\Exports\Excel as ExcelRaw;
use App\Modules\Parks\src\Models\Endowment;
use App\Modules\Parks\src\Models\Location;
use App\Modules\Parks\src\Models\Park;
use App\Modules\Parks\src\Models\Scale;
use App\Modules\Parks\src\Request\ParkExcelRequest;
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

/**
 * @group Parques - Estadísticas
 *
 * API para visualización de estadísticas.
 *
 */
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
     * Relación de parques que no se toman en el conteo por estar sectorizados
     *
     */
    public function uniqueParks() {
        return [
            // 07-036	TIMIZA(SECTOR VILLA DEL RIO) Se toma en cuenta un TIMIZA únicamente ya que cuentan como un sólo parque
            '07-036', // TIMIZA
            // '10-291' SIMON BOLIVAR (SECTOR JARDIN BOTANICO) Se toma en cuenta un parque ya que todo los sectores nombrados como SIMÓN BOLIVAR cuentan como un parque
            '12-090', // SIMON BOLIVAR (SECTOR CENTRO DE ALTO RENDIMIENTO )
            '12-091', // SIMON BOLIVAR ( SECTOR PARQUE DEPORTIVO EL SALITRE )
            '12-092', // SIMON BOLIVAR ( SECTOR PARQUE DE LOS NOVIOS )
            '12-110', // SIMON BOLIVAR ( SECTOR PLAZA DE ARTESANOS )
            '12-117', // SIMON BOLIVAR ( SECTOR SALITRE MAGICO )
            '12-140', // SIMON BOLIVAR (SECTOR ESCUELA DESALVAMENTO CRUZ ROJA)
            '12-141', // SIMON BOLIVAR (SECTOR MUSEO DE LOS NI┬ÑOS )
            '12-144', // SIMON BOLIVAR (SECTOR FUNDACION NI┬ÑO DIFERENTE)
            '12-145', // SIMON BOLIVAR (SECTOR I.D.R.D.)
            '12-146', // SIMON BOLIVAR (SECTOR NOVIOS II)
            '13-088', // SIMON BOLIVAR ( SECTOR VIRGILIO BARCO)
            '13-089', // SIMON BOLIVAR ( SECTOR CENTRAL )
        ];
    }

    /**
     * @group Parques - Estadísticas
     *
     * Escalas
     *
     * Muestra la cantidad de parques por escalas.
     *
     * @param ParkExcelRequest $request
     * @return JsonResponse
     */
    public function scales(ParkExcelRequest $request)
    {
        $stats = Scale::withCount([
                            'parks' => function ($q) use ($request) {
                                return $this->makeFilters($q, $request)
                                            ->whereNotIn('Id_IDRD', $this->uniqueParks());
                            }
                        ])
                        ->get();
        return $this->success_response( ScaleStatsResource::collection( $stats ) );
    }

    /**
     * @group Parques - Estadísticas
     *
     * Administración
     *
     * Muestra la cantidad de parques totales, administrados por el IDRD y no administrados por el IDRD.
     *
     * @param ParkExcelRequest $request
     * @return JsonResponse
     */
    public function count(ParkExcelRequest $request)
    {
        $total = Park::query();
        $total = $this->makeFilters($total, $request);
        $total = $total->whereNotIn('Id_IDRD', $this->uniqueParks())->count();

        $idrd = Park::idrd();
        $idrd = $this->makeFilters($idrd, $request);
        $idrd = $idrd->count();

        /*
        $not_idrd = Park::notIdrd();
        $not_idrd = $this->makeFilters($not_idrd, $request);
        $not_idrd = $not_idrd->whereNotIn('Id_IDRD', $this->uniqueParks())->count();
        */

        $stats = Scale::withCount([
                    'parks' => function ($q) use ($request) {
                        return $this->makeFilters($q, $request)
                            ->whereNotIn('Id_IDRD', $this->uniqueParks());
                    }
                ])
                ->orderByDesc('parks_count')
                ->first();

        $data = [
            'total'     =>  $total,
            'admin'     =>  $idrd,
            'not_admin' =>  new ScaleStatsResource($stats),
        ];
        return $this->success_message($data);
    }

    /**
     * @group Parques - Estadísticas
     *
     * Cerramientos
     *
     * Muestra la cantidad de parques por tipo de cerramiento.
     *
     * @param ParkExcelRequest $request
     * @return JsonResponse
     */
    public function enclosure(ParkExcelRequest $request)
    {
        $data = Park::selectRaw(DB::raw('Estrato AS enclosure, COUNT(*) as parks_count'));
        $data = $this->makeFilters($data, $request)->whereNotIn('Id_IDRD', $this->uniqueParks());
        $data = $data
            ->groupBy(['Estrato'])
            ->get()
            ->map(function ($model) {
                return [
                    'name'     => isset($model->enclosure) ? toUpper("Estrato $model->enclosure") : '',
                    'parks_count'   => isset($model->parks_count) ? (int) $model->parks_count : 0,
                ];
            });
        return $this->success_message($data);
    }

    /**
     * @group Parques - Estadísticas
     *
     * Certificados
     *
     * Muestra el porcentaje de parques certificados.
     *
     * @param ParkExcelRequest $request
     * @return JsonResponse
     */
    public function certified(ParkExcelRequest $request)
    {
        $data = Park::where('EstadoCertificado', 1);
        $data = $this->makeFilters($data, $request);
        $data = $data->count();
        $total = Park::query();
        $total = $this->makeFilters($total, $request);
        $total = $total->count();
        return $this->success_message([
            'name'  =>  'Certificados en el Sistema',
            'value' =>  $data,
            'percent' => $total == 0 ? 0 : round($data * 100 / $total, 2)
        ]);
    }

    /**
     * @group Parques - Estadísticas
     *
     * Localidades
     *
     * Muestra la cantidad de parques por localidades.
     *
     * @param ParkExcelRequest $request
     * @return JsonResponse
     */
    public function localities(ParkExcelRequest $request)
    {
        $stats = Location::withCount([
            'parks' => function ($q) use ($request) {
                return $this->makeFilters($q, $request);
            }
        ])->get();

        return $this->success_response(
            StatsLocationResource::collection( $stats ),
            Response::HTTP_OK,
            [
                'total' =>  Park::query()->whereNotIn('Id_IDRD', $this->uniqueParks())->count()
            ]
        );
    }

    /**
     * @group Parques - Estadísticas
     *
     * UPZ
     *
     * Muestra la cantidad de parques por UPZ.
     *
     * @param ParkExcelRequest $request
     * @return JsonResponse
     */
    public function upz(ParkExcelRequest $request)
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
                            'name'   => isset( $model->name ) ? toUpper($model->name) : 'UPZ EN REVISIÓN',
                            'code'   => isset( $model->code ) ? toUpper($model->code) : null,
                            'parks_count'   => isset( $model->parks_count ) ? (int) $model->parks_count : 0,
                        ];
                    });
        return $this->success_message($stats);
    }

    /**
     * @group Parques - Estadísticas
     *
     * Excel
     *
     * Devuelve un archivo en Excel (.xlsx) condificado en Base64 con información de los parques según los filtros realizados.
     *
     * @response {
     *      "data": { "name": "PARQUES-FA453A-A625A6.xlsx", "file": "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,TyayT8y76hh7A6GAJA887..." },
     *      "details": null,
     *      "code": 201,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
     * @param ParkExcelRequest $request
     * @return JsonResponse
     */
    public function excel(ParkExcelRequest $request)
    {
        $file = ExcelRaw::raw(new DashboardExport($request), Excel::XLSX);
        $name = random_img_name();
        $response =  array(
            'name' => toUpper(str_replace(' ', '-', __('parks.excel.title')))."-$name.xlsx",
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($file),
        );
        return $this->success_message($response);
    }

    public function endowmentStats($equipment, $park = null)
    {
        $data = Endowment::query()
            ->where('Id_Dotacion', $equipment)
            ->when($park, function ($query) use ($park) {
                return $query->whereHas('parks', function ($query) use ($park) {
                   return $query->where('Id', $park);
                });
            })
            ->withCount('parks')
            ->get()
            ->map(function ($model) {
                return [
                    'id'   => isset( $model->id ) ?  (int) $model->id : null,
                    'name'   => isset( $model->name ) ? toUpper($model->name) : null,
                    'equipment_id'   => isset( $model->Id_Equimamento ) ? (int) $model->Id_Equimamento : null,
                    'parks_count'   => isset( $model->parks_count ) ? (int) $model->parks_count : 0,
                ];
            });

        return $this->success_message($data);
    }

    /**
     * @param $query
     * @param Request $request
     * @return mixed
     */
    private function makeFilters($query, Request $request)
    {
        $park = new Park();
        return $query
                ->when($request->has('location'), function ($query) use ($request) {
                    $localities = $request->get('location');
                    return is_array($localities)
                        ? $query->whereIn('Id_Localidad', $localities)
                        : $query->where('Id_Localidad', $localities);
                })
                ->when($request->has('upz'), function ($query) use ($request,$park) {
                    $upz = $request->get('upz');
                    return is_array($upz)
                        ? $query->whereIn("{$park->getTable()}.Upz", $upz)
                        : $query->where("{$park->getTable()}.Upz", $upz);
                })
                ->when($request->has('neighborhood'), function ($query) use ($request) {
                    $neighborhood = $request->get('neighborhood');
                    return is_array($neighborhood)
                        ? $query->whereIn('Id_Barrio', $neighborhood)
                        : $query->where('Id_Barrio', $neighborhood);
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
                ->when($request->has('stratum'), function ($query) use ($request) {
                    $stratum = $request->get('stratum');
                    if (is_array($stratum) && count($stratum) > 0)
                        return $query->whereIn('Estrato', $stratum);

                    return $query;
                })
                ->when($request->has('park_type'), function ($query) use ($request) {
                    if (is_array($request->get('park_type')) && count($request->get('park_type')) > 0)
                        return $query->whereIn('Id_Tipo', $request->get('park_type'));
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
