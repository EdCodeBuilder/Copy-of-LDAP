<?php

namespace App\Modules\Contractors\src\Exports;

use App\Modules\Contractors\src\Jobs\ProcessExport;
use App\Modules\Contractors\src\Models\ContractorView;
use Illuminate\Contracts\Queue\ShouldQueue;
use Imtigger\LaravelJobStatus\JobStatus;
use Imtigger\LaravelJobStatus\Trackable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Events\BeforeWriting;

class DataExport implements WithMultipleSheets, WithEvents, ShouldQueue
{
    use Exportable;

    /**
     * @var array
     */
    private $request;

    /**
     * @var JobStatus
     */
    private $job;

    /**
     * Excel constructor.
     *
     * @param array $request
     * @param int $job
     */
    public function __construct(array $request, int $job)
    {
        $this->request = $request;
        $this->job = $job;
        update_status_job($job, JobStatus::STATUS_EXECUTING, 'excel-contractor-portal');
    }

    public function sheets(): array
    {
        $sheets = [];

        $sheets[0] = new SummaryExport($this->request, $this->job);
        $sheets[1] = new ContractorsExport($this->request, $this->job);
        $sheets[2] = new ContractsExport($this->request, $this->job);
        $sheets[3] = new CareerExport($this->request, $this->job);
        $sheets[4] = new FileExport($this->request, $this->job);
        $sheets[5] = new SearcherExport($this->job);

        return  $sheets;
    }

    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function(BeforeWriting $writer) {
                $writer->writer->getDelegate()->setActiveSheetIndex(0);
            }
        ];
    }
}
