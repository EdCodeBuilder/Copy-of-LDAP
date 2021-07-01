<?php


namespace App\Modules\Passport\src\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
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
            'title' => isset($this->title) ? (string) $this->title : null,
            'description' => isset($this->description) ? (string) $this->description : null,
            'btn_text' => isset($this->btn_text) ? (string) $this->btn_text : null,
            'flex'  => isset($this->flex) ? (int) $this->flex : null,
            'src'   => isset($this->src) ? (string) $this->src : null,
            'lottie' => isset($this->lottie) ? $this->lottie : null,
            'to'    => isset($this->to) ? (string) $this->to : null,
            'href'  => isset($this->href) ? (string) $this->href : null,
            'dashboard_id'  => isset($this->dashboard_id) ? (int) $this->dashboard_id : null,
            'created_at'    =>  isset($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'    =>  isset($this->updated_at) ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
