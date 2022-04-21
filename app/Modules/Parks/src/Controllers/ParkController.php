<?php

namespace App\Modules\Parks\src\Controllers;


use App\Models\Security\User;
use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Exports\DashboardExport;
use App\Modules\Parks\src\Exports\Excel as ExcelRaw;
use App\Modules\Parks\src\Exports\ParkExport;
use App\Modules\Parks\src\Models\AssignedPark;
use App\Modules\Parks\src\Models\EconomicUsePark;
use App\Modules\Parks\src\Models\Park;
use App\Modules\Parks\src\Models\ParkEndowment;
use App\Modules\Parks\src\Request\AssignParkRequest;
use App\Modules\Parks\src\Request\ParkExcelRequest;
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

/**
 * @group Parques
 *
 * API para la gestión y consulta de datos de Parques.
 */
class ParkController extends Controller
{
    /**
     * Initialise common request params
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api')->only(['store', 'update', 'destroy', 'ownedKeys', 'owned', 'assignParks']);
        $this->middleware(Roles::actions(Park::class, 'create_or_manage'))->only('store');
        // $this->middleware(Roles::actions(Park::class, 'update_or_manage'))->only('update');
        $this->middleware(Roles::actions(Park::class, 'destroy_or_manage'))->only('destroy');
    }

    /**
     * @group Parques
     *
     * Buscador de parques
     *
     * Despliega una lista de coincidencias de parques según los criterios de búsqueda
     *
     * @param ParkFinderRequest $request
     * @return JsonResponse
     */
    public function index(ParkFinderRequest $request)
    {
        $parksId = [];
        if (isset($this->query)) {
            $parksId = Park::query()->search($this->query)
                ->orWhere('Id_IDRD', 'like', "%{$this->query}%")
                ->get(['Id'])->pluck('Id')->toArray();
        }
        $parks = $this->setQuery(Park::query(), (new Park)->getSortableColumn($this->column))
            ->select( ['Id', 'Id_IDRD', 'Nombre', 'Direccion', 'Upz', 'Id_Localidad', 'Id_Tipo'] )
            ->when($this->query, function ($query) use($parksId) {
                return $query->whereIn('Id', $parksId);
            })
            ->when(request()->has('locality_id'), function ($query) use ($request) {
                $localities = $request->get('locality_id');
                return is_array($localities)
                    ? $query->whereIn('Id_Localidad', $localities)
                    : $query->where('Id_Localidad', $localities);
            })
            ->when(request()->has('upz_id'), function ($query) use ($request) {
                $upz = $request->get('upz_id');
                return is_array($upz)
                    ? $query->whereIn('Upz', $upz)
                    : $query->where('Upz', $upz);
            })
            ->when(request()->has('neighborhood_id'), function ($query) use ($request) {
                $neighborhood = $request->get('neighborhood_id');
                return is_array($neighborhood)
                    ? $query->whereIn('Id_Barrio', $neighborhood)
                    : $query->where('Id_Barrio', $neighborhood);
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
            ->when($request->has('stratum'), function ($query) use ($request) {
                $stratum = $request->get('stratum');
                if (is_array($stratum) && count($stratum) > 0)
                    return $query->whereIn('Estrato', $stratum);

                return $query;
            })
            ->when($request->has('endowment_id'), function ($query) use ($request) {
                $endowment = $request->get('endowment_id');
                return $query->whereHas('park_endowment', function ($query) use ($endowment) {
                   return $query->where('Id_Dotacion', $endowment);
                });
            })
            ->when(request()->has('enclosure'), function ($query) use ($request) {
                $types = $request->get('enclosure');
                return is_array($types)
                    ? $query->whereIn('Cerramiento', $types)
                    : $query->where('Cerramiento', $types);
            })
            ->orderBy((new Park)->getSortableColumn($this->column), $this->order)
            ->paginate($this->per_page);
        return $this->success_response( ParkFinderResource::collection( $parks ) );
    }

    /**
     * @group Parques
     *
     * Reporte en excel de parques
     *
     * Genera un reporte en Excel (.xlsx) codificado en Base64 según filtros especificados.
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
        // return (new ParkExport($request))->download('REPORTE_PARQUES.xlsx', Excel::XLSX);
        $file = ExcelRaw::raw(new DashboardExport($request), Excel::XLSX);
        $name = random_img_name();
        $response =  array(
            'name' => toUpper(str_replace(' ', '-', __('parks.excel.title')))."-$name.xlsx",
            'file' => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,".base64_encode($file)
        );
        return $this->success_message($response);
    }

    /**
     * @group Parques
     *
     * Ver parque
     *
     * Muesta información detallada de un parque en específico.
     *
     * @urlParam park string required Código o ID del parque. Example: 9
     * @responseFile responses/parks/park.show.json
     *
     * @param $park
     * @return JsonResponse
     */
    public function show($park)
    {
        $data = Park::with('rupis', 'story', 'history')
                    ->when(strpos($park, '-'), function ($query) use ($park) {
                        return $query->where('Id_IDRD', $park);
                    })
                    ->when(!strpos($park, '-'), function ($query) use ($park) {
                        return $query->where('Id', $park);
                    })
                    ->first();
        if ( $data ) {
            return $this->success_response(
                new ParkResource( $data )
            );
        }

        return $this->error_response(__('parks.handler.park_does_not_exist', ['code' => $park]));
    }

    /**
     * @group Parques
     *
     * Crear Parque
     *
     * Crea un parque con información específica.
     *
     * @authenticated
     * @response 201 {
     *      "data": "Datos almacenados satisfactoriamente",
     *      "details": null,
     *      "code": 201,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
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
     * @group Parques
     *
     * Actualizar parque.
     *
     * Actualiza información de un parque en específico
     *
     * @authenticated
     * @response {
     *      "data": "Datos actualizados satisfactoriamente",
     *      "details": null,
     *      "code": 200,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     * @param UpdateParkRequest $request
     * @param Park $park
     * @return JsonResponse
     */
    public function update(UpdateParkRequest $request, Park $park)
    {
        $filled = $park->transformRequest( $request->validated() );
        $park->fill($filled);
        if (auth('api')->user()->can(Roles::can(Park::class, 'manage'), Park::class)) {
            $park->save();
            return $this->success_message(__('validation.handler.updated'));
        }
        if (auth('api')->user()->can(Roles::can(Park::class, 'update'), $park)) {
            $owned = AssignedPark::where('user_id', auth('api')->user()->id)
                                    ->where('park_id', $park->Id)
                                    ->count();
            abort_unless($owned > 0, Response::HTTP_FORBIDDEN, __('validation.handler.unauthorized'));
            $park->save();
            return $this->success_message(__('validation.handler.updated'));
        }
        return $this->error_response(__('validation.handler.unauthorized'), Response::HTTP_FORBIDDEN);
    }

    /**
     * @group Parques
     *
     * Eliminar parque.
     *
     * @authenticated
     * @response {
     *      "data": "Datos eliminados satisfactoriamente",
     *      "details": null,
     *      "code": 204,
     *      "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     * @param Park $park
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Park $park)
    {
        $park->delete();
        return $this->success_message(
        __('validation.handler.deleted'),
        Response::HTTP_OK,
        Response::HTTP_NO_CONTENT
    );
    }

    /**
     * @group Parques - Parques Asignados
     *
     * Ids de parques asignados.
     *
     * Muestra un listado de ids de los parques asignados al usuario autenticado.
     *
     * @authenticated
     * @responseFile responses/parks/ownedKey.get.json
     *
     * @return JsonResponse
     */
    public function ownedKeys()
    {
        $owned = AssignedPark::where('user_id', auth()->user()->id)
                             ->get()->pluck('park_id')
                             ->map(function ($model) {
                                 return (int) $model;
                             })
                             ->toArray();
        return $this->success_message($owned);
    }

    /**
     * @group Parques - Parques Asignados
     *
     * Buscador de Parques Asignados
     *
     * Muestra el listado de los parques asignados al usuario autenticado
     *
     * @authenticated
     * @responseFile responses/parks/assigned.get.json
     *
     * @param ParkFinderRequest $request
     * @return JsonResponse
     */
    public function owned(ParkFinderRequest $request)
    {
        $owned = AssignedPark::where('user_id', auth()->user()->id)
                ->get()->pluck('park_id')->toArray();
        $parks = Park::query()
                ->whereKey($owned)
                ->select( ['Id', 'Id_IDRD', 'Nombre', 'Direccion', 'Upz', 'Id_Localidad', 'Id_Tipo'] )
                ->when($this->query, function ($query) use($owned) {
                    return $query->search($this->query)
                            ->whereKey($owned)
                           ->orWhere(function ($query) use($owned) {
                               return $query->where('Id_IDRD', 'like', "%{$this->query}%")
                                   ->whereKey('Id', $owned);
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
     * @group Parques - Parques Asignados
     *
     * Parques Asignados a Usuario
     *
     * Muestra un listado de los parques asignados a un usuario en específico.
     *
     * @urlParam user int required Id del usuario con parques asignados. Example: 1
     * @authenticated
     * @responseFile responses/parks/assigned.get.json
     *
     * @param User $user
     * @return JsonResponse
     */
    public function showOwned(User $user)
    {
        $owned = AssignedPark::where('user_id', $user->id)
            ->get()->pluck('park_id')->toArray();
        $parks = Park::query()
            ->whereKey($owned)
            ->select( ['Id', 'Id_IDRD', 'Nombre', 'Direccion', 'Upz', 'Id_Localidad', 'Id_Tipo'] )
            ->paginate($this->per_page);

        return $this->success_response( ParkFinderResource::collection( $parks ) );
    }

    /**
     * @group Parques - Parques Asignados
     *
     * Deasociar un parque asignado a un usuario
     *
     * Eliminar permisos a parques asignados y desasigna el parque de un usuario en específico.
     *
     * @urlParam user int required ID de usuarios con parques asignados. Example: 3
     * @urlParam park int required ID del parque. Example: 9
     *
     * @authenticated
     * @response {
     *  "data": "Datos eliminados satisfactoriamente",
     *  "details": null,
     *  "code": 204,
     *  "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
     * @param User $user
     * @param Park $park
     * @return JsonResponse
     */
    public function destroyOwned(User $user, Park $park)
    {
        AssignedPark::where('user_id', $user->id)
                    ->where('park_id', $park->Id)->delete();
        $user->disallow(Roles::can(Park::class, 'update'), $park);

        return $this->success_message(__('validation.handler.deleted'));
    }

    /**
     * @group Parques - Parques Asignados
     *
     * Deasociar todos los parques asignados a un usuario
     *
     * Elimina la asignación de todos los parques a un usuario en específico.
     *
     * @authenticated
     * @response {
     *  "data": "Datos eliminados satisfactoriamente",
     *  "details": null,
     *  "code": 204,
     *  "requested_at": "2021-09-20T17:52:01-05:00"
     * }
     *
     * @param User $user
     * @return JsonResponse
     */
    public function destroyAllOwned(User $user)
    {
        $parks = AssignedPark::where('user_id', $user->id)->get();
        foreach ($parks as $park) {
            $p = Park::find($park->park_id);
            $user->disallow(Roles::can(Park::class, 'update'), $p);
        }
        AssignedPark::where('user_id', $user->id)->delete();
        return $this->success_message(__('validation.handler.deleted'));
    }

    /**
     * @group Parques - Parques Asignados
     *
     * Asignar Parque a Usuario
     *
     * Asigna la administración de un parque a un usuario en específico.
     * Puede asignar los parques de toda una localidad, upz, barrio o parque específico.
     *
     * @authenticated
     * @response {}
     *
     * @param AssignParkRequest $request
     * @return JsonResponse
     */
    public function assignParks(AssignParkRequest $request)
    {
        $form = AssignedPark::query();
        $user = User::query()->find($request->get('user_id'));
        switch ($request->get('type_assignment')) {
            case 'locality':
                $parks = Park::query()
                            ->select(['Id', 'Id_Localidad'])
                            ->where('Id_Localidad', $request->get('locality_id'))
                            ->get();
                foreach ($parks as $park) {
                    $user->allow(Roles::can(Park::class, 'update'), $park);
                    $form->updateOrCreate([
                        'user_id'   => $request->get('user_id'),
                        'park_id'   =>  $park->Id,
                    ]);
                }
                break;
            case 'upz':
                $parks = Park::query()
                    ->select(['Id', 'Upz'])
                    ->where('Upz', $request->get('upz_code'))
                    ->get();
                foreach ($parks as $park) {
                    $user->allow(Roles::can(Park::class, 'update'), $park);
                    $form->updateOrCreate([
                        'user_id'   => $request->get('user_id'),
                        'park_id'   =>  $park->Id,
                    ]);
                }
                break;
            case 'neighborhood':
                $parks = Park::query()
                    ->select(['Id', 'Id_Barrio'])
                    ->where('Id_Barrio', $request->get('neighborhood_id'))
                    ->get();
                foreach ($parks as $park) {
                    $user->allow(Roles::can(Park::class, 'update'), $park);
                    $form->updateOrCreate([
                        'user_id'   => $request->get('user_id'),
                        'park_id'   =>  $park->Id,
                    ]);
                }
                break;
            case 'manual':
                foreach ($request->get('park_id') as $id) {
                    $park = Park::find($id);
                    $user->allow(Roles::can(Park::class, 'update'), $park);
                    $form->updateOrCreate([
                        'user_id'   => $request->get('user_id'),
                        'park_id'   =>  $id,
                    ]);
                }
                break;
            case 'managed':
                $parks = Park::query()
                      ->select(['Id', 'Administracion'])
                      ->where('Administracion', 'IDRD')
                      ->get();
                foreach ($parks as $park) {
                    $user->allow(Roles::can(Park::class, 'update'), $park);
                    $form->updateOrCreate([
                           'user_id'   => $request->get('user_id'),
                           'park_id'   =>  $park->Id,
                    ]);
                }
                break;
        }
        return $this->success_message(
            __('validation.handler.success'),
            Response::HTTP_CREATED
        );
    }

    /**
     * @group Parques - Canchas Sintéticas
     *
     * Canchas Sintéticas
     *
     * En desarollo. Muestra un listado de las canchas sintéticas
     *
     * @return JsonResponse
     */
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

    /**
     * @group Parques - Diagramas/Renders
     *
     * Diagramas/Renders
     *
     * En desarollo. Muestra un listado de los parques que cuentan con diagramas.
     *
     * @return JsonResponse
     */
    public function diagrams()
    {
        $parks = Park::query()
            ->select( ['Id', 'Id_IDRD', 'Nombre', 'Direccion', 'Upz', 'Id_Localidad', 'Id_Tipo'] )
            ->where('Estado', 1)
            ->where('Id', '!=', 1)->paginate($this->per_page);
        return $this->success_response( ParkFinderResource::collection( $parks ) );
    }

    /**
     * @group Parques - Aprovechamiento Económico
     *
     * Aprovechamiento Económico
     *
     * En desarollo. Muestra un listado de los aprovechamientos económicos de un parque especificado.
     *
     * @urlParam park int required Id del parque. Example: 9
     *
     * @param $park
     * @return JsonResponse
     */
    public function economic(Park $park)
    {
        $data = EconomicUsePark::with('economic_use')
            ->whereHas('economic_use')
            ->where('IdParque', $park->getKey())
            ->get();
        if ( $data ) {
            return $this->success_response( EconomicUseParkResource::collection( $data ) );
        }

        return $this->error_response(__('parks.handler.park_does_not_exist', ['code' => $park]));
    }

    /**
     * @group Parques - Sectores Diagramas/Renders
     *
     * Sectores Diagramas/Renders
     *
     * En desarollo. Muestra breves datos de un parque y los sectores mapeados del render para mostrar información interactivamente.
     *
     * @urlParam park int required Id del parque. Example: 9
     * @responseFile responses/parks/sectors.get.json
     *
     * @param $park
     * @return JsonResponse
     */
    public function sectors($park)
    {
        $park = Park::with('sectors.endowments')
            ->select( ['Id', 'Id_IDRD', 'Nombre', 'Direccion', 'Upz', 'Id_Localidad', 'Id_Tipo', 'Estado'] )
            ->when(strpos($park, '-'), function ($query) use ($park) {
                return $query->where('Id_IDRD', $park);
            })
            ->when(!strpos($park, '-'), function ($query) use ($park) {
                return $query->where('Id', $park);
            })
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

    /**
     * @group Parques - Dotaciones
     *
     * Dotaciones
     *
     * En desarrollo. Muestra el listado de docationes de un parque especificado y un equipamiento especificado.
     *
     * @urlParam park int required Id del parque. Example: 9478
     * @urlParam equipment int required Id del equipamiento. Example: 4
     *
     * @param $park
     * @param $equipment
     * @return JsonResponse
     */
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
