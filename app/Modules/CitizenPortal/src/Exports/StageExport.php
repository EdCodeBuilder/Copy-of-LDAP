<?php

namespace App\Modules\CitizenPortal\src\Exports;

use App\Modules\CitizenPortal\src\Constants\Roles;
use App\Modules\CitizenPortal\src\Models\File;
use App\Modules\CitizenPortal\src\Models\Observation;
use App\Modules\CitizenPortal\src\Models\Profile;
use App\Modules\CitizenPortal\src\Models\ProfileView;
use App\Modules\CitizenPortal\src\Models\Program;
use App\Modules\CitizenPortal\src\Models\Stage;
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

class StageExport implements FromQuery, WithMapping, WithHeadings, WithColumnFormatting, WithTitle, ShouldAutoSize, WithEvents
{
    /**
     * @return Builder
     */
    public function query()
    {
        return Stage::query();
    }

    /**
     * @return array
     */
    public function headings(): array {
        return [
            toUpper(__('citizen.validations.name')),
            'ID',
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        $id = isset( $row->id ) ? (int) $row->id : null;
        return [
            'B'    =>  isset($row->name) ? "$id - $row->name" : null,
            'A'    =>  $id
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'ESCENARIOS';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => 'FF000000'],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                $row = $event->sheet->getDelegate()->getHighestDataRow('B');
                $event->sheet->getDelegate()->getStyle("A1:B$row")
                    ->applyFromArray($styleArray);
                $cells = "A1:B1";
                $event->sheet->getDelegate()
                    ->getStyle($cells)
                    ->getFont()
                    ->setSize(14)
                    ->setBold(true)
                    ->getColor()
                    ->setRGB('FFFFFF');
                $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(50);
                $event->sheet->getDelegate()->getStyle($cells)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('594d95');
            }
        ];
    }
}
