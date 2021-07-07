<?php


namespace App\Modules\Passport\src\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
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
            'id' => isset($this->id) ? (int) $this->id : null,
            'background' => isset($this->background) ? (string) $this->background : null,
            'title' => isset($this->title) ? (string) $this->title : null,
            'icon'  => isset($this->icon) ? (string) $this->icon : null,
            'text'  => isset($this->text) ? json_decode($this->text, true) : null,
            'banner'    =>  isset($this->banner) ? (string) $this->banner : null,
            'cards'     => CardResource::collection( $this->cards ),
            'created_at'    =>  isset($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'    =>  isset($this->updated_at) ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
