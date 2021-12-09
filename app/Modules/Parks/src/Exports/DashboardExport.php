<?php


namespace App\Modules\Parks\src\Exports;


use App\Modules\Parks\src\Models\Park;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
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

class DashboardExport implements FromQuery, WithMapping, WithHeadings, WithColumnFormatting, WithEvents, WithTitle, ShouldAutoSize
{
    use Exportable;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var int
     */
    private $row = 1;

    /**
     * OrfeoExport constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        return Park::query()
                    ->when($this->request->has('location'), function ($query) {
                        $localities = $this->request->get('location');
                        return is_array($localities)
                            ? $query->whereIn('Id_Localidad', $localities)
                            : $query->where('Id_Localidad', $localities);
                    })
                    ->when($this->request->has('upz'), function ($query) {
                        $upz = $this->request->get('upz');
                        return is_array($upz)
                            ? $query->whereIn('Upz', $upz)
                            : $query->where('Upz', $upz);
                    })
                    ->when($this->request->has('neighborhood'), function ($query) {
                        $neighborhood = $this->request->get('neighborhood');
                        return is_array($neighborhood)
                            ? $query->whereIn('Id_Barrio', $neighborhood)
                            : $query->where('Id_Barrio', $neighborhood);
                    })
                    ->when($this->request->has('certified'), function ($query) {
                        if ($this->request->get('certified') == 'certified')
                            return $query->where('EstadoCertificado', 1);
                        if ($this->request->get('certified') == 'not_certified')
                            return $query->where('EstadoCertificado', '!=', 1);

                        return $query;
                    })
                    ->when($this->request->has('enclosure'), function ($query) {
                        $types = $this->request->get('enclosure');
                        if (is_array($types) && count($types) > 0)
                            return $query->whereIn('Cerramiento', $types);

                        return $query;
                    })
                    ->when($this->request->has('endowment_id'), function ($query) {
                        if (!is_null($this->request->get('endowment_id'))) {
                            return $query->whereHas('park_endowment', function ($query)  {
                                return $query->where('Id_Dotacion', $this->request->get('endowment_id'));
                            });
                        }
                        return  $query;
                    })
                    ->when($this->request->has('park_type'), function ($query) {
                        if (is_array($this->request->get('park_type')) && count($this->request->get('park_type')) > 0)
                            return $query->whereIn('Id_Tipo', $this->request->get('park_type'));
                        return $query;
                    })
                    ->when($this->request->has('admin'), function ($query) {
                        if ($this->request->get('admin') == 'admin')
                            return $query->where('Administracion', 'IDRD');
                        if ($this->request->get('admin') == 'is_not_admin')
                            return $query->where('Administracion', '!=', 'IDRD');
                        return $query;
                    });
    }

    public function headings(): array
    {
        return [
            '#',
            toUpper(__('parks.attributes.code')),
            toUpper(__('parks.attributes.name')),
            toUpper(__('parks.attributes.locality_id')),
            toUpper(__('parks.attributes.upz_code')),
            toUpper(__('parks.attributes.upz')),
            toUpper(__('parks.attributes.neighborhood_id')),
            toUpper(__('parks.attributes.urbanization')),
            toUpper(__('parks.attributes.address')),
            toUpper(__('parks.attributes.stratum')),
            toUpper(__('parks.attributes.latitude')),
            toUpper(__('parks.attributes.longitude')),
            'RUPIS',
            toUpper(__('parks.attributes.area')),
            toUpper(__('parks.attributes.green_area')),
            toUpper(__('parks.attributes.grey_area')),
            toUpper(__('parks.attributes.area_hectare')),
            toUpper(__('parks.attributes.walking_trails')),
            toUpper(__('parks.attributes.walking_trails_status')),
            toUpper(__('parks.attributes.access_roads')),
            toUpper(__('parks.attributes.access_roads_status')),
            toUpper(__('parks.attributes.households')),
            toUpper(__('parks.attributes.children_population')),
            toUpper(__('parks.attributes.youth_population')),
            toUpper(__('parks.attributes.older_population')),
            toUpper(__('parks.attributes.admin_name')),
            toUpper(__('parks.attributes.email')),
            toUpper(__('parks.attributes.phone')),
            toUpper(__('parks.attributes.admin')),
            toUpper(__('parks.attributes.general_status')),
            toUpper(__('parks.attributes.enclosure')),
            toUpper(__('parks.attributes.zone_type')),
            toUpper(__('parks.attributes.render')),
            toUpper(__('parks.attributes.capacity')),
            toUpper(__('parks.attributes.stage_type_id')),
            toUpper(__('parks.attributes.scale_id')),
            toUpper(__('parks.attributes.vocation_id')),
            toUpper(__('parks.attributes.vigilance')),
            toUpper(__('parks.attributes.received')),
            toUpper(__('parks.attributes.certified_status')),
            toUpper(__('parks.attributes.compete')),
            toUpper(__('parks.attributes.concern')),
            toUpper(__('parks.attributes.visited_at')),
        ];
    }

    public function map($row): array
    {
        if (auth('api')->user()->isA('superadmin')) {
            $id = isset( $row->Id ) ? (int) $row->Id : null;
        }
        return [
            'id'        => $id ?? $this->row++,
            'code'      =>  isset( $row->Id_IDRD ) ? toUpper($row->Id_IDRD) : null,
            'name'      =>  isset( $row->Nombre ) ? toUpper($row->Nombre) : null,
            'locality'  =>  isset( $row->location->Localidad ) ? toUpper($row->location->Localidad) : null,
            'upz_code'  =>  isset( $row->Upz ) ? (string) $row->Upz : null,
            'upz'       =>  isset( $row->upz_name->Upz ) ? toUpper($row->upz_name->Upz) : null,
            'block'     =>  isset( $row->neighborhood->Barrio ) ? toUpper($row->neighborhood->Barrio) : null,
            'urbanization'  => isset( $row->Urbanizacion ) ? toUpper($row->Urbanizacion) : null,
            'address'   =>  isset( $row->Direccion ) ? toUpper($row->Direccion) : null,
            'stratum'   =>  isset( $row->Estrato ) ? (int) $row->Estrato : null,
            'latitude' =>  isset( $row->Latitud ) ? (string) $row->Latitud : null,
            'longitude' =>  isset( $row->Longitud ) ? (string) $row->Longitud : null,
            'rupis'      => isset($row->rupis)
                ? $row->rupis->map(function ($rupi) {
                    return isset($rupi->Rupi) ?(string) $rupi->Rupi : null;
                  })->implode(', ')
                : null,
            'area'     =>  isset( $row->Area ) ? (float) $row->Area : null,
            'green_area'    =>  isset( $row->AreaZVerde ) ? (int) $row->AreaZVerde : 0,
            'grey_area'    =>  isset( $row->AreaZDura ) ? (int) $row->AreaZDura : 0,
            'area_hectare' => isset( $row->Areageo_enHa ) ? (float) $row->Areageo_enHa : null,
            'walking_trails'   => isset( $row->CantidadSenderos ) ? (int) $row->CantidadSenderos : null,
            'walking_trails_status'   => isset( $row->EstadoSendero ) ? toUpper($row->EstadoSendero) : null,
            'access_roads'   => isset( $row->ViasAcceso ) ? toUpper($row->ViasAcceso) : null,
            'access_roads_status'   => isset( $row->EstadoVias ) ? toUpper($row->EstadoVias) : null,
            'households'   => isset( $row->Viviendas ) ? (int) $row->Viviendas : null,
            'children_population'   => isset( $row->PoblacionInfantil ) ? (int) $row->PoblacionInfantil : null,
            'youth_population'   => isset( $row->PoblacionJuvenil ) ? (int) $row->PoblacionJuvenil : null,
            'older_population'   => isset( $row->PoblacionMayor ) ? (int) $row->PoblacionMayor : null,
            'admin_name'     => isset( $row->NomAdministrador ) ? toUpper($row->NomAdministrador) : null,
            'email'     =>  isset( $row->Email ) ? toLower($row->Email) : null,
            'phone'     =>  isset( $row->TelefonoParque ) ? (int) $row->TelefonoParque : null,
            'admin'     => isset( $row->Administracion ) ? toUpper($row->Administracion) : null,
            'general_status'    =>  isset( $row->EstadoGeneral ) ? toUpper($row->EstadoGeneral) : null,
            'enclosure'    =>  isset( $row->Cerramiento ) ? toUpper($row->Cerramiento) : null,
            'zone_type' => isset( $row->TipoZona ) ? toUpper($row->TipoZona) : null,
            'status'    => isset($row->Estado) && (bool) $row->Estado,
            'capacity' =>  isset( $row->Aforo ) ? (float) $row->Aforo : null,
            'stage_type'    => isset( $row->stage_type->tipo ) ? toUpper($row->stage_type->tipo) : null,
            'scale'     =>  isset( $row->scale->Tipo ) ? toUpper($row->scale->Tipo) : null,
            'vocation'  => isset( $row->vocation->name ) ? toUpper($row->vocation->name) : null,
            'vigilance'  => isset( $row->Vigilancia ) ? toUpper($row->Vigilancia) : null,
            'received'  => isset( $row->RecibidoIdrd ) ? toUpper($row->RecibidoIdrd) : null,
            'concept'   =>  isset( $row->certified->EstadoCertificado ) ? toUpper($row->certified->EstadoCertificado) : null,
            'concern'   =>  isset($row->CompeteIDRD) ? toUpper($row->CompeteIDRD) : null,
            'regulation'      => isset($row->CompeteIDRD) ? toUpper( $row->CompeteIDRD ) : null,
            'visited_at' => isset($row->FechaVisita) ? $this->dateToExcel($row->FechaVisita, 'Y-m-d') : null,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_NUMBER,
            'J' => NumberFormat::FORMAT_NUMBER,
            'N' => NumberFormat::FORMAT_NUMBER,
            'O' => NumberFormat::FORMAT_NUMBER,
            'P' => NumberFormat::FORMAT_NUMBER,
            'Q' => NumberFormat::FORMAT_NUMBER,
            'R' => NumberFormat::FORMAT_NUMBER,
            'V' => NumberFormat::FORMAT_NUMBER,
            'W' => NumberFormat::FORMAT_NUMBER,
            'X' => NumberFormat::FORMAT_NUMBER,
            'Y' => NumberFormat::FORMAT_NUMBER,
            'AQ' => NumberFormat::FORMAT_DATE_YYYYMMDD,
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
                $event->sheet->getDelegate()->mergeCells("A1:AQ1");
                $event->sheet->getDelegate()->getCell("A1")
                    ->setValue(toUpper(toUpper(__('parks.excel.title'))))
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

                $user = isset(auth('api')->user()->full_name) ? auth('api')->user()->full_name : 'USUARIO';
                $event->sheet->getDelegate()->getCell("B3")
                    ->setValue($user)
                    ->getStyle()
                    ->getFont()
                    ->setSize(14)
                    ->setBold(true);

                $event->sheet->getDelegate()->getCell("A5")
                    ->setValue(toUpper(__('parks.excel.created_at')).':')
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
                foreach (range('A', 'Q') as $col) {
                    $event->sheet->getDelegate()->getColumnDimension("A$col")->setAutoSize(true);
                }
                // $row = $this->rowNumb-1;
                $row = $event->sheet->getDelegate()->getHighestDataRow('AQ');
                $event->sheet->getDelegate()->getStyle("A7:AQ$row")
                    ->applyFromArray($styleArray);
                $cells = "A7:AQ7";
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
        return toUpper(__('parks.excel.title'));
    }
}
