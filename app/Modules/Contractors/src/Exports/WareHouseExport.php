<?php

namespace App\Modules\Contractors\src\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class WareHouseExport implements FromCollection, WithMapping, WithHeadings, WithColumnFormatting, ShouldAutoSize
{
    use Exportable;

    /**
     * @var int
     */
    private $counter;
    /**
     * @var array
     */
    private $array;

    /**
     * WareHouseExport constructor.
     */
    public function __construct($collection)
    {
        $this->counter = 1;
        $this->array = $collection;
    }

    public function headings(): array {
        return [
            'Nº',
            'PLACA',
            'DESCRIPCIÓN',
            'VALOR HISTÓRICO',
            'RESPONSABLE',
        ];
    }

    public function map($row): array
    {
        return [
            'consecutive'  =>  $this->counter++,
            'id'           =>  isset($row['id']) ? (int) $row['id'] : null,
            'name'         =>  isset($row['name']) ? (string) $row['name'] : null,
            'value'        =>  isset($row['value']) ? (int) $row['value'] : null,
            'document'     =>  isset($row['document']) ? (int) $row['document'] : null,
        ];
    }

    public function collection()
    {
        return $this->array;
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_CURRENCY_USD
        ];
    }
}
