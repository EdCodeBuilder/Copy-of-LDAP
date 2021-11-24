<?php

namespace App\Modules\Parks\src\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MaterialResource extends JsonResource
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
			'IdMaterial'        	=>  (int) isset($this->IdMaterial) ? (int) $this->IdMaterial : null,
			'Material'      	=>  isset($this->Material) ? toUpper($this->Material) : null,
		];
	}
}
