<?php

namespace App\Modules\Parks\src\Resources;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Resources\Json\JsonResource;

class EndowmentResource extends JsonResource
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
            'id'    =>  isset( $this->Id ) ? (int) $this->Id : null,
            'park_id'    =>  isset( $this->Id_Parque ) ? (int) $this->Id_Parque : null,
            'endowment_id'    =>  isset( $this->Id_Dotacion ) ? (int) $this->Id_Dotacion : null,
            'endowment_num'    =>  isset( $this->Num_Dotacion ) ? (int) $this->Num_Dotacion : null,
            'endowment'         => isset($this->endowment) ? toUpper($this->endowment->Dotacion) : null,
            'status_id'    =>  isset( $this->Estado ) ? (int) $this->Estado : null,
            'status'    =>  isset( $this->status ) ? toUpper($this->status->Estado) : null,
            'material'    =>  isset( $this->Material ) ? toUpper($this->Material) : null,
            'illumination'    =>  isset( $this->iluminacion ) ? toUpper($this->iluminacion) : null,
            'economic_use'    =>  isset( $this->Aprovechamientoeconomico ) ? toUpper($this->Aprovechamientoeconomico) : null,
            'area'    =>  isset( $this->Area ) ? (int) $this->Area : null,
            'floor_material_id'    =>  isset( $this->MaterialPiso ) ? (int) $this->MaterialPiso : null,
            'floor_material'    =>  isset($this->material) ? toUpper($this->material->Material) : null,
            'equipment_id'    =>  isset( $this->endowment ) ? (int) $this->endowment->Id_Equipamento : null,
            'equipment'    =>  isset( $this->endowment->equipment ) ? toUpper($this->endowment->equipment->Equipamento) : null,
            'enclosure_id'    =>  isset( $this->Cerramiento ) ? (int) $this->Cerramiento : null,
            'enclosure'    =>  isset( $this->enclosure ) ? toUpper($this->enclosure->Cerramiento) : null,
            'dressing_room'    =>  isset( $this->Camerino ) ? toUpper($this->Camerino) : null,
            'light'    =>  isset( $this->Luz ) ? toUpper($this->Luz) : null,
            'water'    =>  isset( $this->Agua ) ? toUpper($this->Agua) : null,
            'gas'    =>  isset( $this->Gas ) ? toUpper($this->Gas) : null,
            'capacity'    =>  isset( $this->Capacidad ) ? (int) $this->Capacidad : null,
            'lane'    =>  isset( $this->Carril ) ? (int) $this->Carril : null,
            'bath'    =>  isset( $this->Bano ) ? (int) $this->Bano : null,
            'sanitary_battery'    =>  isset( $this->BateriaSanitaria ) ? (int) $this->BateriaSanitaria : null,
            'description'    =>  isset( $this->Descripcion ) ? $this->Descripcion : null,
            'maintenance_diagnosis'    =>  isset( $this->Diag_Mantenimiento ) ? $this->Diag_Mantenimiento : null,
            'construction_diagnosis'    =>  isset( $this->Diag_Construcciones ) ? $this->Diag_Construcciones : null,
            'positioning'    =>  isset( $this->Posicionamiento ) ? $this->Posicionamiento : null,
            'destination'    =>  isset( $this->Destinacion ) ? $this->Destinacion : null,
	        // 'image'    =>  isset( $this->Imagen ) ? $this->Imagen: null,
            'image'    =>  isset( $this->Imagen ) ? $this->image_exist( $this->Imagen ) : null,
            'date'    =>  isset( $this->Fecha ) ? $this->Fecha : null,
            'enclosure_type'    =>  isset( $this->TipoCerramiento ) ? toUpper($this->TipoCerramiento) : null,
            'enclosure_height'    =>  isset( $this->AlturaCerramiento ) ? toUpper($this->AlturaCerramiento) : null,
            'long'    =>  isset( $this->Largo ) ? (int) $this->Largo : null,
            'width'    =>  isset( $this->Ancho ) ? (int) $this->Ancho : null,
            'covered'    =>  isset( $this->Cubierto ) ? $this->Cubierto : null,
            'dunt'    =>  isset( $this->Dunt ) ? (int) $this->Dunt : null,
            'male_bath'    =>  isset( $this->B_Masculino ) ? (int) $this->B_Masculino : null,
            'female_bath'    =>  isset( $this->B_Femenino ) ? (int) $this->B_Femenino : null,
            'disabled_bath'    =>  isset( $this->B_Discapacitado ) ? (int) $this->B_Discapacitado : null,
            'car_parking'    =>  isset( $this->C_Vehicular ) ? (int) $this->C_Vehicular : null,
            'bike_parking'    =>  isset( $this->C_BiciParqueadero ) ? (int) $this->C_BiciParqueadero : null,
            'public'    =>  isset( $this->Publico ) ? (int) $this->Publico : null,
            'sector_id'    =>  isset( $this->i_fk_id_sector ) ? (int) $this->i_fk_id_sector : null,
            'map'    =>  isset( $this->mapeo ) ? $this->mapeo : null,
        ];
    }

    public function image_exist( $image = null )
    {
        $base = 'https://sim1.idrd.gov.co/SIM/Parques/Foto/';
        if ( $image ) {
            return $this->urlExists( "{$base}{$image}" ) ? "{$base}{$image}" : null;
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
}
