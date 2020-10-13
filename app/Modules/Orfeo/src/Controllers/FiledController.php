<?php

namespace App\Modules\Orfeo\src\Controllers;

use App\Modules\Orfeo\src\Models\Attachment;
use App\Modules\Orfeo\src\Models\Dependency;
use App\Modules\Orfeo\src\Models\Filed;
use App\Modules\Orfeo\src\Resources\DependencyResource;
use App\Modules\Orfeo\src\Resources\FiledResource;
use App\Modules\Orfeo\src\Resources\HistoryResource;
use App\Modules\Orfeo\src\Resources\InformedResource;
use Carbon\Carbon;
use Illuminate\Database\Concerns\BuildsQueries;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class FiledController extends Controller
{
    public function countByMonth(Request $request)
    {
        $data = Filed::query()
            ->without(['user', 'dependency', 'city', 'document_type'])
            ->select(DB::raw('EXTRACT(MONTH FROM radi_fech_radi) AS month, COUNT(*) AS count'))
            ->whereBetween('radi_fech_radi', [now()->startOfYear()->format('Y-m-d H:i:s'), now()->endOfYear()->format('Y-m-d H:i:s')])
            ->groupBy( [DB::raw('EXTRACT(MONTH FROM radi_fech_radi)')] )
            ->orderBy('month')
            ->get()->map(function ($query) {
                return [
                    'month' => (int) $query->month,
                    'count' => (int) $query->count,
                ];
            });
        return $this->success_message($data);
    }

    public function countByDependency(Request $request)
    {
        $data = $this->getBuilder( $request, Filed::query() )
            ->without(['user', 'dependency', 'city', 'document_type'])
            ->select(DB::raw('radi_depe_actu, COUNT(*) AS count'))
            ->groupBy(['radi_depe_actu'])
            ->get()->map(function ($data) {
                return [
                    'id'  => isset( $data->radi_depe_actu ) ? (int) $data->radi_depe_actu : null,
                    'count'  => isset( $data->count ) ? (int) $data->count : null,
                    'name'  => isset( $data->dependency->depe_nomb ) ? toUpper($data->dependency->depe_nomb) : null,
                ];
            });
        return $this->success_message($data);
    }

    public function countByStatus(Request $request, $status)
    {
        $count = 0;
        $resource = $this->getBuilder( $request, Filed::query() )->without(['user', 'dependency', 'city', 'document_type']);
        switch ($status) {
            case 'filed':
                $noAttach = $this->getBuilder( $request, Filed::query() )->without(['user', 'dependency', 'city', 'document_type'])->doesntHave('attachments')->count();
                $filed = $resource->whereHas('attachments', function ($query) {
                return $query->where('anex_estado', Filed::FILED);
            })->count();
                $count = (int) $noAttach + (int) $filed;
                break;
            case 'principal':
                $count = $resource->whereHas('attachments', function ($query) {
                return $query->where('anex_estado', Filed::PRINCIPAL);
            })->count();
                break;
            case 'printed':
                $count = $resource->whereHas('attachments', function ($query) {
                    return $query->where('anex_estado', Filed::PRINTED);
                })->count();
                break;
            case 'sent':
                $count = $resource->whereHas('attachments', function ($query) {
                    return $query->where('anex_estado', Filed::SENT);
                })->count();
                break;
        }
        return $this->success_message($count);
    }

    public function countByFileType()
    {
        
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $resource = $this->getBuilder( $request, Filed::query() );
        return $this->success_response(
            FiledResource::collection( $resource->orderByDesc('radi_nume_radi')
                ->paginate($this->per_page)
            )
        );
    }

    public function calendar(Request $request)
    {
        $start = $request->has('start_date')
                ? Carbon::parse( $request->get('start_date') )
                : now()->startOfMonth();
        $final = $request->has('final_date')
            ? Carbon::parse( $request->get('final_date') )
            : now()->endOfMonth();
        $resource = $this->getBuilder( $request, Filed::query() )
                         ->where([
                             ['radi_fech_radi', '>=', $start->format('Y-m-d H:i:s')],
                             ['radi_fech_radi', '<=', $final->format('Y-m-d H:i:s')],
                         ]);
        return $this->success_response(
            FiledResource::collection( $resource->orderByDesc('radi_nume_radi')->get())
        );
    }

    /**
     * Display the specified resource.
     *
     * @param Filed $filed
     * @return JsonResponse
     */
    public function show(Filed $filed)
    {
        $resource = $filed->load(['attachments', 'history', 'associates']);
        return $this->success_response(new FiledResource( $resource ));
    }

    /**
     * Get a query builder instance
     *
     * @param Request $request
     * @param Builder $resource
     * @return BuildsQueries|Builder|mixed
     */
    public function getBuilder(Request $request, Builder $resource)
    {
        return $resource->when($request->has('document_type'), function ($query) use ( $request ) {
            return $query->whereIn('tdoc_codi', $request->get('document_type'));
        })->when( $request->has('city_id'), function ($query) use ( $request ) {
            return $query->whereIn('muni_codi', $request->get('city_id'));
        })->when( $request->has('current_user_id'), function ($query) use ( $request ) {
            return $query->whereIn('radi_usua_actu', $request->get('current_user_id'));
        })->when( $request->has('current_dependency_id'), function ($query) use ( $request ) {
            return $query->whereIn('radi_depe_actu', $request->get('current_dependency_id'));
        })->when( $request->has('query'), function ($query) use ( $request ) {
            return $query->where('radi_nume_radi', $request->get('query'));
        })->when( $request->has('where_has'), function ($query) use ( $request ) {
            foreach ( $request->get('where_has') as $relation ) {
                $query = $query->whereHas($relation);
            }
            return $query;
        })->when( $request->has('status'), function ($query) use ( $request ) {
            if ( (int) $request->get('status') == Filed::FILED ) {
                return $query->whereHas('attachments', function ($query) {
                    return $query->where('anex_estado', Filed::FILED);
                })->orWhereDoesntHave('attachments');
            } else {
                return $query->whereHas('attachments', function ($query) use ( $request ) {
                    return $query->where('anex_estado', (int) $request->get('status'));
                });
            }
        });
    }

    public function dependencies()
    {
        return $this->success_response( DependencyResource::collection( Dependency::all() ) );
    }

    public function informed(Filed $filed)
    {
        return $this->success_response(
            InformedResource::collection( $filed->informed()->paginate( $this->per_page ) )
        );
    }

    public function history(Filed $filed)
    {
        return $this->success_response(
            HistoryResource::collection( $filed->history()->paginate( $this->per_page ) )
        );
    }
}
