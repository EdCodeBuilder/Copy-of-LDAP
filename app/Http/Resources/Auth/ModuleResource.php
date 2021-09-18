<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class ModuleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $permissions = ActivityResource::collection($this->whenLoaded('incompatible_access'));
        $vector = [];
        $vector[0] = auth('api')->check() && auth('api')->user()->sim_id ? auth('api')->user()->sim_id : null;
        return [
            'id'        => isset( $this->id ) ? (int) $this->id : null,
            'name'      => isset( $this->name ) ? $this->name : null,
            'area'      => isset( $this->area ) ? $this->area : null,
            'redirect'  => isset( $this->redirect ) ? $this->redirect : null,
            'image'     => isset( $this->image ) ? $this->image : null,
            'status'    => isset( $this->status ) ? (bool) $this->status : null,
            'missionary'        => isset( $this->missionary ) ? (bool) $this->missionary : null,
            'compatible'        => isset( $this->compatible ) ? (bool) $this->compatible : null,
            'access'            => $permissions,
            'encoded'     => $this->when(auth('api')->check(), function () use ($permissions, $vector){
                                return urlencode(
                                    serialize(
                                        array_merge(
                                            $vector,
                                            $permissions
                                                ->collection
                                                ->map(function ($data) {
                                                    return isset( $data->permission[0]['Estado'] ) ? $data->permission[0]['Estado'] : 0;
                                                })
                                                ->toArray()
                                        )
                                    )
                                );
                            }, []),
            "created_at"  =>    isset( $this->created_at ) ? $this->created_at->format('Y-m-d H:i:s') : null,
            "updated_at"  =>    isset( $this->updated_at ) ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
