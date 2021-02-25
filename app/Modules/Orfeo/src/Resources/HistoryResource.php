<?php


namespace App\Modules\Orfeo\src\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class HistoryResource extends JsonResource
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
            'id'                =>  isset( $this->id ) ? (int) $this->id : null,
            'filed_id'          =>  isset( $this->radi_nume_radi ) ? (int) $this->radi_nume_radi : null,
            'transaction_id'    =>  isset( $this->sgd_ttr_codigo ) ? (int) $this->sgd_ttr_codigo : null,
            'transaction'       =>  isset( $this->transaction->name ) ? $this->transaction->name : null,
            'dependency_id'     =>  isset( $this->depe_codi ) ? (int) $this->depe_codi : null,
            'dependency'        => isset( $this->dependency->name ) ? $this->dependency->name : null,
            'user_id'           =>  isset( $this->usua_codi ) ? (int) $this->usua_codi : null,
            'user'              => isset( $this->user->name ) ? $this->user->name : null,
            'user_document'     => isset( $this->user->document ) ? (int) $this->user->document : null,
            'addressee_dependency_id'     =>  isset( $this->depe_codi_dest ) ? (int) $this->depe_codi_dest : null,
            'addressee_dependency'        => isset( $this->addressee_dependency->name ) ? $this->addressee_dependency->name : null,
            'addressee_user_id'           =>  isset( $this->usua_codi_dest ) ? (int) $this->usua_codi_dest : null,
            'addressee_user'              => isset( $this->addressee_user->name ) ? $this->addressee_user->name : null,
            'addressee_user_document'     => isset( $this->addressee_user->document ) ? (int) $this->addressee_user->document : null,
            'created_at'        =>  isset( $this->hist_fech ) ? $this->hist_fech : null,
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