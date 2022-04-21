<?php

namespace App\Modules\Parks\src\Resources;

use App\Modules\Parks\src\Constants\Roles;
use App\Modules\Parks\src\Models\Upz;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UpzLocalityResource extends JsonResource
{
      /**
       * Transform the resource into an array.
       *
       * @param  Request  $request
       * @return array
       */
      public function toArray($request)
      {
            $name = isset($this->Upz) ? " - $this->Upz" : null;
            $code = isset($this->cod_upz) ? $this->cod_upz : null;
            return [
                  'name'          =>  isset($this->Upz) ? toUpper($this->Upz) : null,
                  'upz_code'      =>  isset($this->cod_upz) ? (string) $this->cod_upz : null,
                  'composed_name' =>  toUpper("{$code}{$name}"),
                  'locality_name' => $this->whenLoaded('locality') ?  ucfirst(mb_strtolower($this->whenLoaded('locality')->Localidad)) : null,
                  // 'audit'     =>  $this->when(
                  //     auth('api')->check() && auth('api')->user()->can(Roles::can(Upz::class, 'history'), Upz::class),
                  //     AuditResource::collection($this->audits()->with('user:id,name,surname')->latest()->get())
                  // ),

            ];
      }
}
