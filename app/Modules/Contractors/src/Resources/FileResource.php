<?php


namespace App\Modules\Contractors\src\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class FileResource extends JsonResource
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
            'id'           =>  isset($this->id) ? (int) $this->id : null,
            'name'         =>  isset($this->name) ? $this->name : null,
            'path'         =>  $this->checkFile(),
            'file_type_id' =>  isset($this->file_type_id) ? (int) $this->file_type_id : null,
            'file_type'    =>  isset($this->file_type->name) ? $this->file_type->name : null,
            'mime'         =>  isset($this->file_type->mimes) ? $this->file_type->mimes : null,
            'contract_id'  =>  isset($this->contract_id) ? (int) $this->contract_id : null,
            'user_id'      =>  isset($this->user_id) ? (int) $this->user_id : null,
            'user'         =>  isset($this->user->full_name) ? $this->user->full_name : null,
            'created_at'   =>  isset($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'   =>  isset($this->updated_at) ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }

    public function checkFile()
    {
        $id = isset($this->id) ? (int) $this->id : 0;
        $file = isset($this->name) ? $this->name : null;
        if ($file) {
            if (Storage::disk('local')->exists("arl/{$file}")) {
                return route('file.resource', ['file' => $id, 'name' => $file]);
            }
        }
        return null;
    }
}
