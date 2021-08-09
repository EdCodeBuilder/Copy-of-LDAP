<?php

namespace App\Modules\Passport\src\Exports;

use App\Modules\Passport\src\Models\PassportOldView;
use App\Modules\Passport\src\Models\PassportView;
use App\Modules\Passport\src\Models\RenewalView;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class RenewalExport implements FromQuery, WithMapping, WithHeadings, WithColumnFormatting, WithEvents
{
    use Exportable;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var int
     */
    private $rowNumb = 2;

    /**
     * OrfeoExport constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return Builder
     */
    public function query()
    {
        $query = RenewalView::query();

        $start = $this->request->has('start_date')
            ? Carbon::parse($this->request->get('start_date'))->startOfDay()
            : now()->startOfMonth();
        $final = $this->request->has('final_date')
            ? Carbon::parse($this->request->get('final_date'))->endOfDay()
            : now()->endOfMonth();

        return $this->request->has('passport')
            ? $query->where('passport_id', $this->request->get('passport'))
            : $query->whereBetween('created_at', [$start, $final]);
    }

    /**
     * @return array
     */
    public function headings(): array {
        return [
            'ID',
            toUpper(__('passport.validations.passport')),
            toUpper(__('passport.validations.name')),
            toUpper(__('passport.validations.document_type_id')),
            toUpper(__('passport.validations.document')),
            toUpper(__('passport.validations.denounce')),
            toUpper(__('passport.validations.supercade_name')),
            toUpper(__('passport.validations.user_cade')),
            toUpper(__('passport.validations.user_cade_name')),
            toUpper(__('passport.validations.created_at')),
            toUpper(__('passport.validations.time_at')),
        ];
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'B' => NumberFormat::FORMAT_NUMBER,
            'E' => NumberFormat::FORMAT_NUMBER,
            'H' => NumberFormat::FORMAT_NUMBER,
            'J' => NumberFormat::FORMAT_DATE_YYYYMMDD2,
            'K' => NumberFormat::FORMAT_DATE_TIME4,
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        return [
            'A' => isset($row->id) ? (int) $row->id : null,
            'B' => isset($row->passport_id) ? (int) $row->passport_id : null,
            'C' => isset($row->user->full_name) ? (string) $row->user->full_name : null,
            'D' => isset($row->user->document_type->name) ? (string) $row->user->document_type->name : null,
            'E' => isset($row->user->document) ? (int) $row->user->document : null,
            'F' => isset($row->denounce) ? (int) $row->denounce : null,
            'G' => isset($row->supercade) ? (string) $row->supercade : null,
            'H' => isset($row->user_cade_document) ? (int) $row->user_cade_document : null,
            'I' => isset($row->user_cade_name) ? (string) $row->user_cade_name : null,
            'J' => isset($row->created_at) ? Date::dateTimeToExcel($row->created_at) : null,
            'K' => isset($row->created_at) ? Date::dateTimeToExcel($row->created_at) : null,
        ];
    }

    /**
     * @return \Closure[]
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->insertNewRowBefore(1, 6);
                $event->sheet->getDelegate()->mergeCells("A1:K1");
                $title = toUpper(__('passport.validations.passport_vital_online')).' - '.
                    toUpper(__('passport.validations.renewals'));
                $event->sheet->getDelegate()->getCell("A1")
                    ->setValue($title)
                    ->getStyle()
                    ->getFont()
                    ->setSize(24)
                    ->setBold(true);
                $event->sheet->getDelegate()->getCell("A1")
                    ->getStyle()
                    ->getAlignment()
                    ->setVertical('center')
                    ->setHorizontal('center');
                $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(30);

                $event->sheet->getDelegate()->getCell("A3")
                    ->setValue(toUpper(__('passport.validations.generated_by')).':')
                    ->getStyle()
                    ->getFont()
                    ->setSize(14)
                    ->setBold(true);

                $event->sheet->getDelegate()->getCell("B3")
                    ->setValue(auth('api')->user()->full_name)
                    ->getStyle()
                    ->getFont()
                    ->setSize(14)
                    ->setBold(true);

                $event->sheet->getDelegate()->getCell("A4")
                    ->setValue(toUpper(__('passport.validations.period')).':')
                    ->getStyle()
                    ->getFont()
                    ->setSize(14)
                    ->setBold(true);

                $start = $this->request->has('start_date')
                    ? Carbon::parse($this->request->get('start_date'))->startOfDay()
                    : now()->startOfMonth();
                $final = $this->request->has('final_date')
                    ? Carbon::parse($this->request->get('final_date'))->endOfDay()
                    : now()->endOfMonth();

                $dates = toUpper(__('passport.validations.period_dates', [
                    'start_date' => $start->format('Y-m-d'),
                    'final_date' => $final->format('Y-m-d'),
                ]));
                $event->sheet->getDelegate()->getCell("B4")
                    ->setValue($dates)
                    ->getStyle()
                    ->getFont()
                    ->setSize(14)
                    ->setBold(true);

                $event->sheet->getDelegate()->getCell("A5")
                    ->setValue(toUpper(__('passport.validations.created_at')).':')
                    ->getStyle()
                    ->getFont()
                    ->setSize(14)
                    ->setBold(true);

                $event->sheet->getDelegate()->getCell("B5")
                    ->setValue(now()->format('Y-m-d H:i:s'))
                    ->getStyle()
                    ->getFont()
                    ->setSize(14)
                    ->setBold(true);

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
                foreach (range('A', 'K') as $col) {
                    $event->sheet->getDelegate()->getColumnDimension($col)->setAutoSize(true);
                }
                $row = $event->sheet->getDelegate()->getHighestDataRow('K');
                $event->sheet->getDelegate()->getStyle("A7:K$row")
                    ->applyFromArray($styleArray);
                $cells = "A7:K7";
                $event->sheet->getDelegate()
                    ->getStyle($cells)
                    ->getFont()
                    ->setSize(14)
                    ->setBold(true)
                    ->getColor()
                    ->setRGB('FFFFFF');
                $event->sheet->getDelegate()->getRowDimension(7)->setRowHeight(50);
                $event->sheet->getDelegate()->getStyle($cells)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('594d95');
            }
        ];
    }
}
