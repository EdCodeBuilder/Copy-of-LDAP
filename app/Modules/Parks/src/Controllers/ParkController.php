<?php

namespace App\Modules\Parks\src\Controllers;


use App\Modules\Parks\src\Exports\ParkExport;
use App\Modules\Parks\src\Models\EconomicUsePark;
use App\Modules\Parks\src\Models\Park;
use App\Modules\Parks\src\Models\ParkEndowment;
use App\Modules\Parks\src\Request\ParkFinderRequest;
use App\Modules\Parks\src\Request\ParkRequest;
use App\Modules\Parks\src\Request\UpdateParkRequest;
use App\Modules\Parks\src\Resources\EconomicUseParkResource;
use App\Modules\Parks\src\Resources\EndowmentResource;
use App\Modules\Parks\src\Resources\ParkEndowmentResource;
use App\Modules\Parks\src\Resources\ParkFinderResource;
use App\Modules\Parks\src\Resources\ParkResource;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ParkController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware(['can:manage-parks', 'auth:api'], ['only' => 'store', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource with few data.
     *
     * @param ParkFinderRequest $request
     * @return JsonResponse
     */
    public function index(ParkFinderRequest $request)
    {
        $parks = Park::query()
            ->select( ['Id', 'Id_IDRD', 'Nombre', 'Direccion', 'Upz', 'Id_Localidad', 'Id_Tipo'] )
            ->when($this->query, function ($query) {
                $query->where(function ($query) {
                    return $query
                        ->where('Id_IDRD', 'LIKE', "%{$this->query}%")
                        ->orWhere('Nombre', 'LIKE', "%{$this->query}%")
                        ->orWhere('Direccion', 'LIKE', "%{$this->query}%");
                });
            })
            ->when(request()->has('locality_id'), function ($query) use ($request) {
                $localities = $request->get('locality_id');
                return is_array($localities)
                    ? $query->whereIn('Id_Localidad', $localities)
                    : $query->where('Id_Localidad', $localities);
            })
            ->when(request()->has('type_id'), function ($query) use ($request) {
                $types = $request->get('type_id');
                return is_array($types)
                    ? $query->whereIn('Id_Tipo', $types)
                    : $query->where('Id_Tipo', $types);
            })
            ->when(request()->has('vigilance'), function ($query) use ($request) {
                return $query->where('Vigilancia', $request->get('vigilance'));
            })
            ->when(request()->has('enclosure'), function ($query) use ($request) {
                $types = $request->get('enclosure');
                return is_array($types)
                    ? $query->whereIn('Cerramiento', $types)
                    : $query->where('Cerramiento', $types);
            })

            ->paginate($this->per_page);
        return $this->success_response( ParkFinderResource::collection( $parks ) );
    }

    /**
     * @param Request $request
     * @return Response|BinaryFileResponse
     */
    public function excel(Request $request)
    {
        return (new ParkExport($request))->download('REPORTE_PARQUES.xlsx', Excel::XLSX);
    }

    /**
     * Display the specified resource.
     *
     * @param $park
     * @return JsonResponse
     */
    public function show($park)
    {
        $data = Park::with('rupis', 'story')
                    ->where('Id_IDRD', $park)
                    ->orWhere('Id', $park)
                    ->first();
        if ( $data ) {
            return $this->success_response(
                new ParkResource( $data )
            );
        }

        return $this->error_response(__('parks.handler.park_does_not_exist', ['code' => $park]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ParkRequest $request
     * @return JsonResponse
     */
    public function store(ParkRequest $request)
    {
        $park = new Park();
        $filled = $park->transformRequest( $request->validated() );
        $park->fill($filled);
        $park->save();
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateParkRequest $request
     * @param Park $park
     * @return JsonResponse
     */
    public function update(UpdateParkRequest $request, Park $park)
    {
        $filled = $park->transformRequest( $request->validated() );
        $park->fill($filled);
        $park->save();
        return $this->success_message(__('validation.handler.updated'));
    }

    public function synthetic()
    {
        $id = 19; // Material del piso sintético
        $parks = ParkEndowment::with([
                                    'park' => function( $query ) {
                                        return $query->with(['location', 'scale', 'upz_name']);
                                    }
                                ])
                                ->where('MaterialPiso', $id)
                                ->select(['Id', 'Id_Parque', 'Descripcion', 'Id_Dotacion'])
                                ->paginate($this->per_page);
        return $this->success_response( ParkEndowmentResource::collection( $parks ) );
    }

    public function diagrams()
    {
        $parks = Park::query()
            ->select( ['Id', 'Id_IDRD', 'Nombre', 'Direccion', 'Upz', 'Id_Localidad', 'Id_Tipo'] )
            ->where('Estado', 1)
            ->where('Id', '!=', 1)->paginate($this->per_page);
        return $this->success_response( ParkFinderResource::collection( $parks ) );
    }

    public function economic($park)
    {
        $data = EconomicUsePark::with('economic_use')->whereHas('economic_use')->where('IdParque', $park)->get();
        if ( $data ) {
            return $this->success_response( EconomicUseParkResource::collection( $data ) );
        }

        return $this->error_response(__('parks.handler.park_does_not_exist', ['code' => $park]));
    }

    public function sectors($park)
    {
        $park = Park::with('sectors.endowments')
            ->select( ['Id', 'Id_IDRD', 'Nombre', 'Direccion', 'Upz', 'Id_Localidad', 'Id_Tipo', 'Estado'] )
            ->where('Id_IDRD', $park)
            ->where('Estado', true)
            ->first();
        if ( $park ) {
            $type = $park->sectors->where('tipo', 1)->count();
            return $this->success_message([
                'park'  =>  new ParkFinderResource($park),
                'type'  =>  $type,
            ]);
        }
        return $this->error_response(__('validation.handler.resource_not_found_url'), Response::HTTP_NOT_FOUND);
    }

    public function fields($park, $equipment)
    {
        $parks = ParkEndowment::whereHas('endowment', function ($query) use ($equipment) {
                return $query->where('Id_Equipamento', $equipment);
            })
            ->where('Id_Parque', $park)
            ->paginate($this->per_page);
        return $this->success_response( EndowmentResource::collection( $parks ) );
    }
}
