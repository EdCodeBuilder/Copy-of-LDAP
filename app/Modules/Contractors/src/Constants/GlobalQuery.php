<?php

namespace App\Modules\Contractors\src\Constants;

use Illuminate\Database\Concerns\BuildsQueries;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class GlobalQuery
{
    /**
     * @param $request
     * @param Builder $builder
     * @return BuildsQueries|Builder|mixed
     */
    public static function query($request, Builder $builder)
    {
        return $builder->when($request->has('doesnt_have_arl'), function ($q) {
            return $q->whereNull('modifiable')->whereHas('contracts', function ($query) {
                return $query->where('contract_type_id', '!=', 3)
                    ->whereDate('final_date', '>=', now()->format('Y-m-d'))
                    ->withCount([
                        'files as arl_files_count' => function ($q) {
                            return $q->where('file_type_id', 1);
                        },
                        'files as other_files_count' => function ($q) {
                            return $q->where('file_type_id', '!=', 1);
                        },
                    ])->having('arl_files_count', 0);
            });
        })->when(
            $request->has(['start_date', 'final_date']),
            function ($query) use ($request) {
                return $query->where('contracts_view.start_date', '>=', $request->get('start_date'))
                    ->where('contracts_view.final_date', '<=', $request->get('final_date'));
            }
        )->when($request->has('doesnt_have_secop'), function ($q) {
            return $q->whereHas('contracts', function ($query) {
                return $query->where('contract_type_id', '!=', 3)
                    ->whereDate('final_date', '>=', now()->format('Y-m-d'))
                    ->withCount([
                        'files as arl_files_count' => function ($q) {
                            return $q->where('file_type_id', 1);
                        },
                        'files as other_files_count' => function ($q) {
                            return $q->where('file_type_id', '!=', 1);
                        },
                    ])->having('other_files_count', 0);
            });
        })->when($request->has('query'), function ($q) use ($request) {
            $data = toLower($request->get('query'));
            return $q->whereHas('contracts', function ($query) use ($data) {
                return $query->where('contract', 'like', "%{$data}%");
            })->orWhere('name', 'like', "%{$data}%")
                ->orWhere('id', 'like', "%{$data}%")
                ->orWhere('surname', 'like', "%{$data}%")
                ->orWhere('document', 'like', "%{$data}%");
        })->when($request->has('doesnt_have_data'), function ($q) use ($request) {
            return $q->whereNotNull('modifiable');
        });
    }
}
