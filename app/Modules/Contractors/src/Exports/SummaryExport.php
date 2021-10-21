<?php

namespace App\Modules\Contractors\src\Exports;

use App\Modules\Contractors\src\Jobs\ProcessExport;
use App\Modules\Contractors\src\Models\ContractorView;
use App\Modules\Contractors\src\Models\ContractView;
use App\Modules\Contractors\src\Models\File;
use App\Traits\AppendHeaderToExcel;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Imtigger\LaravelJobStatus\JobStatus;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class SummaryExport implements FromQuery, WithHeadings, WithEvents, WithTitle, WithMapping, WithColumnFormatting, ShouldQueue, ShouldAutoSize
{
    use Exportable, AppendHeaderToExcel;

    /**
     * @var array
     */
    private $request;

    /**
     * @var int
     */
    private $rowNumb = 2;

    public function __construct(array $request, $job)
    {
        $this->request = $request;
        update_status_job($job, JobStatus::STATUS_EXECUTING, 'excel-contractor-portal');
    }

    /**
     * @return Builder
     */
    public function query()
    {
        return ContractView::query()->whereKey($this->request['contracts']);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'DOCUMENTO CONTRATISTA',
            'TIPO DE DOCUMENTO',
            'ID CONTRATISTA',
            'NOMBRES',
            'APELLIDOS',
            'FECHA DE NACIMIENTO',
            'EDAD',
            'PAÍS DE NACIMIENTO',
            'DEPTO DE NACIMIENTO',
            'CIUDAD DE NACIMIENTO',
            'SEXO',
            'CORREO PERSONAL',
            'CORREO INSTITUCIONAL',
            'TELÉFONO',
            'EPS',
            'EPS NIT',
            'OTRA EPS',
            'AFP',
            'AFP NIT',
            'OTRA AFP',
            'PAÍS DE RESIDENCIA',
            'DEPTO DE RESIDENCIA',
            'CIUDAD DE RESIDENCIA',
            'LOCALIDAD',
            'UPZ',
            'BARRIO',
            'OTRO BARRIO',
            'DIRECCIÓN',
            'NOMBRES USUARIO QUE REGISTRÓ CONTRATISTA',
            'APELLIDOS USUARIO QUE REGISTRÓ CONTRATISTA',
            'DOCUMENTO QUE REGISTRÓ CONTRATISTA',
            'FECHA DE CREACIÓN CONTRATISTA',
            'FECHA DE MODIFICACIÓN CONTRATISTA',
            'ID CONTRATO',
            'ESTADO DEL CONTRATO',
            'TIPO DE TRÁMITE',
            'CONTRATO',
            'SE SUMINISTRA TRANSPORTE',
            'CARGO',
            'FECHA INICIAL',
            'FECHA FINAL',
            'FECHA INICIAL DE SUSPENSIÓN',
            'FECHA FINAL DE SUSPENSIÓN',
            'VALOR TOTAL DEL CONTRATO O ADICIÓN',
            'DÍAS QUE NO TRABAJA',
            'NIVEL DE RIESGO',
            'CÓDIGO NIVEL DE RIESGO',
            'SUBDIRECCIÓN',
            'DEPENDENCIA',
            'OTRA SUBDIRECCIÓN O DEPEDENCIA',
            'EMAIL SUPERVISOR',
            'NOMBRES CREADOR DEL CONTRATO',
            'APELLIDOS CREADOR DEL CONTRATO',
            'DOCUMENTO CREADOR DEL CONTRATO',
            'ARCHIVOS ARL',
            'ARCHIVOS OTROS',
            'FECHA DE CREACIÓN CONTRATO',
            'FECHA DE MODIFICACIÓN CONTRATO',
            'FECHA DE ELIMINACIÓN CONTRATO',
        ];
    }

    /**
     * @return \Closure[]
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $sheet) {
                $this->setHeader($sheet->sheet, 'RESUMEN - PORTAL CONTRATISTA', 'A1:BG1', 'BG');
            },
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return "RESUMEN";
    }

    public function map($row): array
    {
        $is_active = $row['final_date'] ?? null;
        if (!is_null($is_active)) {
            $is_active = now()->lte(Carbon::parse($is_active))
                ? 'ACTIVO'
                : 'INACTIVO';
        }
        $transport = $row['transport'] ?? null;
        if (!is_null($transport)) {
            $transport = $transport
                ? 'SI'
                : 'NO';
        }

        $contractor = ContractorView::query()
            ->where('document', $row['contractor_document'] ?? null)
            ->first()->toArray();

        return [
            'document'              => $row['contractor_document'] ?? null,

            'document_type'         => $contractor['document_type'] ?? null,
            'contractor_id'         =>  isset($contractor['id']) ? (int) $contractor['id'] : null,
            'name'                  => $contractor['name'] ?? null,
            'surname'               => $contractor['surname'] ?? null,
            'birthdate'             =>  isset($contractor['birthdate']) ? date_time_to_excel(Carbon::parse($contractor['birthdate']), 'Y-m-d') : null,
            'age'                   => isset($contractor['birthdate']) ? Carbon::parse($contractor['birthdate'])->age : null,
            'birthdate_country'     =>  $contractor['birth_country'] ?? null,
            'birthdate_state'       =>  $contractor['birth_state'] ?? null,
            'birthdate_city'        =>  $contractor['birth_city'] ?? null,
            'sex'                   =>  $contractor['sex'] ?? null,
            'email'                 => $contractor['email'] ?? null,
            'institutional_email'   => $contractor['institutional_email'] ?? null,
            'phone'                 => $contractor['phone'] ?? null,
            'eps_name'              => $contractor['eps'] ?? null,
            'eps_nit'              => $contractor['eps_nit'] ?? null,
            'eps'                   => $contractor['other_eps'] ?? null,
            'afp_name'              => $contractor['afp'] ?? null,
            'afp_nit'              => $contractor['afp_nit'] ?? null,
            'afp'                   => $contractor['other_afp'] ?? null,
            'residence_country'     =>  $contractor['residence_country'] ?? null,
            'residence_state'       =>  $contractor['residence_state'] ?? null,
            'residence_city'        =>  $contractor['residence_city'] ?? null,
            'locality'              =>   $this->setLocalityName($contractor),
            'upz'                   =>   $this->setUpzName($contractor),
            'neighborhood_name'     =>   $this->setNeighborhoodName($contractor),
            'neighborhood'          => $contractor['other_neighborhood'] ?? null,
            'address'               => $contractor['address'] ?? null,
            'creator_name'                  =>  $contractor['creator_name'] ?? null,
            'creator_surname'                  =>  $contractor['creator_surname'] ?? null,
            'creator_document'                  =>  $contractor['creator_document'] ?? null,
            'created_at_contractor'            =>  isset($contractor['created_at']) ? date_time_to_excel(Carbon::parse($contractor['created_at'])) : null,
            'updated_at_contractor'            =>  isset($contractor['updated_at']) ? date_time_to_excel(Carbon::parse($contractor['updated_at'])) : null,

            'id'                    =>  isset($row['id']) ? (int) $row['id'] : null,
            'is_active'             => is_null($is_active) ? null : $is_active,
            'contract_type'         => $row['contract_type'] ?? null,
            'contract'              => $row['contract'] ?? null,
            'transport'             => $transport,
            'position'              => $row['position'] ?? null,
            'start_date'            =>  isset($row['start_date']) ? date_time_to_excel(Carbon::parse($row['start_date'])) : null,
            'final_date'            =>  isset($row['final_date']) ? date_time_to_excel(Carbon::parse($row['final_date'])) : null,
            'start_suspension_date'     =>  isset($row['start_suspension_date']) ? date_time_to_excel(Carbon::parse($row['start_suspension_date'])) : null,
            'final_suspension_date'       =>  isset($row['final_suspension_date']) ? date_time_to_excel(Carbon::parse($row['final_suspension_date'])) : null,
            'total'               => $row['total'] ?? null,
            'day'               => $row['day'] ?? null,
            'risk'               => $row['risk'] ?? null,
            'risk_code'               => $this->riskCode($row['risk'] ?? 0),
            'subdirectorate'               => $row['subdirectorate'] ?? null,
            'dependency'               => $row['dependency'] ?? null,
            'other_dependency_subdirectorate'     => $row['other_dependency_subdirectorate'] ?? null,
            'supervisor_email'               => $row['supervisor_email'] ?? null,
            'lawyer_name'               => $row['lawyer_name'] ?? null,
            'lawyer_surname'               => $row['lawyer_surname'] ?? null,
            'lawyer_document'               => $row['lawyer_document'] ?? null,
            'arl_files_count'               => isset($row['id']) ? File::query()->where('contract_id', $row['id'])->where('file_type_id', 1)->count() : null,
            'other_files_count'               => isset($row['id']) ? File::query()->where('contract_id', $row['id'])->where('file_type_id','!=', 1)->count() : null,
            'created_at'            =>  isset($row['created_at']) ? date_time_to_excel(Carbon::parse($row['created_at'])) : null,
            'updated_at'            =>  isset($row['updated_at']) ? date_time_to_excel(Carbon::parse($row['updated_at'])) : null,
            'deleted_at'            =>  isset($row['deleted_at']) ? date_time_to_excel(Carbon::parse($row['deleted_at'])) : null,
        ];
    }

    /**
     * @param $row
     * @return string|null
     */
    private function setNeighborhoodName($row)
    {
        $state = isset($row['residence_city_id']) ? (int) $row['residence_city_id'] : null;
        $neighborhood = $row['neighborhood'] ?? null;
        return !is_null($state) && $state != 12688 && is_null($neighborhood) ? 'OTRO' : $neighborhood;
    }

    /**
     * @param $row
     * @return string|null
     */
    private function setLocalityName($row)
    {
        $state = isset($row['residence_city_id']) ? (int) $row['residence_city_id'] : null;
        $locality = $row['locality'] ?? null;
        return !is_null($state) && $state != 12688 && is_null($locality) ? 'OTRO' : $locality;
    }

    /**
     * @param $row
     * @return string|null
     */
    private function setUpzName($row)
    {
        $state = isset($row['residence_city_id']) ? (int) $row['residence_city_id'] : null;
        $upz = $row['upz'] ?? null;
        return !is_null($state) && $state != 12688 && is_null($upz) ? 'OTRO' : $upz;
    }

    /**
     * @param $code
     * @return int|null
     */
    public function riskCode($code)
    {
        switch ($code) {
            case 1:
                return 1924101;
            case 2:
                return 2924102;
            case 3:
                return 3924102;
            case 5:
                return 5924101;
            default:
                return null;
        }
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_DATE_YYYYMMDD2,
            'AF' => NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4,
            'AG' => NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4,
            'AN' => NumberFormat::FORMAT_DATE_YYYYMMDD2,
            'AO' => NumberFormat::FORMAT_DATE_YYYYMMDD2,
            'AP' => NumberFormat::FORMAT_DATE_YYYYMMDD2,
            'AQ' => NumberFormat::FORMAT_DATE_YYYYMMDD2,
            'AR' => NumberFormat::FORMAT_CURRENCY_USD,
            'BE' => NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4,
            'BF' => NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4,
            'BG' => NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4,
        ];
    }
}
