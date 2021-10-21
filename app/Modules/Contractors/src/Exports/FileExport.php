<?php

namespace App\Modules\Contractors\src\Exports;

use App\Modules\Contractors\src\Jobs\ProcessExport;
use App\Modules\Contractors\src\Models\ContractorCareerView;
use App\Modules\Contractors\src\Models\ContractorView;
use App\Modules\Contractors\src\Models\ContractView;
use App\Modules\Contractors\src\Models\FileView;
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

class FileExport implements FromQuery, WithHeadings, WithEvents, WithTitle, WithMapping, WithColumnFormatting, ShouldQueue
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
        return FileView::query()->whereIn('contract_id', $this->request['contracts']);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'DOCUMENTO CONTRATISTA',
            'ID ARCHIVO',
            'CONTRATO',
            'TIPO DE TRÁMITE',
            'TIPO DE ARCHIVO',
            'NOMBRE ARCHIVO',
            'NOMBRE USUARIO QUE CREA ARCHIVO',
            'APELLIDOS USUARIO QUE CREA ARCHIVO',
            'DOCUMENTO USUARIO QUE CREA ARCHIVO',
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
                $this->setHeader($sheet->sheet, 'ARCHIVOS - PORTAL CONTRATISTA', 'A1:K1', 'K');
            },
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return "ARCHIVOS";
    }

    public function map($row): array
    {
        return [
            'document'              => $row['contractor_document'] ?? null,
            'id'                    =>  isset($row['id']) ? (int) $row['id'] : null,
            'contract'         => $row['contract'] ?? null,
            'contract_type'         => $row['contract_type'] ?? null,
            'file_type'         => $row['file_type'] ?? null,
            'file_name'         => $row['file_name'] ?? null,
            'user_name'         => $row['user_name'] ?? null,
            'user_surname'         => $row['user_surname'] ?? null,
            'user_document'         => $row['user_document'] ?? null,
            'created_at'            =>  isset($row['created_at']) ? date_time_to_excel(Carbon::parse($row['created_at'])) : null,
            'updated_at'            =>  isset($row['updated_at']) ? date_time_to_excel(Carbon::parse($row['updated_at'])) : null,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'J' => NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4,
            'K' => NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4,
        ];
    }
}
