<?php

namespace App\Modules\CitizenPortal\src\Exports;

use App\Modules\CitizenPortal\src\Constants\Roles;
use App\Modules\CitizenPortal\src\Models\Observation;
use App\Modules\CitizenPortal\src\Models\Profile;
use App\Modules\CitizenPortal\src\Models\ProfileView;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ObservationsExport implements FromQuery, WithMapping, WithHeadings, WithColumnFormatting, WithEvents, WithTitle
{
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

    /**
     * @return Builder
     */
    public function query()
    {
        return Observation::query()->whereHas(
            'profile',
            function($query) {
                $request = $this->request;
                return $query->when($request->has('query'), function ($query) use ($request) {
                        $keys = Profile::search($request->get('query'))->get(['id'])->pluck('id')->toArray();
                        return $query->whereKey($keys);
                    })
                    ->when($request->has('document'), function ($query) use ($request) {
                        return $query->where('document', 'like', "%{$request->get('document')}%");
                    })
                    ->when($request->has('status_id'), function ($query) use ($request) {
                        $status = $request->get('status_id');
                        if (is_array($status) && in_array(Profile::PENDING, $status)) {
                            return $query->whereNull('status_id')
                                ->orWhereIn('status_id', $status);
                        }
                        if ( $request->get('status_id') == Profile::PENDING ) {
                            return $query->whereNull('status_id')
                                ->orWhere('status_id', $request->get('status_id'));
                        }
                        return is_array($status)
                            ?  $query->whereIn('status_id', $request->get('status_id'))
                            :  $query->where('status_id', $request->get('status_id'));
                    })
                    ->when($request->has('not_assigned'), function ($query) use ($request) {
                        return $query->whereNull('checker_id');
                    })
                    ->when($request->has('assigned'), function ($query) use ($request) {
                        return $query->whereNotNull('checker_id');
                    })
                    ->when($request->has('validators_id'), function ($query) use ($request) {
                        return $query->whereIn('checker_id', $request->get('validators_id'));
                    })
                    ->when($request->has('profile_type_id'), function ($query) use ($request) {
                        return $query->whereIn('profile_type_id', $request->get('profile_type_id'));
                    })
                    ->when($request->has('start_date'), function ($query) use ($request) {
                        return $query->whereDate('created_at', '>=', $request->get('start_date'));
                    })
                    ->when($request->has('final_date'), function ($query) use ($request) {
                        return $query->whereDate('created_at', '<=', $request->get('final_date'));
                    })
                    ->when(
                        (auth('api')->user()->isA(Roles::ROLE_VALIDATOR) && auth('api')->user()->isNotA(...Roles::adminAnd(Roles::ROLE_ASSIGNOR))
                        ), function ($query) use ($request) {
                        return $query->where('checker_id', auth('api')->user()->id);
                    });
            }
        );
    }

    /**
     * @return array
     */
    public function headings(): array {
        return [
            'ID PERFIL',
            'ID OBSERVACIÓN',
            'OBSERVACIÓN',
            'USUARIO',
            'FECHA DE LECTURA',
            toUpper(__('citizen.validations.created_at')),
            toUpper(__('citizen.validations.updated_at')),
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
            'E' => NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4,
            'F' => NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4,
            'G' => NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4,
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        return [
            'A'    =>  isset( $row->profile_id ) ? (int) $row->profile_id : null,
            'B'    =>  isset($row->id) ? (int) $row->id : null,
            'C'    =>  isset($row->observation) ? (string) $row->observation : null,
            'D'    =>  isset( $row->user->full_name ) ? (string) $row->user->full_name : null,
            'E'    => isset($row->read_at) ? $this->dateToExcel($row->read_at) : null,
            'F'    =>  isset($row->created_at) ? $this->dateToExcel($row->created_at) : null,
            'G'    =>  isset($row->updated_at) ? $this->dateToExcel($row->updated_at) : null,
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
                $event->sheet->getDelegate()->mergeCells("A1:G1");
                $event->sheet->getDelegate()->getCell("A1")
                    ->setValue(toUpper(__('citizen.validations.citizen_portal')))
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
                    ->setValue(toUpper(__('citizen.validations.generated_by')).':')
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
                    ->setValue(toUpper(__('citizen.validations.period')).':')
                    ->getStyle()
                    ->getFont()
                    ->setSize(14)
                    ->setBold(true);

                $dates = $this->getDates();

                $dates = is_null($dates['start_date']) && is_null($dates['final_date'])
                    ? ''
                    : toUpper(__('citizen.validations.period_dates', [
                        'start_date' => $dates['start_date']->format('Y-m-d'),
                        'final_date' => $dates['final_date']->format('Y-m-d'),
                    ]));
                $event->sheet->getDelegate()->getCell("B4")
                    ->setValue($dates)
                    ->getStyle()
                    ->getFont()
                    ->setSize(14)
                    ->setBold(true);

                $event->sheet->getDelegate()->getCell("A5")
                    ->setValue(toUpper(__('citizen.validations.created_at')).':')
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
                foreach (range('A', 'G') as $col) {
                    $event->sheet->getDelegate()->getColumnDimension($col)->setAutoSize(true);
                }
                // $row = $this->rowNumb-1;
                $row = $event->sheet->getDelegate()->getHighestDataRow('G');
                $event->sheet->getDelegate()->getStyle("A7:G$row")
                    ->applyFromArray($styleArray);
                $cells = "A7:G7";
                $event->sheet->getDelegate()
                    ->getStyle($cells)
                    ->getFont()
                    ->setSize(14)
                    ->setBold(true)
                    ->getColor()
                    ->setRGB('FFFFFF');
                $event->sheet->getDelegate()->getRowDimension(7)->setRowHeight(50);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(100);
                $event->sheet->getDelegate()->getStyle($cells)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('594d95');
            }
        ];
    }

    public function dateToExcel($date, $fallbackFormat = 'Y-m-d H:i:s')
    {
        try {
            return Date::dateTimeToExcel($date);
        } catch (\Exception $exception) {
            return $date->format($fallbackFormat);
        }
    }

    public function title(): string
    {
        return 'OBSERVACIONES';
    }

    public function getDates()
    {
        $has_dates = $this->request->has([
            'start_date',
            'final_date',
        ]);
        $start = null;
        $final = null;
        $has_any_dates = $this->request->hasAny([
            'start_date',
            'final_date',
        ]);
        if ($has_dates) {
            $start = Carbon::parse($this->request->get('start_date'))->startOfDay();
            $final = Carbon::parse($this->request->get('final_date'))->endOfDay();
        } else if ($has_any_dates) {
            $start_dates = $this->request->has('start_date') && !$this->request->has('final_date');
            $final_dates = $this->request->has('final_date') && !$this->request->has('start_date');

            if ($start_dates) {
                $start = Carbon::parse($this->request->get('start_date'))->startOfDay();
                $final = Carbon::parse($this->request->get('start_date'))->endOfDay();;
            }

            if ($final_dates) {
                $start = Carbon::parse($this->request->get('final_date'))->startOfDay();
                $final = Carbon::parse($this->request->get('final_date'))->endOfDay();;
            }
        }

        return [
            'start_date' => $start,
            'final_date' => $final,
        ];
    }
}
