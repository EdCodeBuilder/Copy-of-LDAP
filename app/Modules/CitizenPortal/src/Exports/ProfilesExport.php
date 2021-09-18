<?php

namespace App\Modules\CitizenPortal\src\Exports;

use App\Modules\CitizenPortal\src\Constants\Roles;
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

class ProfilesExport implements FromQuery, WithMapping, WithHeadings, WithColumnFormatting, WithEvents, WithTitle
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var int
     */
    private $rowNumb = 2;

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
        $request = $this->request;
        return ProfileView::query()
            ->withCount(
                [
                    'observations',
                    'files',
                    'files as pending_files_count' => function($query) {
                        return $query->where('status_id', "!=", Profile::VERIFIED);
                    },
                ]
            )
            ->when($request->has('query'), function ($query) use ($request) {
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

    /**
     * @return array
     */
    public function headings(): array {
        return [
            'ID',
            toUpper(__('citizen.validations.status')),
            toUpper(__('citizen.validations.profile_type')),
            toUpper(__('citizen.validations.document_type')),
            toUpper(__('citizen.validations.document')),
            toUpper(__('citizen.validations.name')),
            toUpper(__('citizen.validations.surname')),
            toUpper(__('citizen.validations.email')),
            toUpper(__('citizen.validations.sex')),
            toUpper(__('citizen.validations.blood_type')),
            toUpper(__('citizen.validations.birthdate')),
            toUpper(__('citizen.validations.age')),
            toUpper(__('citizen.validations.country_birth')),
            toUpper(__('citizen.validations.state_birth')),
            toUpper(__('citizen.validations.city_birth')),
            toUpper(__('citizen.validations.country_residence')),
            toUpper(__('citizen.validations.state_residence')),
            toUpper(__('citizen.validations.city_residence')),
            toUpper(__('citizen.validations.locality')),
            toUpper(__('citizen.validations.upz')),
            toUpper(__('citizen.validations.neighborhood')),
            toUpper(__('citizen.validations.other_neighborhood_name')),
            toUpper(__('citizen.validations.address')),
            toUpper(__('citizen.validations.stratum')),
            toUpper(__('citizen.validations.ethnic_group')),
            toUpper(__('citizen.validations.population_group')),
            toUpper(__('citizen.validations.gender')),
            toUpper(__('citizen.validations.sexual_orientation')),
            toUpper(__('citizen.validations.eps')),
            toUpper(__('citizen.validations.has_disability')),
            toUpper(__('citizen.validations.disability')),
            toUpper(__('citizen.validations.contact_name')),
            toUpper(__('citizen.validations.contact_phone')),
            toUpper(__('citizen.validations.contact_relationship')),
            toUpper(__('citizen.validations.verified_at')),
            toUpper(__('citizen.validations.assignor_name')),
            toUpper(__('citizen.validations.assignor_document')),
            toUpper(__('citizen.validations.assigned_at')),
            toUpper(__('citizen.validations.checker_name')),
            toUpper(__('citizen.validations.checker_document')),
            toUpper(__('citizen.validations.observations_count')),
            toUpper(__('citizen.validations.files_count')),
            toUpper(__('citizen.validations.pending_files_count')),
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
            'E' => NumberFormat::FORMAT_NUMBER,
            'K' => NumberFormat::FORMAT_DATE_YYYYMMDD2,
            'L' => NumberFormat::FORMAT_NUMBER,
            'X' => NumberFormat::FORMAT_NUMBER,
            'AG' => NumberFormat::FORMAT_NUMBER,
            'AK' => NumberFormat::FORMAT_NUMBER,
            'AN' => NumberFormat::FORMAT_NUMBER,
            'AO' => NumberFormat::FORMAT_NUMBER,
            'AP' => NumberFormat::FORMAT_NUMBER,
            'AQ' => NumberFormat::FORMAT_NUMBER,
            'AI' => NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4,
            'AL' => NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4,
            'AR' => NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4,
            'AS' => NumberFormat::FORMAT_DATE_YYYYMMDD2.' h'.NumberFormat::FORMAT_DATE_TIME4,
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        $formula = null;
        if (isset($row->birthdate)) {
            $formula = "=INT((TODAY()-K".$this->rowNumb++.")/365)";
        } else {
            $this->rowNumb++;
        }
        return [
            'id'        =>  isset($row->id) ? (int) $row->id : null,
            'status'      =>  isset($row->status) ? (string) $row->status : 'PENDIENTE',
            'profile_type'      =>  isset($row->profile_type) ? (string) $row->profile_type : null,
            'document_type'      =>  isset($row->document_type) ? (string) $row->document_type : null,
            'document'      =>  isset($row->document) ? (int) $row->document : null,
            'name'      =>  isset($row->name) ? (string) $row->name : null,
            'surname'      =>  isset($row->surname) ? (string) $row->surname : null,
            'email'      =>  isset($row->email) ? toLower($row->email) : null,
            'sex'      =>  isset($row->sex) ? (string) $row->sex : null,
            'blood_type'      =>  isset($row->blood_type) ? (string) $row->blood_type : null,
            'birthdate'      =>  isset($row->birthdate) ? $this->dateToExcel($row->birthdate, 'Y-m-d') : null,
            'age'               =>  $formula,
            'country_birth'      =>  isset($row->country_birth) ? (string) $row->country_birth : null,
            'state_birth'      =>  isset($row->state_birth) ? (string) $row->state_birth : null,
            'city_birth'      =>  isset($row->city_birth) ? (string) $row->city_birth : null,
            'country_residence'      =>  isset($row->country_residence) ? (string) $row->country_residence : null,
            'state_residence'      =>  isset($row->state_residence) ? (string) $row->state_residence : null,
            'city_residence'      =>  isset($row->city_residence) ? (string) $row->city_residence : null,
            'locality'      =>  isset($row->locality) ? (string) $row->locality : null,
            'upz'      =>  isset($row->upz) ? (string) $row->upz : null,
            'neighborhood'      =>  isset($row->neighborhood) ? (string) $row->neighborhood : null,
            'other_neighborhood_name'      =>  isset($row->other_neighborhood_name) ? (string) $row->other_neighborhood_name : null,
            'address'      =>  isset($row->address) ? (string) $row->address : null,
            'stratum'      =>  isset($row->stratum) ? (int) $row->stratum : null,
            'ethnic_group'      =>  isset($row->ethnic_group) ? (string) $row->ethnic_group : null,
            'population_group'      =>  isset($row->population_group) ? (string) $row->population_group : null,
            'gender'      =>  isset($row->gender) ? (string) $row->gender : null,
            'sexual_orientation'      =>  isset($row->sexual_orientation) ? (string) $row->sexual_orientation : null,
            'eps'      =>  isset($row->eps) ? (string) $row->eps : null,
            'has_disability'      =>  isset($row->has_disability) ? (string) $row->has_disability : null,
            'disability'      =>  isset($row->disability) ? (string) $row->disability : null,
            'contact_name'      =>  isset($row->contact_name) ? (string) $row->contact_name : null,
            'contact_phone'      =>  isset($row->contact_phone) ? (int) $row->contact_phone : null,
            'contact_relationship_name'      =>  isset($row->contact_relationship_name) ? (string) $row->contact_relationship_name : null,
            'verified_at'      =>  isset($row->verified_at) ? $this->dateToExcel($row->verified_at) : null,
            'full_name_assignor'      =>  isset($row->full_name_assignor) ? (string) $row->full_name_assignor : null,
            'assignor_document'      =>  isset($row->assignor_document) ? (string) $row->assignor_document : null,
            'assigned_at'      =>  isset($row->assigned_at) ? $this->dateToExcel($row->assigned_at) : null,
            'full_name_verifier'      =>  isset($row->full_name_verifier) ? (string) $row->full_name_verifier : null,
            'checker_document'      =>  isset($row->checker_document) ? (string) $row->checker_document : null,
            'observations_count'      =>  isset($row->observations_count) ? (int) $row->observations_count : 0,
            'files_count'      =>  isset($row->files_count) ? (int) $row->files_count : 0,
            'pending_files_count'      =>  isset($row->pending_files_count) ? (int) $row->pending_files_count : 0,
            'created_at'    =>  isset($row->created_at) ? $this->dateToExcel($row->created_at) : null,
            'updated_at'    =>  isset($row->updated_at) ? $this->dateToExcel($row->updated_at) : null,
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
                $event->sheet->getDelegate()->mergeCells("A1:AS1");
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
                foreach (range('A', 'Z') as $col) {
                    $event->sheet->getDelegate()->getColumnDimension($col)->setAutoSize(true);
                }
                foreach (range('A', 'S') as $col) {
                    $event->sheet->getDelegate()->getColumnDimension("A$col")->setAutoSize(true);
                }
                // $row = $this->rowNumb-1;
                $row = $event->sheet->getDelegate()->getHighestDataRow('AS');
                $event->sheet->getDelegate()->getStyle("A7:AS$row")
                    ->applyFromArray($styleArray);
                $cells = "A7:AS7";
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
        return 'PERFILES';
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
