<?php

namespace App\Modules\CitizenPortal\src\Exports;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CitizenExport implements WithMultipleSheets
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

        $sheets[0] = new FilesExport($this->request);
        $sheets[1] = new ObservationsExport($this->request);
        $sheets[2] = new ProfilesExport($this->request);

        return  $sheets;
    }
}
