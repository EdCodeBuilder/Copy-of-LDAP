<?php


namespace App\Modules\Passport\src\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class CommentsResource extends JsonResource
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
            'id'            =>  isset($this->id) ? (int) $this->id : null,
            'name'          =>  isset($this->name) ? (string) $this->name : null,
            'comment'       =>  isset($this->comment) ? (string) $this->comment : null,
            'agreement_id'  =>  isset($this->agreement_id) ? (int) $this->agreement_id : null,
            'created_at'    =>  isset($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'    =>  isset($this->updated_at) ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
