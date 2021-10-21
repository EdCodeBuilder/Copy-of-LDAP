<?php

namespace App\Modules\Contractors\src\Exports;

use App\Modules\Contractors\src\Constants\GlobalQuery;
use App\Modules\Contractors\src\Jobs\ProcessExport;
use App\Modules\Contractors\src\Models\Contractor;
use App\Modules\Contractors\src\Models\ContractorView;
use App\Traits\AppendHeaderToExcel;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Imtigger\LaravelJobStatus\JobStatus;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use function Clue\StreamFilter\fun;

class ContractorsExport implements FromQuery, WithHeadings, WithEvents, WithTitle, WithMapping, WithColumnFormatting, ShouldQueue
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


    public function query()
    {
        return ContractorView::query()->whereKey($this->request['contractors']);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'DOCUMENTO',
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
            'FECHA DE CREACIÓN',
            'FECHA DE MODIFICACIÓN',
        ];
    }

    /**
     * @return \Closure[]
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $sheet) {
                $this->setHeader($sheet->sheet, 'CONTRATISTAS - PORTAL CONTRATISTA', 'A1:AF1', 'AF');
            },
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return "CONTRATISTAS";
    }

    public function map($row): array
    {
        return [
            'document'              => $row['document'] ?? null,
            'document_type'         => $row['document_type'] ?? null,
            'id'                    =>  isset($row['id']) ? (int) $row['id'] : null,
            'name'                  => $row['name'] ?? null,
            'surname'               => $row['surname'] ?? null,
            'birthdate'             =>  isset($row['birthdate']) ? date_time_to_excel(Carbon::parse($row['birthdate']), 'Y-m-d') : null,
            'age'                   => isset($row['birthdate']) ? Carbon::parse($row['birthdate'])->age : null,
            'birthdate_country'     =>  $row['birth_country'] ?? null,
            'birthdate_state'       =>  $row['birth_state'] ?? null,
            'birthdate_city'        =>  $row['birth_city'] ?? null,
            'sex'                   =>  $row['sex'] ?? null,
            'email'                 => $row['email'] ?? null,
            'institutional_email'   => $row['institutional_email'] ?? null,
            'phone'                 => $row['phone'] ?? null,
            'eps_name'              => $row['eps'] ?? null,
            'eps_nit'              => $row['eps_nit'] ?? null,
            'eps'                   => $row['other_eps'] ?? null,
            'afp_name'              => $row['afp'] ?? null,
            'afp'                   => $row['other_afp'] ?? null,
            'residence_country'     =>  $row['residence_country'] ?? null,
            'residence_state'       =>  $row['residence_state'] ?? null,
            'residence_city'        =>  $row['residence_city'] ?? null,
            'locality'              =>   $this->setLocalityName($row),
            'upz'                   =>   $this->setUpzName($row),
            'neighborhood_name'     =>   $this->setNeighborhoodName($row),
            'neighborhood'          => $row['other_neighborhood'] ?? null,
            'address'               => $row['address'] ?? null,
            'creator_name'                  =>  $row['creator_name'] ?? null,
            'creator_surname'                  =>  $row['creator_surname'] ?? null,
            'creator_document'                  =>  $row['creator_document'] ?? null,
            'created_at'            =>  isset($row['created_at']) ? date_time_to_excel(Carbon::parse($row['created_at'])) : null,
            'updated_at'            =>  isset($row['updated_at']) ? date_time_to_excel(Carbon::parse($row['updated_at'])) : null,
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

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_DATE_YYYYMMDD2,
            'AE' => NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4,
            'AF' => NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4,
        ];
    }
}
