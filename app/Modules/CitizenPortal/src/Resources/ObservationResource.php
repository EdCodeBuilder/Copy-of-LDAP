<?php


namespace App\Modules\CitizenPortal\src\Resources;


use App\Modules\CitizenPortal\src\Constants\Roles;
use App\Modules\CitizenPortal\src\Models\Observation;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ObservationResource extends JsonResource
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
            'id'           =>  isset($this->id) ? (int) $this->id : null,
            'observation'  =>  isset($this->observation) ? (string) $this->observation : null,
            'user'         =>  isset( $this->user->full_name ) ? (string) $this->user->full_name : null,
            'user_id'      =>  isset( $this->user_ldap_id ) ? (int) $this->user_ldap_id : null,
            'profile_id'    =>  isset( $this->profile_id ) ? (int) $this->profile_id : null,
            'read_at'      => isset($this->read_at) ? $this->read_at->format('Y-m-d H:i:s') : null,
            'created_at'   =>  isset($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'   =>  isset($this->updated_at) ? $this->updated_at->format('Y-m-d H:i:s') : null,
            'audits'       => $this->when(
                auth('api')->check() &&
                auth('api')->user()->can(Roles::can(Observation::class,'history'), Observation::class),
                AuditResource::collection($this->audits()->latest()->get()),
                []
            )
        ];
    }
}
