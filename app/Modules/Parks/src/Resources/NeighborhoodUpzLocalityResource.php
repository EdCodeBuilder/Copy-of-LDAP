<?php

namespace App\Modules\Parks\src\Resources;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Neighborhood;
use App\Modules\Parks\src\Models\Upz;
use Illuminate\Http\Resources\Json\JsonResource;

class NeighborhoodUpzLocalityResource extends JsonResource
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
                  'neighborhood_id'    =>  isset($this->IdBarrio) ? (int) $this->IdBarrio : null,
                  'neighborhood_name'  =>  isset($this->Barrio) ? toUpper($this->Barrio) : null,
                  'upz_name' => $this->whenLoaded('upz') ? ucfirst(mb_strtolower($this->whenLoaded('upz')->Upz))  : null,
                  'upz_code'   =>  isset($this->CodUpz) ? (string) $this->CodUpz : null,
                  'locality_name' => $this->whenLoaded('upz') ? ucfirst(mb_strtolower($this->whenLoaded('upz')->locality->Localidad))  : null,
                  // 'audit'     =>  $this->when(
                  //     auth('api')->check() && auth('api')->user()->can(Roles::can(Neighborhood::class, 'history'), Neighborhood::class),
                  //     AuditResource::collection($this->audits()->with('user:id,name,surname')->latest()->get())
                  // )
            ];
      }
}
