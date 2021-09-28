<?php

namespace App\Modules\Contractors\src\Exports;

use App\Modules\Contractors\src\Jobs\ProcessExport;
use App\Modules\Contractors\src\Models\ContractorCareerView;
use App\Modules\Contractors\src\Models\ContractorView;
use App\Modules\Contractors\src\Models\ContractView;
use App\Traits\AppendHeaderToExcel;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
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

class CareerExport implements FromQuery, WithHeadings, WithEvents, WithTitle, WithMapping, WithColumnFormatting, ShouldQueue
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
        $request = collect($this->request);
        return ContractorCareerView::query()
            ->when($request->has(['start_date', 'final_date']) || $request->has('contract'),
                function (Builder $query)use ($request) {
                    return $query->whereHas('contractors', function ($query) use ($request) {
                        return $query
                            ->when($request->has('document'), function ($query) use ($request) {
                                return $query->where('document', $request->get('document'));
                            })
                            ->whereHas('contracts', function(Builder $query) use ($request) {
                                return $query->when($request->has(['start_date', 'final_date']), function ($query) use ($request) {
                                    return $query->where('start_date', '>=', $request->get('start_date'))
                                        ->where('final_date', '<=', $request->get('final_date'));
                                })
                                ->when($request->has('document'), function ($query) use ($request) {
                                    return $query->where('contractor_document', $request->get('document'));
                                })
                                ->when($request->has('contract'), function ($query) use ($request) {
                                    return $query->where('contract', 'like', "%{$request->get('contract')}%");
                                });
                        });
                    });
                }
            )
            ->when($request->has('document'), function ($query) use ($request) {
                return $query->where('contractor_document', $request->get('document'));
            });

    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'DOCUMENTO CONTRATISTA',
            'ID ESTUDIOS',
            'NIVEL ACADÉMICO',
            'CARRERA',
            '¿ES GRADUADO?',
            'AÑO/CURSO APROBADO',
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
                $this->setHeader($sheet->sheet, 'ESTUDIOS ACADÉMICOS - PORTAL CONTRATISTA', 'A1:H1', 'H');
            },
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return "ESTUDIOS";
    }

    public function map($row): array
    {
        $graduate = $row['graduate'] ?? null;
        if (!is_null($graduate)) {
            $graduate = $graduate
                ? 'SI'
                : 'NO';
        }
        return [
            'document'              => $row['contractor_document'] ?? null,
            'id'                    =>  isset($row['id']) ? (int) $row['id'] : null,
            'academic_level'        => $row['academic_level'] ?? null,
            'career'                => $row['career'] ?? null,
            'graduate'              => $graduate,
            'year_approved'         => $row['year_approved'] ?? null,
            'created_at'            =>  isset($row['created_at']) ? date_time_to_excel(Carbon::parse($row['created_at'])) : null,
            'updated_at'            =>  isset($row['updated_at']) ? date_time_to_excel(Carbon::parse($row['updated_at'])) : null,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4,
            'H' => NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4,
        ];
    }
}
