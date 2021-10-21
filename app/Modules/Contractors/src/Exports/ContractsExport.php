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
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ContractsExport implements FromQuery, WithHeadings, WithEvents, WithTitle, WithMapping, WithColumnFormatting, ShouldQueue
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
            'SUBDIRECCIÓN',
            'DEPENDENCIA',
            'OTRA SUBDIRECCIÓN O DEPEDENCIA',
            'EMAIL SUPERVISOR',
            'NOMBRES CREADOR DEL CONTRATO',
            'APELLIDOS CREADOR DEL CONTRATO',
            'DOCUMENTO CREADOR DEL CONTRATO',
            'ARCHIVOS ARL',
            'ARCHIVOS OTROS',
            'FECHA DE CREACIÓN',
            'FECHA DE MODIFICACIÓN',
            'FECHA DE ELIMINACIÓN',
        ];
    }

    /**
     * @return \Closure[]
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $sheet) {
                $this->setHeader($sheet->sheet, 'CONTRATOS - PORTAL CONTRATISTA', 'A1:Z1', 'Z');
            },
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return "CONTRATOS";
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
        return [
            'document'              => $row['contractor_document'] ?? null,
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

    public function columnFormats(): array
    {
        return [
            'L' => NumberFormat::FORMAT_CURRENCY_USD,
            'H' => NumberFormat::FORMAT_DATE_YYYYMMDD2,
            'I' => NumberFormat::FORMAT_DATE_YYYYMMDD2,
            'J' => NumberFormat::FORMAT_DATE_YYYYMMDD2,
            'K' => NumberFormat::FORMAT_DATE_YYYYMMDD2,
            'X' => NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4,
            'Y' => NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4,
            'Z' => NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4,
        ];
    }
}
