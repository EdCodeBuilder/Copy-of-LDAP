<?php


namespace App\Modules\Orfeo\src\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class AssociatedResource extends JsonResource
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
            'id'                =>  isset( $this->radi_asociado ) ? (int) $this->radi_asociado : null,
            'filed_id'          =>  isset( $this->radi_padre ) ? (int) $this->radi_padre : null,
            'created_at'        =>  isset( $this->fech_crea ) ? $this->fech_crea : null,
        ];
    }

    public function getStatus( $id = null )
    {
        switch ($id) {
            case 1:
                return 'RADICADO';
                break;
            case 2:
                return 'CON IMAGEN PRINCIPAL';
                break;
            case 3:
                return 'IMPRESO';
                break;
            case 4:
                return 'ENVIADO';
                break;
            default:
                return null;
                break;
        }
    }
}