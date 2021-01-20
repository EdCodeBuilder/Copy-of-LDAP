<?php

namespace App\Modules\Parks\src\Controllers;


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
use Illuminate\Http\Response;

class ParkController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource with few data.
     *
     * @param ParkFinderRequest $request
     * @return JsonResponse
     */
    public function index(ParkFinderRequest $request): JsonResponse
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
                return $query->where('Id_Localidad', $request->get('locality_id'));
            })
            ->when(request()->has('type_id'), function ($query) use ($request) {
                return $query->where('Id_Tipo', $request->get('type_id'));
            })->paginate($this->per_page);
        return $this->success_response( ParkFinderResource::collection( $parks ) );
    }

    /**
     * Display the specified resource.
     *
     * @param $park
     * @return JsonResponse
     */
    public function show($park): JsonResponse
    {
        $data = Park::with('rupis', 'story')
                    ->where('Id_IDRD', $park)
                    ->orWhere('Id', $park)
                    ->first();
        if ( $data ) {
            return $this->success_response(
                new ParkResource( $data ),
                Response::HTTP_OK,
                [
                    'permissions' => [
                        'create' => true,
                        'update' => true,
                        'delete' => true,
                    ],
                ]
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
    public function store(ParkRequest $request): JsonResponse
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
    public function update(UpdateParkRequest $request, Park $park): JsonResponse
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
