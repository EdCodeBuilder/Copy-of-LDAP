<?php

namespace App\Modules\Contractors\src\Exports;

use App\Modules\Contractors\src\Constants\Roles;
use App\Modules\Contractors\src\Models\Contractor;
use App\Modules\Orfeo\src\Models\Attachment;
use App\Modules\Orfeo\src\Models\Filed;
use App\Modules\Orfeo\src\Resources\FiledResource;
use App\Modules\Orfeo\src\Resources\FolderResource;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ContractorsExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    /**
     * @var Request
     */
    private $request;

    /**
     * OrfeoExport constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return $this->queryBuilder( $this->request, Contractor::query() );
    }

    public function headings(): array {
        return [
            'ID',
            'TIPO DE TRÁMITE',
            'CONTRATO',
            'SE SUMINISTRA TRANSPORTE',
            'CARGO',
            'FECHA INICIAL',
            'FECHA FINAL',
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
            'USUARIO QUE REGISTRÓ CONTRATISTA',
            'FECHA DE CREACIÓN',
            'FECHA DE MODIFICACIÓN',
        ];
    }

    public function map($row): array
    {
        $contract = $row->contracts()->latest()->first();
        $transport = isset($contract->transport) ? $contract->transport : null;
        return [
            'id'                    =>  isset($row->id) ? (int) $row->id : null,
            // Start Contract Data
            'contract_type'         =>  isset($contract->contract_type->name) ? $contract->contract_type->name : null,
            'contract'              =>  isset($contract->contract) ? $contract->contract : null,
            'transport_text'                    =>  $transport ? 'SI' : 'NO',
            'position'                          =>  isset($contract->position) ? $contract->position : null,
            'start_date'                        =>  isset($contract->start_date) ? $contract->start_date->format('Y-m-d') : null,
            'final_date'                        =>  isset($contract->final_date) ? $contract->final_date->format('Y-m-d') : null,
            'total'                             =>  isset($contract->total) ? (int) $contract->total : null,
            'day'                               =>  isset($contract->day) ? $contract->getOriginal('day') : null,
            'risk'                              =>  isset($contract->risk) ? $contract->risk : null,
            'subdirectorate'                    =>  isset($contract->subdirectorate->name) ? $contract->subdirectorate->name : null,
            'dependency'                        =>  isset($contract->dependency->name) ? $contract->dependency->name : null,
            'other_dependency_subdirectorate'   =>  isset($contract->other_dependency_subdirectorate) ? $contract->other_dependency_subdirectorate : null,
            'supervisor_email'                  =>  isset($contract->supervisor_email) ? $contract->supervisor_email : null,
            'lawyer'                            =>  isset($contract->lawyer->full_name) ? $contract->lawyer->full_name : null,
            'secop_file'                        => $contract->files->where('file_type_id', 2)->count(),
            'arl_file'                          => $contract->files->where('file_type_id', 1)->count(),
            // End Contract Data
            'document_type'         =>  isset($row->document_type->name) ? $row->document_type->name : null,
            'document'              =>  isset($row->document) ? $row->document : null,
            'name'                  =>  isset($row->name) ? $row->name : null,
            'surname'               =>  isset($row->surname) ? $row->surname : null,
            'birthdate'             =>  isset($row->birthdate) ? $row->birthdate->format('Y-m-d H:i:s') : null,
            'age'                   =>  isset($row->birthdate) ? $row->birthdate->age : null,
            'sex'                   =>  isset($row->sex) ? $row->sex : null,
            'email'                 =>  isset($row->email) ? $row->email : null,
            'institutional_email'   =>  isset($row->institutional_email) ? $row->institutional_email : null,
            'phone'                 =>  isset($row->phone) ? $row->phone : null,
            'eps_name'              =>  isset($row->eps_name->name) ? $row->eps_name->name : null,
            'eps'                   =>  isset($row->eps) ? $row->eps : null,
            'afp_name'              =>  isset($row->afp_name->name) ? $row->afp_name->name : null,
            'afp'                   =>  isset($row->afp) ? $row->afp : null,
            'residence_country'     =>  isset($row->residence_country->name) ? $row->residence_country->name : null,
            'residence_state'       =>  isset($row->residence_state->name) ? $row->residence_state->name : null,
            'residence_city'        =>  isset($row->residence_city->name) ? $row->residence_city->name : null,
            'locality'              =>  isset($row->locality) ? $row->locality : null,
            'upz'                   =>  isset($row->upz) ? $row->upz : null,
            'neighborhood_name'     =>  isset($row->neighborhood_name->name) ? $row->neighborhood_name->name : null,
            'neighborhood'          =>  isset($row->neighborhood) ? $row->neighborhood : null,
            'address'               =>  isset($row->address) ? $row->address : null,
            'user'                  =>  isset($row->user->full_name) ? $row->user->full_name : null,
            'created_at'            =>  isset($row->created_at) ? $row->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'            =>  isset($row->updated_at) ? $row->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }


    public function queryBuilder(Request $request, Builder $builder)
    {
        return $builder->when($request->has('has_arl'), function ($q) {
            return $q->whereHas('contracts', function ($query) {
                return $query->whereHas('files', function ($query) {
                    return $query->where('file_type_id', 1);
                });
            });
        })->when($request->has('has_secop'), function ($q) {
            return $q->whereHas('contracts', function ($query) {
                return $query->whereHas('files', function ($query) {
                    return $query->where('file_type_id', 2);
                });
            });
        })->when($request->has('query'), function ($q) use ($request) {
            $data = toLower($request->get('query'));
            return $q->whereHas('contracts', function ($query) use ($data) {
                return $query->where('contract', 'like', "%{$data}%");
            })->orWhere('name', 'like', "%{$data}%")
                ->orWhere('surname', 'like', "%{$data}%")
                ->orWhere('document', 'like', "%{$data}%");
        });
    }
}
