<?php

namespace App\Modules\Contractors\src\Exports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Imtigger\LaravelJobStatus\Trackable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Events\BeforeWriting;

class DataExport implements WithMultipleSheets, WithEvents, ShouldQueue
{
    use Exportable, Trackable;

    /**
     * @var array
     */
    private $request;

    /**
     * Excel constructor.
     * @param array $request
     */
    public function __construct(array $request, array $params = [])
    {
        $this->request = $request;
        $this->prepareStatus($params);
    }

    public function sheets(): array
    {
        $sheets = [];

        $sheets[0] = new ContractorsExport($this->request);
        $sheets[1] = new ContractsExport($this->request);
        $sheets[2] = new CareerExport($this->request);
        $sheets[3] = new FileExport($this->request);
        $sheets[4] = new SearcherExport();

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
