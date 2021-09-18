<?php

namespace App\Modules\CitizenPortal\src\Exports;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Events\BeforeWriting;

class CitizenExport implements WithMultipleSheets, WithEvents
{
    use Exportable;

    /**
     * @var Request
     */
    private $request;

    /**
     * Excel constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function sheets(): array
    {
        $sheets = [];

        $sheets[0] = new ProfilesExport($this->request);
        $sheets[1] = new FilesExport($this->request);
        $sheets[2] = new ObservationsExport($this->request);

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
