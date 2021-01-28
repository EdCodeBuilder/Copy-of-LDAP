<?php

namespace App\Modules\Parks\src\Resources;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Resources\Json\JsonResource;

class ParkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'        =>  (int) isset( $this->Id ) ? (int) $this->Id : null,
            'code'      =>  isset( $this->Id_IDRD ) ? toUpper($this->Id_IDRD) : null,
            'name'      =>  isset( $this->Nombre ) ? toUpper($this->Nombre) : null,
            'phone'     =>  isset( $this->TelefonoParque ) ? (int) $this->TelefonoParque : null,
            'stratum'   =>  isset( $this->Estrato ) ? (int) $this->Estrato : null,
            'image'     =>  isset( $this->Imagen ) ? $this->Imagen : null,
            'block'     =>  isset( $this->neighborhood->Barrio ) ? toUpper($this->neighborhood->Barrio) : null,
            'neighborhood_id'     =>  isset( $this->Id_Barrio ) ? (int) $this->Id_Barrio : null,

            'area'     =>  isset( $this->Area ) ? (float) $this->Area : null,
            'area_hectare' => isset( $this->Areageo_enHa ) ? (float) $this->Areageo_enHa : null,

            'general_status'    =>  isset( $this->EstadoGeneral ) ? toUpper($this->EstadoGeneral) : null,
            'enclosure'    =>  isset( $this->Cerramiento ) ? $this->Cerramiento : null,
            'households'   => isset( $this->Viviendas ) ? (int) $this->Viviendas : null,
            'zone_type' => isset( $this->TipoZona ) ? toUpper($this->TipoZona) : null,
            'admin'     => isset( $this->Administracion ) ? toUpper($this->Administracion) : null,
            'walking_trails'   => isset( $this->CantidadSenderos ) ? (int) $this->CantidadSenderos : null,
            'walking_trails_status'   => isset( $this->EstadoSendero ) ? toUpper($this->EstadoSendero) : null,
            'access_roads'   => isset( $this->ViasAcceso ) ? toUpper($this->ViasAcceso) : null,
            'access_roads_status'   => isset( $this->EstadoVias ) ? toUpper($this->EstadoVias) : null,
            'children_population'   => isset( $this->PoblacionInfantil ) ? (int) $this->PoblacionInfantil : null,
            'youth_population'   => isset( $this->PoblacionJuvenil ) ? (int) $this->PoblacionJuvenil : null,
            'older_population'   => isset( $this->PoblacionMayor ) ? (int) $this->PoblacionMayor : null,
            'population_chart'   => [
                [
                    'name'  =>  'Total',
                    'data'  => [
                        isset( $this->PoblacionInfantil ) ? (int) $this->PoblacionInfantil : 0,
                        isset( $this->PoblacionJuvenil ) ? (int) $this->PoblacionJuvenil : 0,
                        isset( $this->PoblacionMayor ) ? (int) $this->PoblacionMayor : 0,
                    ]
                ]
            ],
            'admin_name'     => isset( $this->NomAdministrador ) ? toUpper($this->NomAdministrador) : null,
            'status_id'    => isset( $this->Estado ) ? (int) $this->Estado : null,
            'status'    => isset( $this->status->Estado ) ? toUpper($this->status->Estado) : null,
            'latitude' =>  isset( $this->Latitud ) ? $this->Latitud : null,
            'longitude' =>  isset( $this->Longitud ) ? $this->Longitud : null,
            'urbanization'  => isset( $this->Urbanizacion ) ? toUpper($this->Urbanizacion) : null,
            'vigilance'  => isset( $this->Vigilancia ) ? $this->Vigilancia : null,
            'received'  => isset( $this->RecibidoIdrd ) ? $this->RecibidoIdrd : null,
            'capacity' =>  isset( $this->Aforo ) ? (float) $this->Aforo : null,
            'stage_type_id'    => isset( $this->Id_Tipo_Escenario ) ? (int) $this->Id_Tipo_Escenario : null,
            'stage_type'    => isset( $this->stage_type->tipo ) ? toUpper($this->stage_type->tipo) : null,

            'pqrs'      =>  'atencionalcliente@idrd.gov.co',
            'email'     =>  isset( $this->Email ) ? $this->Email : null,
            'schedule_service'  =>  'Lunes a Viernes: 6:00 AM - 6:00 PM / Sábados y Domingos: 5:00 AM - 6:00 PM',
            'schedule_admin'    =>  'Lunes a Viernes:  8:00 AM A  4:00 PM / Sábados y Domingos:  9:00 AM -2:00 PM',
            'scale_id'  =>  isset( $this->Id_Tipo ) ? (int) $this->Id_Tipo : null,
            'scale'     =>  isset( $this->scale->Tipo ) ? toUpper($this->scale->Tipo) : null,
            'locality_id'  =>  isset( $this->Id_Localidad ) ? (int) $this->Id_Localidad : null,
            'locality'  =>  isset( $this->location->Localidad ) ? toUpper($this->location->Localidad) : null,
            'address'   =>  isset( $this->Direccion ) ? toUpper($this->Direccion) : null,
            'upz_code'  =>  isset( $this->Upz ) ? $this->Upz : null,
            'upz'       =>  isset( $this->upz_name->Upz ) ? toUpper($this->upz_name->Upz) : null,
            'concept_id'    => isset( $this->EstadoCertificado ) ? (int) $this->EstadoCertificado : null,
            'concept'   =>  isset( $this->certified->EstadoCertificado ) ? toUpper($this->certified->EstadoCertificado) : null,
            'file'      =>  isset( $this->Id_IDRD ) ? $this->certified_exist($this->Id_IDRD) : null,
            'concern'   =>  isset($this->CompeteIDRD) ? $this->CompeteIDRD : null,
            'regulation'      => isset($this->CompeteIDRD) ? toUpper( $this->regulation($this->CompeteIDRD) ) : null,
            'regulation_file' => isset($this->CompeteIDRD) ? $this->regulation_file($this->CompeteIDRD) : null,
            'visited_at' => isset($this->FechaVisita) ? $this->checkDate($this->FechaVisita) : null,
            'rupis'      => $this->whenLoaded('rupis', RupiResource::collection($this->rupis)),
            'story'      => $this->whenLoaded('story', StoryResource::collection($this->story)),



            'color'      =>  isset( $this->Id_Tipo ) ? $this->getColor((int) $this->Id_Tipo) : 'grey',
            'green_area'    =>  isset( $this->AreaZVerde ) ? (int) $this->AreaZVerde : 0,
            'grey_area'    =>  isset( $this->AreaZDura ) ? (int) $this->AreaZDura : 0,
            'area_chart'   => [
                [
                    'name'  =>  'Total',
                    'data'  => [
                        isset( $this->AreaZVerde ) ? (int) $this->AreaZVerde : 0,
                        isset( $this->AreaZDura ) ? (int) $this->AreaZDura : 0,
                    ]
                ]
            ],
            'map'   =>  $this->setMap(),
            'audit' => AuditResource::collection($this->audits()->with('user:id,name,surname')->latest()->get())
        ];
    }

    public function getColor($id = null)
    {
        switch ($id) {
            case 1:
            case 2:
            case 3:
                return 'success';
                break;
            default;
                return 'grey';
                break;
        }
    }

    public function certified_exist( $code = null )
    {
        $base = 'https://www.idrd.gov.co/SIM/Parques/Certificado/';
        if ( $code ) {
            $path_tif = $this->urlExists( "{$base}{$code}.tif" ) ? "{$base}{$code}.tif" : null;
            $path_pdf = $this->urlExists( "{$base}{$code}.pdf" ) ? "{$base}{$code}.pdf" : null;
            return ($path_tif) ? $path_tif : $path_pdf;
        }
        return null;
    }

    function urlExists($url = null)
    {
        try {
            if ($url == null) {
                return false;
            }
            $client = new Client();
            $data = $client->head( $url );
            $status = $data->getStatusCode();
            return $status >= 200 && $status < 300;
        } catch (ClientException $e) {
            return false;
        }
    }

    public function regulation( $text = null )
    {
        $locality = isset( $this->location->Localidad ) ? toUpper($this->location->Localidad) : null ;
        switch ($text) {
            case 'Junta Administradora Local':
                return "Alcaldía Local / {$text} / {$locality}";
                break;
            case 'SI':
                return 'IDRD';
                break;
            default:
                return $text;
        }
    }

    public function regulation_file( $text = null )
    {
        switch ($text) {
            case 'Junta Administradora Local':
                return 'https://www.idrd.gov.co/SIM/Parques/Certificado/Resolucion2011.pdf';
                break;
            default:
                return null;
        }
    }

    public function setMap()
    {
        $id = isset( $this->Id_IDRD ) ? toUpper($this->Id_IDRD) : null;
        if ($id) {
            return "https://mapas.bogota.gov.co/?l=436&b=262&show_menu=false&e=-74.57201001759988,4.2906625340901,-73.61070630666201,4.928542831147915,4686&layerFilter=436;ID_PARQUE='{$id}'";
        }
        return "https://mapas.bogota.gov.co/?l=436&b=262&show_menu=false&e=-74.57201001759988,4.2906625340901,-73.61070630666201,4.928542831147915,4686&layerFilter=436";
    }

    public function checkDate($date)
    {
        return isAValidDate( $date )
            ? Carbon::parse( $date )->format('Y-m-d')
            : toUpper($date);
    }
}