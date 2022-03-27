<?php

namespace App\Modules\Parks\src\Resources;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Origin;
use App\Modules\Parks\src\Models\Park;
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
            'enclosure'    =>  isset( $this->Cerramiento ) ? (string) $this->Cerramiento : null,
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
                isset( $this->PoblacionInfantil ) ? (int) $this->PoblacionInfantil : 0,
                isset( $this->PoblacionJuvenil ) ? (int) $this->PoblacionJuvenil : 0,
                isset( $this->PoblacionMayor ) ? (int) $this->PoblacionMayor : 0,
            ],
            'admin_name'     => isset( $this->NomAdministrador ) ? toUpper($this->NomAdministrador) : null,
            'status_id'    => isset( $this->Estado ) && $this->Estado != 0 ? (int) $this->Estado : null,
            'status'    => isset( $this->status->Estado ) ? toUpper($this->status->Estado) : null,
            'latitude' =>  isset( $this->Latitud ) ? (float) $this->Latitud : null,
            'longitude' =>  isset( $this->Longitud ) ? (float) $this->Longitud : null,
            'urbanization'  => isset( $this->Urbanizacion ) ? toUpper($this->Urbanizacion) : null,
            'vigilance'  => isset( $this->Vigilancia ) ? (string) $this->Vigilancia : null,
            'received'  => isset( $this->RecibidoIdrd ) ? (string) $this->RecibidoIdrd : null,
            'capacity' =>  isset( $this->Aforo ) ? (float) $this->Aforo : null,
            'stage_type_id'    => isset( $this->Id_Tipo_Escenario ) ? (int) $this->Id_Tipo_Escenario : null,
            'stage_type'    => isset( $this->stage_type->tipo ) ? toUpper($this->stage_type->tipo) : null,
            'pqrs'      =>  'atencionalcliente@idrd.gov.co',
            'email'     =>  isset( $this->Email ) ? toLower($this->Email) : null,
            'schedule_service'  =>  'Lunes a Viernes: 6:00 AM - 6:00 PM / Sábados y Domingos: 5:00 AM - 6:00 PM',
            'schedule_admin'    =>  'Lunes a Viernes:  8:00 AM A  4:00 PM / Sábados y Domingos:  9:00 AM -2:00 PM',
            'scale_id'  =>  isset( $this->Id_Tipo ) ? (int) $this->Id_Tipo : null,
            'scale'     =>  isset( $this->scale->Tipo ) ? toUpper($this->scale->Tipo) : null,
            'locality_id'  =>  isset( $this->Id_Localidad ) ? (int) $this->Id_Localidad : null,
            'locality'  =>  isset( $this->location->Localidad ) ? toUpper($this->location->Localidad) : null,
            'address'   =>  isset( $this->Direccion ) ? toUpper($this->Direccion) : null,
            'upz_code'  =>  isset( $this->Upz ) ? (string) $this->Upz : null,
            'upz'       =>  isset( $this->upz_name->Upz ) ? toUpper($this->upz_name->Upz) : null,
            'concept_id'    => isset( $this->EstadoCertificado ) ? (int) $this->EstadoCertificado : null,
            'concept'   =>  isset( $this->certified->EstadoCertificado ) ? toUpper($this->certified->EstadoCertificado) : null,
            'file'      =>  isset( $this->Id_IDRD ) ? $this->certified_exist($this->Id_IDRD) : null,
            'concern'   =>  isset($this->CompeteIDRD) ? (string) $this->CompeteIDRD : null,
            'regulation'      => isset($this->CompeteIDRD) ? toUpper( $this->regulation($this->CompeteIDRD) ) : null,
            'regulation_file' => isset($this->CompeteIDRD) ? $this->regulation_file($this->CompeteIDRD) : null,
            'visited_at' => isset($this->FechaVisita) ? $this->FechaVisita->format('Y-m-d') : null,
            'rupis'      => $this->whenLoaded('rupis', RupiResource::collection($this->rupis)),
            'story'      => $this->whenLoaded('story', StoryResource::collection($this->story)),
            'origin'      => $this->whenLoaded('history', new OriginResource($this->history)),
            'vocation_id'  => isset( $this->Id_Vocacion ) ? (int) $this->Id_Vocacion : null,
            'vocation'  => isset( $this->vocation->name ) ? (string) $this->vocation->name : null,
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
            'pse_payments'   =>  $this->when(auth('api')->check(), $this->payments()),
            'plans' =>  EmergencyPlanResource::collection($this->emergency_plans),
            'created_at'    => isset($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'    => isset($this->updated_at) ? $this->updated_at->format('Y-m-d H:i:s') : null,
            'deleted_at'    => isset($this->deleted_at) ? $this->deleted_at->format('Y-m-d H:i:s') : null,
            'audit'     =>  $this->when(
                auth('api')->check() && auth('api')->user()->can(Roles::can(Park::class, 'history'), Park::class),
                AuditResource::collection($this->audits()->with('user:id,name,surname')->latest()->get())
            ),
            '_links'    => [
                [
                    'rel'     => 'self',
                    'type'    =>  'GET',
                    'href'    => route('parks.show', ['park' => $this])
                ],
                [
                    'rel'     => 'create',
                    'type'    =>  'POST',
                    'href'    => route('parks.store')
                ],
                [
                    'rel'     => 'update',
                    'type'    =>  'PUT/PATCH',
                    'href'    => route('parks.update', ['park' => $this])
                ],
                [
                    'rel'     => 'delete',
                    'type'    =>  'DELETE',
                    'href'    => route('parks.destroy', ['park' => $this])
                ],
            ],
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
        $base = 'https://sim1.idrd.gov.co/SIM/Parques/Certificado/';
        if ( $code ) {
            $path_tif = verify_url( "{$base}{$code}.tif" ) ? "{$base}{$code}.tif" : null;
            $path_pdf = verify_url( "{$base}{$code}.pdf" ) ? "{$base}{$code}.pdf" : null;
            return ($path_tif) ? $path_tif : $path_pdf;
        }
        return null;
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
                return 'https://sim1.idrd.gov.co/SIM/Parques/Certificado/Resolucion2011.pdf';
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

    public function payments()
    {
        if (isset($this->Id_IDRD)) {
            $aux = substr($this->Id_IDRD, 3);
            $aux2 = substr($this->Id_IDRD, 0, 2);
            $code = '1'.$aux2.'0'.$aux;
            return \App\Modules\PaymentGateway\src\Models\Pago::query()
                ->whereHas('park', function ($query) use ($code) {
                    $query->where('codigo_parque', $code);
                })
                ->where('estado_id', 2)
                ->sum('total');
        }

        return null;
    }
}
