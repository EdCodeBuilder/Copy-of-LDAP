<?php


namespace App\Modules\Orfeo\src\Resources;


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
            'subject'           =>  isset( $this->ra_asun ) ? toUpper($this->ra_asun) : null,
            'document_type_id'  =>  isset( $this->tdoc_codi ) ? (int) $this->tdoc_codi : null,
            'document_type'     =>  $this->whenLoaded('document_type', isset($this->document_type->name) ? $this->document_type->name : null),
            'business_days'     =>  $this->whenLoaded('document_type', isset($this->document_type->business_days) ? (int) $this->document_type->business_days : null),
            'days_left'         =>  $this->getDaysLeftAttribute(),
            'final_day'         =>  $this->getFinalDateAttribute(),
            'addressee_document'=>  isset( $this->radi_nume_iden ) ? (int) $this->radi_nume_iden : null,
            'addressee_full_name'=>  isset( $this->addressee_full_name ) ? $this->addressee_full_name : null,
            'address'            =>  isset( $this->radi_dire_corr ) ? toUpper($this->radi_dire_corr) : null,
            'city_id'            =>  isset( $this->muni_codi ) ? (int) $this->muni_codi : null,
            'city'               =>  $this->whenLoaded('city', isset($this->city->name) ? $this->city->name : null),
            'state'              =>  $this->whenLoaded('city', isset($this->city->state->name) ? $this->city->state->name : null),
            'country'            =>  $this->whenLoaded('city', isset($this->city->state->country->name) ? $this->city->state->country->name : null),
            'current_user_id'    => isset( $this->radi_usua_actu ) ? (int) $this->radi_usua_actu : null,
            'current_user_name'  => $this->whenLoaded('user', isset($this->user->name) ? $this->user->name : null),
            'current_user_document'  => $this->whenLoaded('user', isset($this->user->document) ? (int) $this->user->document : null),
            'current_dependency' => isset( $this->radi_depe_actu ) ? (int) $this->radi_depe_actu : null,
            'current_dependency_name'  => $this->whenLoaded('dependency', isset($this->dependency->name) ? $this->dependency->name : null),
            'attachments'       => $this->when($this->resource->relationLoaded('attachments'), $this->whenLoaded('attachments', AttachmentResource::collection( $this->attachments ))),
            'associates'        => $this->when($this->resource->relationLoaded('associates'), $this->whenLoaded('associates', AssociatedResource::collection( $this->associates ))),
            'informed'          => $this->when($this->resource->relationLoaded('informed'), $this->whenLoaded('informed', InformedResource::collection( $this->informed ))),
            'history'           => $this->when($this->resource->relationLoaded('history'), $this->whenLoaded('history', HistoryResource::collection( $this->history ))),
            'attachments_count'  => isset( $this->attachments_count ) ? (int) $this->attachments_count : null,
            'associates_count'   => isset( $this->associates_count ) ? (int) $this->associates_count : null,
            'informed_count'     => isset( $this->informed_count ) ? (int) $this->informed_count : null,
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
        $pieces = explode(".", $this->radi_fech_radi);
        $date_filed = isset($pieces[0]) ? Carbon::parse( $pieces[0] ) : now();
        $date = $date ? Carbon::parse( $date ) : null;
        return $date ? $date_filed->diffInDays( $date_filed ) : null;
    }

    /**
     * Get the final day
     *
     * @return string
     */
    public function getFinalDateAttribute()
    {
        $business_date = $this->whenLoaded('document_type', isset($this->document_type->business_days) ? (int) $this->document_type->business_days : 0);
        $pieces = explode(".", $this->radi_fech_radi);
        $date = isset($pieces[0]) ? Carbon::parse( $pieces[0] ) : null;
        return $date ? $date->addDays( (int) $business_date )->format('Y-m-d H:i:s') : null;
    }
}