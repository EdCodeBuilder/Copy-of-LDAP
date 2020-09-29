<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            "id"          =>    isset( $this->id ) ? (int) $this->id : null,
            "guid"        =>    isset( $this->guid ) ? $this->guid : null,
            "full_name"   =>    isset( $this->full_name ) ? $this->full_name : null,
            "name"        =>    isset( $this->name ) ? $this->name : null,
            "surname"     =>    isset( $this->surname ) ? $this->surname : null,
            "document"    =>    isset( $this->document ) ? $this->document : null,
            "email"       =>    isset( $this->email ) ? $this->email : null,
            "username"    =>    isset( $this->username ) ? $this->username : null,
            "description" =>    isset( $this->description ) ? $this->description : null,
            "dependency"  =>    isset( $this->dependency ) ? $this->dependency : null,
            "company"     =>    isset( $this->company ) ? $this->company : null,
            "phone"       =>    isset( $this->phone ) ? $this->phone : null,
            "ext"         =>    isset( $this->ext ) ? $this->ext : null,
            "expires_at"  =>    isset( $this->expires_at ) ? $this->expires_at->format('Y-m-d H:i:s') : null,
            "created_at"  =>    isset( $this->created_at ) ? $this->created_at->format('Y-m-d H:i:s') : null,
            "updated_at"  =>    isset( $this->updated_at ) ? $this->updated_at->format('Y-m-d H:i:s') : null,
            'ldap'        =>    $this->when( isset( $this->ldap ), new ActiveRecordResource( $this ), []),
            "deleted_at"  =>    isset( $this->deleted_at ) ? $this->deleted_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
