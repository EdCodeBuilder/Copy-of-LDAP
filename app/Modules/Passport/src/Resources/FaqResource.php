<?php


namespace App\Modules\Passport\src\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class FaqResource extends JsonResource
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
            'id'        =>  isset($this->id) ? (int) $this->id : null,
            'header'      =>  isset($this->header) ? (string) $this->header : null,
            'answer'      =>  isset($this->answer) ? json_decode($this->answer, true) : null,
            'link'      =>  isset($this->url) ? (string) $this->url : null,
            'to'      =>  isset($this->to) ? (string) $this->to : null,
            'video'      =>  isset($this->video) ? (string) $this->video : null,
            'created_at'    =>  isset($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'    =>  isset($this->updated_at) ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
