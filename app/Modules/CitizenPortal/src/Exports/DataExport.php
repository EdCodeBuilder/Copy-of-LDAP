<?php

namespace App\Modules\CitizenPortal\src\Exports;

use App\Modules\CitizenPortal\src\Constants\Roles;
use App\Modules\CitizenPortal\src\Models\File;
use App\Modules\CitizenPortal\src\Models\Observation;
use App\Modules\CitizenPortal\src\Models\Profile;
use App\Modules\CitizenPortal\src\Models\ProfileView;
use App\Modules\CitizenPortal\src\Models\Program;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class DataExport implements WithHeadings, WithColumnFormatting, WithTitle, ShouldAutoSize
{
    /**
     * @return array
     */
    public function headings(): array {
        return [
            'ID PROGRAMA', //A
            'PROGRAMA', //B
            'ID ACTIVIDAD', //C
            'ACTIVIDAD', //D
            'ID ESCENARIO', //E
            'ESCENARIO', //F
            'ID DÍA DE LA SEMANA', //G
            'DÍA DE LA SEMANA', //H
            'ID HORA',//I
            'HORA',//J
            'EDAD MÍNIMA', //K
            'EDAD MÁXIMA', //L
            'CUPO',//M
            'FECHA APERTURA',//N
            'FECHA CIERRE', //O
            'ES PAGO', //P
            'ESTÁ ACTIVO', //Q
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'C' => NumberFormat::FORMAT_NUMBER,
            'E' => NumberFormat::FORMAT_NUMBER,
            'G' => NumberFormat::FORMAT_NUMBER,
            'I' => NumberFormat::FORMAT_NUMBER,
            'K' => NumberFormat::FORMAT_NUMBER,
            'L' => NumberFormat::FORMAT_NUMBER,
            'M' => NumberFormat::FORMAT_NUMBER,
            'N' => NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4,
            'O' => NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4,
            'P' => NumberFormat::FORMAT_NUMBER,
            'Q' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'MASIVOS';
    }
}
