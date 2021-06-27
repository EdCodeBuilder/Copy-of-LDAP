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
            'text'  => isset($this->text) ? (string) $this->text : null,
            'banner'    =>  isset($this->banner) ? (string) $this->banner : null,
            'cards'     => CardResource::collection( $this->cards ),
        ];
    }
}
