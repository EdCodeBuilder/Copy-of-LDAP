<?php

namespace App\Modules\Contractors\src\Exports;

use App\Modules\Contractors\src\Constants\Roles;
use App\Modules\Contractors\src\Models\Contractor;
use App\Modules\Orfeo\src\Models\Attachment;
use App\Modules\Orfeo\src\Models\Filed;
use App\Modules\Orfeo\src\Resources\FiledResource;
use App\Modules\Orfeo\src\Resources\FolderResource;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class WareHouseExport implements FromCollection, WithMapping, WithHeadings
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
        $this->counter = 0;
        $this->array = $collection;
    }

    public function headings(): array {
        return [
            'CONSECUTIVO',
            'PLACA',
            'DESCRIPCIÓN',
            'CANTIDAD',
            'VALOR HISTÓRICO',
            'RESPONSABLE',
        ];
    }

    public function map($row): array
    {
        return [
            'consecutive'  =>  $this->counter++,
            'id'           =>  isset($row->id) ? (int) $row->id : null,
            'name'         =>  isset($row->name) ? (string) $row->name : null,
            'quantity'     =>  isset($row->quantity) ? (int) $row->quantity : null,
            'document'     =>  isset($row->document) ? (int) $row->document : null,
            'value'        =>  isset($row->value) ? (int) $row->value : null,
        ];
    }

    public function collection()
    {
        return $this->array;
    }
}
