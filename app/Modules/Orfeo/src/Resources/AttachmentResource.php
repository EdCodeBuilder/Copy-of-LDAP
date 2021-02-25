<?php


namespace App\Modules\Orfeo\src\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class AttachmentResource extends JsonResource
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
            'id'                =>  isset( $this->anex_codigo ) ? (int) $this->anex_codigo : null,
            'filed_id'          =>  isset( $this->anex_radi_nume ) ? (int) $this->anex_radi_nume : null,
            'status_id'         =>  isset( $this->anex_estado ) ? (int) $this->anex_estado : null,
            'status'            =>  isset( $this->anex_estado ) ? $this->getStatus( (int) $this->anex_estado ) : null,
            'order'             =>  isset( $this->anex_numero ) ? (int) $this->anex_numero : null,
            'created_at'        =>  isset( $this->anex_radi_fech ) ? $this->anex_radi_fech : null,
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