<?php


namespace App\Modules\Parks\src\Exports;


use App\Modules\Parks\src\Models\Park;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ParkExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    /**
     * @var Request
     */
    private $request;

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
                        if (count($this->request->get('location')) > 0)
                            return $query->whereIn('Id_Localidad', $this->request->get('location'));

                        return $query;
                    })
                    ->when($this->request->has('park_type'), function ($query) {
                        if (count($this->request->get('park_type')) > 0)
                            return $query->whereIn('Id_Tipo', $this->request->get('park_type'));

                        return $query;
                    })->when($this->request->has('query'), function ($query) {
                        $query->search($this->request->get('query'))
                            ->orWhere('Id_IDRD', 'like', "%{$this->request->get('query')}%");
                    })
                    ->when($this->request->has('vigilance'), function ($query) {
                        if ($this->request->get('vigilance') != null)
                            return $query->where('Vigilancia', $this->request->get('vigilance'));
                        return $query;
                    })
                    ->when($this->request->has('enclosure'), function ($query) {
                        $types = $this->request->get('enclosure');
                        if (count($types) > 0)
                            return $query->whereIn('Cerramiento', $types);

                        return $query;
                    });
    }

    public function headings(): array
    {
        return [
            'ID',
            'CÓDIGO PARQUE',
            'NOMBRE',
            'LOCALIDAD',
            'CÓDIGO UPZ',
            'UPZ',
            'BARRIO',
            'URBANIZACIÓN',
            'DIRECCIÓN',
            'ESTRATO',
            'LATITUD',
            'LONGITUD',
            'RUPIS',
            'ÁREA',
            'ÁREA ZONA VERDE',
            'ÁREA ZONA DURA',
            'ÁREA EN HECTÁREAS',
            'SENDEROS',
            'ESTADO SENDEROS',
            'VÍAS DE ACCESO',
            'ESTADO VÍAS DE ACCESO',
            'VIVIENDAS',
            'POBLACIÓN INFANTIL',
            'POBLACIÓN JUVENIL',
            'POBLACIÓN MAYOR',
            'NOMBRE DEL ADMINISTRADOR',
            'EMAIL',
            'TELÉFONO',
            'ADMINISTRACIÓN',
            'ESTADO GENERAL',
            'TIPO DE CERRAMIENTO',
            'TIPO DE ZONA',
            'RENDER',
            'AFORO',
            'TIPO DE ESCENARIO',
            'ESCALA',
            'VOCACIÓN',
            'VGILANCIA',
            'RECIBIDO IDRD',
            'ESTADO CERTIFICADO',
            'COMPETE IDRD',
            'REGULACIÓN',
            'FECHA DE VISITA',
        ];
    }

    public function map($row): array
    {
        return [
            'id'        =>  (int) isset( $row->Id ) ? (int) $row->Id : null,
            'code'      =>  isset( $row->Id_IDRD ) ? toUpper($row->Id_IDRD) : null,
            'name'      =>  isset( $row->Nombre ) ? toUpper($row->Nombre) : null,
            'locality'  =>  isset( $row->location->Localidad ) ? toUpper($row->location->Localidad) : null,
            'upz_code'  =>  isset( $row->Upz ) ? $row->Upz : null,
            'upz'       =>  isset( $row->upz_name->Upz ) ? toUpper($row->upz_name->Upz) : null,
            'block'     =>  isset( $row->neighborhood->Barrio ) ? toUpper($row->neighborhood->Barrio) : null,
            'urbanization'  => isset( $row->Urbanizacion ) ? toUpper($row->Urbanizacion) : null,
            'address'   =>  isset( $row->Direccion ) ? toUpper($row->Direccion) : null,
            'stratum'   =>  isset( $row->Estrato ) ? (int) $row->Estrato : null,
            'latitude' =>  isset( $row->Latitud ) ? $row->Latitud : null,
            'longitude' =>  isset( $row->Longitud ) ? $row->Longitud : null,
            'rupis'      => isset($row->rupis)
                ? $row->rupis->map(function ($rupi) {
                    return isset($rupi->Rupi) ? $rupi->Rupi : null;
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
            'email'     =>  isset( $row->Email ) ? $row->Email : null,
            'phone'     =>  isset( $row->TelefonoParque ) ? (int) $row->TelefonoParque : null,
            'admin'     => isset( $row->Administracion ) ? toUpper($row->Administracion) : null,
            'general_status'    =>  isset( $row->EstadoGeneral ) ? toUpper($row->EstadoGeneral) : null,
            'enclosure'    =>  isset( $row->Cerramiento ) ? $row->Cerramiento : null,
            'zone_type' => isset( $row->TipoZona ) ? toUpper($row->TipoZona) : null,
            'status'    => isset( $row->Estado ) ? (bool) $row->Estado : false,
            'capacity' =>  isset( $row->Aforo ) ? (float) $row->Aforo : null,
            'stage_type'    => isset( $row->stage_type->tipo ) ? toUpper($row->stage_type->tipo) : null,
            'scale'     =>  isset( $row->scale->Tipo ) ? toUpper($row->scale->Tipo) : null,
            'vocation'  => isset( $row->vocation->name ) ? $row->vocation->name : null,
            'vigilance'  => isset( $row->Vigilancia ) ? $row->Vigilancia : null,
            'received'  => isset( $row->RecibidoIdrd ) ? $row->RecibidoIdrd : null,
            'concept'   =>  isset( $row->certified->EstadoCertificado ) ? toUpper($row->certified->EstadoCertificado) : null,
            'concern'   =>  isset($row->CompeteIDRD) ? $row->CompeteIDRD : null,
            'regulation'      => isset($row->CompeteIDRD) ? toUpper( $row->CompeteIDRD ) : null,
            'visited_at' => isset($row->FechaVisita) ? $row->FechaVisita : null,
        ];
    }
}
