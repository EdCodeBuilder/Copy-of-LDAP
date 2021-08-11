<?php

namespace App\Modules\Passport\src\Exports;

use App\Modules\Passport\src\Models\PassportOld;
use App\Modules\Passport\src\Models\PassportOldView;
use App\Modules\Passport\src\Models\PassportView;
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

class PassportExport implements FromQuery, WithMapping, WithHeadings, WithColumnFormatting, WithEvents
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
        $query = $this->request->has('find_old')
            ? PassportOldView::query()
            : PassportView::query();

        $start = $this->request->has('start_date')
            ? Carbon::parse($this->request->get('start_date'))->startOfDay()
            : now()->startOfMonth();
        $final = $this->request->has('final_date')
            ? Carbon::parse($this->request->get('final_date'))->endOfDay()
            : now()->endOfMonth();


        return $this->request->has('passport')
            ? $query->where('id', $this->request->get('passport'))
            : $query
                ->when($this->request->has('find_old'), function ($query) use ($start, $final) {
                    $keys = PassportOld::query()
                                ->whereBetween('fechaExpedicion', [$start->format('Y-m-d'), $final->format('Y-m-d')])
                                ->get('idPasaporte')->pluck('idPasaporte')->toArray();
                    return $query->whereIn('id', $keys);
                })
                ->when(!$this->request->has('find_old'), function ($query) use ($start, $final) {
                  return $query->whereBetween('created_at', [$start, $final]);
                });
    }

    /**
     * @return array
     */
    public function headings(): array {
        return [
            toUpper(__('passport.validations.passport')),
            toUpper(__('passport.validations.name')),
            toUpper(__('passport.validations.document_type_id')),
            toUpper(__('passport.validations.document')),
            toUpper(__('passport.validations.birthdate')),
            toUpper(__('passport.validations.age')),
            toUpper(__('passport.validations.sex_id')),
            toUpper(__('passport.validations.country_id')),
            toUpper(__('passport.validations.state_id')),
            toUpper(__('passport.validations.city_id')),
            toUpper(__('passport.validations.locality_id')),
            toUpper(__('passport.validations.address')),
            toUpper(__('passport.validations.stratum')),
            toUpper(__('passport.validations.mobile')),
            toUpper(__('passport.validations.phone')),
            toUpper(__('passport.validations.email')),
            toUpper(__('passport.validations.pensionary')),
            toUpper(__('passport.validations.interest_id')),
            toUpper(__('passport.validations.eps_id')),
            toUpper(__('passport.validations.supercade_name')),
            toUpper(__('passport.validations.observations')),
            toUpper(__('passport.validations.question_1')),
            toUpper(__('passport.validations.question_2')),
            toUpper(__('passport.validations.question_3')),
            toUpper(__('passport.validations.question_4')),
            toUpper(__('passport.validations.downloads')),
            toUpper(__('passport.validations.renewals')),
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
            'D' => NumberFormat::FORMAT_NUMBER,
            'E' => NumberFormat::FORMAT_DATE_YYYYMMDD2,
            'F' => NumberFormat::FORMAT_NUMBER,
            'AD' => NumberFormat::FORMAT_DATE_YYYYMMDD2,
            'AE' => NumberFormat::FORMAT_DATE_TIME4,
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        try {
            $birthdate = isset($row->birthday) ? Date::dateTimeToExcel($row->birthday) : null;
        } catch (\Exception $exception) {
            $birthdate = isset($row->birthday) ? $row->birthday->format('Y-m-d') : null;
        }
        return [
            'A'  => isset($row->id) ? (int) $row->id : null,
            'B' => isset($row->full_name) ? (string) $row->full_name : null,
            'C'    => isset($row->document_type_name) ? (string) $row->document_type_name : null,
            'D'  => isset($row->document) ? (int) $row->document : null,
            'E'  => $birthdate,
            'F'  => "=INT((TODAY()-E".$this->rowNumb++.")/365)",
            'G'   => isset($row->gender_name) ? (string) $row->gender_name : null,
            'H'  => isset($row->country_name) ? (string) $row->country_name : null,
            'I' => isset($row->state) ? (string) $row->state : null,
            'J' => isset($row->city_name) ? (string) $row->city_name : null,
            'K' => isset($row->location_name) ? (string) $row->location_name : null,
            'L'   => isset($row->address) ? (string) $row->address : null,
            'M'   => isset($row->stratum) ? (int) $row->stratum : null,
            'N'    => isset($row->mobile) ? (int) $row->mobile : null,
            'O' => isset($row->phone) ? (int) $row->phone : null,
            'P' => isset($row->email) ? (string) $row->email : null,
            'Q'   => isset($row->retired) ? Str::ucfirst($row->retired) : null,
            'R'   => isset($row->hobbies_name) ? (string) $row->hobbies_name : null,
            'S'  => isset($row->eps_name) ? (string) $row->eps_name : null,
            'T'    => isset($row->supercade_name) ? (string) $row->supercade_name : null,
            'U'  => isset($row->observations) ? (string) $row->observations : null,
            'V'    => isset($row->question_1) ? (string) $row->question_1 : null,
            'W'    => isset($row->question_2) ? (string) $row->question_2 : null,
            'X'    => isset($row->question_3) ? (string) $row->question_3 : null,
            'Y'    => isset($row->question_4) ? (string) $row->question_4 : null,
            'Z' => isset($row->downloads) ? (int) $row->downloads : null,
            'AA' => $row->renewals()->count(),
            'AB' => isset($row->user_cade_document) ? (int) $row->user_cade_document : null,
            'AC' => isset($row->user_cade_fullname) ? (string) $row->user_cade_fullname : null,
            'AD'    => isset($row->created_at) ? Date::dateTimeToExcel($row->created_at) : null,
            'AE'    => isset($row->created_at) ? Date::dateTimeToExcel($row->created_at) : null,
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
                $event->sheet->getDelegate()->mergeCells("A1:AE1");
                $event->sheet->getDelegate()->getCell("A1")
                    ->setValue(toUpper(__('passport.validations.passport_vital_online')))
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
                foreach (range('A', 'Z') as $col) {
                    $event->sheet->getDelegate()->getColumnDimension($col)->setAutoSize(true);
                }
                foreach (range('A', 'E') as $col) {
                    $event->sheet->getDelegate()->getColumnDimension("A$col")->setAutoSize(true);
                }
                // $row = $this->rowNumb-1;
                $row = $event->sheet->getDelegate()->getHighestDataRow('AE');
                $event->sheet->getDelegate()->getStyle("A7:AE$row")
                    ->applyFromArray($styleArray);
                $cells = "A7:AE7";
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
