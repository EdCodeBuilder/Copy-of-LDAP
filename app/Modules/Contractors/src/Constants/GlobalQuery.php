<?php

namespace App\Modules\Contractors\src\Constants;

use App\Modules\Contractors\src\Models\Contract;
use Illuminate\Database\Concerns\BuildsQueries;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\This;

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
                $contracts = new GlobalQuery();
                return $query->whereIn('id', $contracts->contracts());
            });
        })->when(
            $request->has(['start_date', 'final_date']),
            function ($query) use ($request) {
                return $query->where('contracts_view.start_date', '>=', $request->get('start_date'))
                    ->where('contracts_view.final_date', '<=', $request->get('final_date'));
            }
        )->when($request->has('doesnt_have_secop'), function ($q) {
            return $q->whereHas('contracts', function ($query) {
                $contracts = new GlobalQuery();
                return $query->whereIn('id', $contracts->contracts('other_files_count'));
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

    public function contracts($files = 'arl_files_count')
    {
        return Contract::query()
            ->where('contract_type_id', '!=', 3)
            ->whereDate('final_date', '>=', now()->format('Y-m-d'))
            ->latest()
            ->withCount([
                'files as arl_files_count' => function ($q) {
                    return $q->where('file_type_id', 1);
                },
                'files as other_files_count' => function ($q) {
                    return $q->where('file_type_id', '!=', 1);
                },
            ])
            ->get(['id', 'contractor_id', 'arl_files_count', 'created_at'])
            ->sortByDesc('created_at')
            ->unique('contractor_id')
            ->filter(function ($value, $key) use ($files) {
                return $value[$files] == 0;
            })->pluck('id')->toArray();
    }
}
