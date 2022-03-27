<?php

namespace App\Modules\Parks\src\Resources;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\ParkFurniture;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Resources\Json\JsonResource;

class ParkFurnitureResource extends JsonResource
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
            'id'        => isset( $this->id ) ? (int) $this->id : null,
            'park_id'        => isset( $this->idParque ) ? (int) $this->idParque : null,
            'furniture_id'        => isset( $this->idMobiliario ) ? (int) $this->idMobiliario : null,
            'name'      =>  isset( $this->furniture->name ) ? toUpper($this->furniture->name) : null,
            'furniture'      =>  isset( $this->furniture->name ) ? toUpper($this->furniture->name) : null,
            'material_id'        => isset( $this->Material ) ? (int) $this->Material : null,
            'material'      =>  isset( $this->material->Material ) ? toUpper($this->material->Material) : null,
            'good' =>  isset( $this->Bueno ) ? (int) $this->Bueno : null,
            'bad' =>  isset( $this->Malo ) ? (int) $this->Malo : null,
            'regular' =>  isset( $this->Regular ) ? (int) $this->Regular : null,
            'total' =>  isset( $this->Total ) ? (int) $this->Total : null,
            'description' =>  isset( $this->Descrpicion ) ? toUpper($this->Descrpicion) : null,
            'image'    =>  isset( $this->Imagen ) ? $this->image_exist( $this->Imagen ) : null,
            'public' =>  isset( $this->Publico ) ? (int) $this->Publico : null,
            'created_at'    => isset($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'    => isset($this->updated_at) ? $this->updated_at->format('Y-m-d H:i:s') : null,
            'deleted_at'    => isset($this->deleted_at) ? $this->deleted_at->format('Y-m-d H:i:s') : null,
            'audit'     =>  $this->when(
                auth('api')->check() && auth('api')->user()->can(Roles::can(ParkFurniture::class, 'history'), ParkFurniture::class),
                AuditResource::collection($this->audits()->with('user:id,name,surname')->latest()->get())
            )
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
