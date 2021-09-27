<?php

namespace App\Modules\Contractors\src\Exports;

use App\Modules\Contractors\src\Models\Contractor;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use phpDocumentor\Reflection\Types\Collection;

class ContractorsExportBKP implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    /**
     * @var Collection
     */
    private $request;

    /**
     * OrfeoExport constructor.
     * @param array $request
     */
    public function __construct(array $request)
    {
        $this->request = collect($request);
    }

    /**
     * @return Builder
     */
    public function query()
    {
        return $this->queryBuilder(Contractor::query());
    }

    public function headings(): array {
        return [
            'ID',
            'TIPO DE TRÁMITE',
            'CONTRATO',
            'CONTRATO ACTIVO',
            'SE SUMINISTRA TRANSPORTE',
            'CARGO',
            'FECHA INICIAL',
            'FECHA FINAL',
            'FECHA INICIAL DE SUSPENSIÓN',
            'FECHA FINAL DE SUSPENSIÓN',
            'VALOR TOTAL DEL CONTRATO O ADICIÓN',
            'DÍAS QUE NO TRABAJA',
            'NIVEL DE RIESGO',
            'SUBDIRECCIÓN',
            'DEPENDENCIA',
            'OTRA SUBDIRECCIÓN O DEPEDENCIA',
            'EMAIL SUPERVISOR',
            'CREADOR DEL CONTRATO',
            'ARCHIVOS SECOP',
            'ARCHIVOS ARL',
            'TIPO DE DOCUMENTO',
            'DOCUMENTO',
            'NOMBRES',
            'APELLIDOS',
            'FECHA DE NACIMIENTO',
            'PAÍS DE NACIMIENTO',
            'DEPTO DE NACIMIENTO',
            'CIUDAD DE NACIMIENTO',
            'EDAD',
            'SEXO',
            'CORREO PERSONAL',
            'CORREO INSTITUCIONAL',
            'TELÉFONO',
            'EPS',
            'OTRA EPS',
            'AFP',
            'OTRA AFP',
            'PAÍS DE RESIDENCIA',
            'DEPTO DE RESIDENCIA',
            'CIUDAD DE RESIDENCIA',
            'LOCALIDAD',
            'UPZ',
            'BARRIO',
            'OTRO BARRIO',
            'DIRECCIÓN',
            'NIVEL ACADÉMICO',
            'GRADUADO',
            'AÑO O SEMESTRE APROBADO',
            'TÍTULO ACADÉMICO',
            'USUARIO QUE REGISTRÓ CONTRATISTA',
            'FECHA DE CREACIÓN',
            'FECHA DE MODIFICACIÓN',
        ];
    }

    public function map($row): array
    {
        $contract = $row->contracts()->latest()->first();
        $transport = isset($contract->transport) ? (boolean) $contract->transport : null;
        $transport_text = null;
        if (!is_null($transport)) {
            $transport_text = $transport ? 'SI' : 'NO';
        }
        $career = $row->careers()->latest()->first();
        $graduate = isset($career->graduate) ? (boolean) $career->graduate : null;
        $graduate_text = null;
        if (!is_null($graduate)) {
            $graduate_text = $graduate ? 'SI' : 'NO';
        }
        $date = $contract->final_date ?? null;
        if (!is_null($date)) {
            $date = now()->lte($date) ? 'ACTIVO' : 'INACTIVO';
        }
        return [
            'id'                    =>  isset($row->id) ? (int) $row->id : null,
            // Start Contract Data
            'contract_type'         => $contract->contract_type->name ?? null,
            'contract'              => $contract->contract ?? null,
            'is_active'              => $date,
            'transport_text'                    =>  $transport_text,
            'position'                          => $contract->position ?? null,
            'start_date'                        =>  isset($contract->start_date) ? $contract->start_date->format('Y-m-d') : null,
            'final_date'                        =>  isset($contract->final_date) ? $contract->final_date->format('Y-m-d') : null,
            'start_suspension_date'                        =>  isset($contract->start_suspension_date) ? $contract->start_suspension_date->format('Y-m-d') : null,
            'final_suspension_date'                        =>  isset($contract->final_suspension_date) ? $contract->final_suspension_date->format('Y-m-d') : null,
            'total'                             =>  isset($contract->total) ? (int) $contract->total : null,
            'day'                               =>  isset($contract->day) ? $contract->getOriginal('day') : null,
            'risk'                              => $contract->risk ?? null,
            'subdirectorate'                    => $contract->subdirectorate->name ?? null,
            'dependency'                        => $contract->dependency->name ?? null,
            'other_dependency_subdirectorate'   => $contract->other_dependency_subdirectorate ?? null,
            'supervisor_email'                  => $contract->supervisor_email ?? null,
            'lawyer'                            => $contract->lawyer->full_name ?? null,
            'secop_file'                        => $contract->files->where('file_type_id', 2)->count(),
            'arl_file'                          => $contract->files->where('file_type_id', 1)->count(),
            // End Contract Data
            'document_type'         => $row->document_type->name ?? null,
            'document'              => $row->document ?? null,
            'name'                  => $row->name ?? null,
            'surname'               => $row->surname ?? null,
            'birthdate'             =>  isset($row->birthdate) ? $row->birthdate->format('Y-m-d H:i:s') : null,
            'birthdate_country'     => $row->birthdate_country->name ?? null,
            'birthdate_state'       => $row->birthdate_state->name ?? null,
            'birthdate_city'        => $row->birthdate_city->name ?? null,
            'age'                   =>  isset($row->birthdate) ? $row->birthdate->age : null,
            'sex'                   => $row->sex->name ?? null,
            'email'                 => $row->email ?? null,
            'institutional_email'   => $row->institutional_email ?? null,
            'phone'                 => $row->phone ?? null,
            'eps_name'              => $row->eps_name->name ?? null,
            'eps'                   => $row->eps ?? null,
            'afp_name'              => $row->afp_name->name ?? null,
            'afp'                   => $row->afp ?? null,
            'residence_country'     => $row->residence_country->name ?? null,
            'residence_state'       => $row->residence_state->name ?? null,
            'residence_city'        => $row->residence_city->name ?? null,
            'locality'              =>  $this->setLocalityName($row),
            'upz'                   =>  $this->setUpzName($row),
            'neighborhood_name'     =>  $this->setNeighborhoodName($row),
            'neighborhood'          => $row->neighborhood ?? null,
            'address'               => $row->address ?? null,
            'academic_level'        => $career->career->level->name ?? null,
            'graduate_text'         =>  $graduate_text,
            'year_approved'         =>  isset($career->year_approved) ? (int) $career->year_approved : null,
            'career'                => $career->career->name ?? null,
            'user'                  => $row->user->full_name ?? null,
            'created_at'            =>  isset($row->created_at) ? $row->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'            =>  isset($row->updated_at) ? $row->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }


    public function queryBuilder(Builder $builder)
    {
        $request = $this->request;
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
        })->when($request->has('doesnt_have_secop'), function ($q) {
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

    public function setNeighborhoodName($row)
    {
        $state = isset($row->residence_city_id) ? (int) $row->residence_city_id : null;
        $neighborhood = $row->neighborhood_name->name ?? null;
        return !is_null($state) && $state != 12688 && is_null($neighborhood) ? 'OTRO' : $neighborhood;
    }
    public function setLocalityName($row)
    {
        $state = isset($row->residence_city_id) ? (int) $row->residence_city_id : null;
        $locality = $row->locality->name ?? null;
        return !is_null($state) && $state != 12688 && is_null($locality) ? 'OTRO' : $locality;
    }
    public function setUpzName($row)
    {
        $state = isset($row->residence_city_id) ? (int) $row->residence_city_id : null;
        $upz = $row->upz->name ?? null;
        return !is_null($state) && $state != 12688 && is_null($upz) ? 'OTRO' : $upz;
    }
}
