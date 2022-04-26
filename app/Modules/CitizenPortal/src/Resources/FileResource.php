<?php


namespace App\Modules\CitizenPortal\src\Resources;


use App\Modules\CitizenPortal\src\Constants\Roles;
use App\Modules\CitizenPortal\src\Models\File;
use App\Modules\CitizenPortal\src\Models\Observation;
use App\Modules\CitizenPortal\src\Models\Profile;
use App\Modules\CitizenPortal\src\Models\Status;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

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
            'file'          =>  isset( $this->file ) ? (string) $this->file : null,
            'file_type_id'  =>  isset( $this->file_type_id ) ? (int) $this->file_type_id : null,
            'file_type'     =>  isset( $this->file_type->name ) ? (string) $this->file_type->name : null,
            'profile_id'    =>  isset( $this->profile_id ) ? (int) $this->profile_id : null,
            'citizen_schedule_id'    =>  isset( $this->citizen_schedule_id ) ? (int) $this->citizen_schedule_id : null,
            'status_id'     =>  isset( $this->status_id ) ? (int) $this->status_id : null,
            'color'         =>  isset( $this->status_id ) ? Status::getColor($this->status_id) : null,
            'status'        =>  isset( $this->status->name ) ? (string) $this->status->name : null,
            'created_at'   =>  isset($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'   =>  isset($this->updated_at) ? $this->updated_at->format('Y-m-d H:i:s') : null,
            'audits'       => $this->when(
                auth('api')->check() &&
                auth('api')->user()->can(Roles::can(File::class,'history'), File::class),
                AuditResource::collection($this->audits()->latest()->get()),
                []
            )
        ];
    }
}
