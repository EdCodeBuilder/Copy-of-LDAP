<?php


namespace App\Modules\Orfeo\src\Resources;


use App\Modules\Orfeo\src\Models\Attachment;
use App\Modules\Orfeo\src\Models\Filed;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class FiledResource extends JsonResource
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
            'id'                =>  isset( $this->radi_nume_radi ) ? (int) $this->radi_nume_radi : null,
            'open_new_link'     =>  isset( $this->radi_nume_radi ) ? "https://orfeo.idrd.gov.co/old/linkArchivo.php?&numrad={$this->radi_nume_radi}" : null,
            'subject'           =>  isset( $this->ra_asun ) ? toUpper($this->ra_asun) : null,
            'document_type_id'  =>  isset( $this->tdoc_codi ) ? (int) $this->tdoc_codi : null,
            'document_type'     =>  isset($this->document_type->name) ? $this->document_type->name : null,
            'business_days'     =>  isset($this->document_type->business_days) ? (int) $this->document_type->business_days : null,
            'days_left'         =>  $this->getDaysLeftAttribute(),
            'final_day'         =>  $this->getFinalDateAttribute(),
            'status'            =>  $this->getStatus(),
            'color'             =>  $this->getStatusColor(),
            'read'              =>  isset( $this->radi_leido ) ? (bool) $this->radi_leido : null,
            'folder_id'         =>  isset( $this->carp_codi ) ? (int) $this->carp_codi : null,
            'folder'            =>  isset( $this->folder->name ) ? $this->folder->name : null,
            'addressee_document'=>  isset( $this->radi_nume_iden ) ? (int) $this->radi_nume_iden : null,
            'addressee_full_name'=>  isset( $this->addressee_full_name ) ? $this->addressee_full_name : null,
            'address'            =>  isset( $this->radi_dire_corr ) ? toUpper($this->radi_dire_corr) : null,
            'city_id'            =>  isset( $this->muni_codi ) ? (int) $this->muni_codi : null,
            'city'               =>  isset($this->city->name) ? $this->city->name : null,
            'state'              =>  isset($this->city->state->name) ? $this->city->state->name : null,
            'country'            =>  isset($this->city->state->country->name) ? $this->city->state->country->name : null,
            'current_user_id'    => isset( $this->radi_usua_actu ) ? (int) $this->radi_usua_actu : null,
            'current_user_name'  => isset($this->user->name) ? $this->user->name : null,
            'current_user_document'  => isset($this->user->document) ? (int) $this->user->document : null,
            'current_dependency' => isset( $this->radi_depe_actu ) ? (int) $this->radi_depe_actu : null,
            'current_dependency_name'  => isset($this->dependency->name) ? $this->dependency->name : null,
            'attachments'       => AttachmentResource::collection( $this->whenLoaded('attachments') ),
            'associates'        => AssociatedResource::collection( $this->whenLoaded('associates') ),
            // 'informed'          => InformedResource::collection( $this->whenLoaded('informed') ),
            // 'history'           => HistoryResource::collection( $this->whenLoaded('history') ),
            'attachments_count' => isset( $this->attachments_count ) ? (int) $this->attachments_count : null,
            'associates_count'  => isset( $this->associates_count ) ? (int) $this->associates_count : null,
            'informed_count'    => isset( $this->informed_count ) ? (int) $this->informed_count : null,
            'created_at'        =>  isset( $this->radi_fech_radi ) ? $this->radi_fech_radi : null,
        ];
    }

    /**
     * Get the business days
     *
     * @return string
     */
    public function getDaysLeftAttribute()
    {
        $date = $this->getFinalDateAttribute();
        $date = $date ? Carbon::parse( $date ) : null;
        $days = $date ? now()->diffInDays( $date, false ) : 0;
        return $days < 0 ? 0 : $days;
    }

    /**
     * Get the final day
     *
     * @return string
     */
    public function getFinalDateAttribute()
    {
        $business_date = isset($this->document_type->business_days) ? (int) $this->document_type->business_days : 0;
        $pieces = explode(".", $this->radi_fech_radi);
        $date = isset($pieces[0]) ? Carbon::parse( $pieces[0] ) : null;
        return $date ? $date->addDays( (int) $business_date )->format('Y-m-d H:i:s') : null;
    }

    public function getStatus()
    {
        $status = Attachment::where('anex_radi_nume', $this->radi_nume_radi)
                            ->where('anex_numero', 1)->first();
        return $this->getStatusName( isset( $status->anex_estado ) ? (int) $status->anex_estado : null );
    }

    public function getStatusName( $id = null )
    {
        switch ($id) {
            case 2:
                return 'CON IMAGEN PRINCIPAL';
                break;
            case 3:
                return 'IMPRESO';
                break;
            case 4:
                return 'ENVIADO';
                break;
            case 1:
            default:
                return 'RADICADO';
                break;
        }
    }

    public function getStatusColor()
    {
        return $this->getColor( $this->getStatus() );
    }

    public function getColor( $id = null )
    {
        switch ($id) {
            case 'CON IMAGEN PRINCIPAL':
                return 'info';
                break;
            case 'IMPRESO':
                return 'warning';
                break;
            case 'ENVIADO':
                return 'success';
                break;
            case 'RADICADO':
            default:
                return 'danger';
                break;
        }
    }
}