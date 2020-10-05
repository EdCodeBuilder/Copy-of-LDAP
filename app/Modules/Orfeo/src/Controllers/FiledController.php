<?php

namespace App\Modules\Orfeo\src\Controllers;

use App\Modules\Orfeo\src\Models\Filed;
use App\Modules\Orfeo\src\Resources\FiledResource;
use Illuminate\Database\Concerns\BuildsQueries;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FiledController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $filed = $this->getBuilder( $request, Filed::query() )->whereHas('attachments', function ($query) {
            return $query->where('anex_estado', Filed::FILED);
        })->count();
        $noAttach = $this->getBuilder( $request, Filed::query() )->whereDoesntHave('attachments')->count();
        $total = $this->getBuilder( $request, Filed::query() )->count();

        $principal = $this->getBuilder( $request, Filed::query() )->whereHas('attachments', function ($query) {
                                        return $query->where('anex_estado', Filed::PRINCIPAL);
                                    })->count();
        $printed = $this->getBuilder( $request, Filed::query() )->whereHas('attachments', function ($query) {
                                        return $query->where('anex_estado', Filed::PRINTED);
                                    })->count();
        $sent = $this->getBuilder( $request, Filed::query() )->whereHas('attachments', function ($query) {
                                        return $query->where('anex_estado', Filed::SENT);
                                    })->count();

        $resource = $this->getBuilder( $request, Filed::query() );

        return $this->success_response(
            FiledResource::collection($resource->orderByDesc('radi_fech_radi')
                ->paginate($this->per_page)
            )->additional([
                'counter'   =>  [
                    'total'     =>  $total,
                    'status'    =>  [
                        'pending' => $noAttach,
                        'filed' =>  (int) $filed,
                        'principal' =>  $principal,
                        'printed' =>  $printed,
                        'sent' =>  $sent,
                    ]
                ],
            ])
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
        $resource = $filed->load(['attachments', 'history', 'associates', 'informed']);
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
            return $query->whereIn('muni_codi', $request->get('document_type'));
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
}
