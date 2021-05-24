<?php


namespace App\Modules\Contractors\src\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class CareerResource extends JsonResource
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
            'id'                                =>  isset($this->id) ? (int) $this->id : null,
            'name'                              =>  isset($this->name) ? $this->name : null,
            'academic_level_id'                 =>  isset($this->academic_level_id) ? (int) $this->academic_level_id : null,
            'academic_level'                    =>  isset($this->level->name) ? $this->level->name : null,
            'created_at'                        =>  isset($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'                        =>  isset($this->updated_at) ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
