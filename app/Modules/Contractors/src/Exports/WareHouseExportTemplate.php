<?php

namespace App\Modules\Contractors\src\Exports;

use App\Modules\Contractors\src\Models\Contractor;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class WareHouseExportTemplate
{
    /**
     * @var array
     */
    private $collections;

    /**
     * @var \PhpOffice\PhpSpreadsheet\Spreadsheet
     */
    private $file;

    /**
     * @var \App\Modules\Contractors\src\Models\Contractor
     */
    private $contractor;

    /**
     * WareHouseExport constructor.
     */
    public function __construct(Contractor $contractor, $collection)
    {
        $this->collections = $collection;
        $this->contractor = $contractor;
    }

    public function create()
    {
        try {
            $this->file = IOFactory::load( storage_path('app/templates/FORMATO_TRASLADOS.xlsx') );
            $this->worksheet = $this->file->getActiveSheet();
            $sub = isset($this->contractor->contracts()->latest()->first()->subdirectorate->name)
                ? $this->contractor->contracts()->latest()->first()->subdirectorate->name
                : null;

            $dependency = isset($this->contractor->contracts()->latest()->first()->dependency->name)
                ? $this->contractor->contracts()->latest()->first()->dependency->name
                : null;

            if (is_null($dependency)) {
                $dependency = isset($this->contractor->contracts()->latest()->first()->other_dependency_subdirectorate)
                    ? $this->contractor->contracts()->latest()->first()->other_dependency_subdirectorate
                    : null;
            }

            if (!is_null($sub)) {
                $dependency = "$sub - $dependency";
            }

            $this->worksheet->getCell('B3')->setValue($this->contractor->full_name);
            $this->worksheet->getCell('E3')->setValue($this->contractor->document);
            $this->worksheet->getCell('F3')->setValue($dependency);
            $this->worksheet->insertNewRowBefore(8, count($this->collections));

            $i = 7;
            foreach ($this->collections as $key => $collection) {
                $this->worksheet->getCell("B$i")->setValue($key + 1 );
                $this->worksheet->getCell("C$i")->setValue(isset($collection['id']) ? (int) $collection['id'] : null);
                $this->worksheet->mergeCells("D$i:M$i");
                $this->worksheet->getCell("D$i")->setValue(isset($collection['name']) ? (string) $collection['name'] : null);
                $i++;
            }
            return IOFactory::createWriter($this->file, Excel::XLSX);
        } catch (\Exception $exception) {

        }
    }
}
