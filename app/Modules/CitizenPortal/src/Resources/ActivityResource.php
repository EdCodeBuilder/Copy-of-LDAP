<?php


namespace App\Modules\CitizenPortal\src\Resources;


use App\Modules\CitizenPortal\src\Constants\Roles;
use App\Modules\CitizenPortal\src\Models\Activity;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ActivityResource extends JsonResource
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
            'name'      =>  isset($this->name) ? (string) $this->name : null,
            'is_initiate'   =>  isset($this->is_initiate) ? (bool) $this->is_initiate : null,
            'created_at'    =>  isset($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'    =>  isset($this->updated_at) ? $this->updated_at->format('Y-m-d H:i:s') : null,
            'audits'       => $this->when(
                auth('api')->check() &&
                auth('api')->user()->can(Roles::can(Activity::class,'history'), Activity::class),
                AuditResource::collection($this->audits()->latest()->get()),
                []
            )
        ];
    }

    public static function headers()
    {
        return [
            [
                'align' => "right",
                'text' => "#",
                'value'  =>  "id",
                'sortable' => false
            ],
            [
                'align' => "left",
                'text' => Str::ucfirst(__('citizen.validations.name')),
                'value'  =>  "name",
                'sortable' => false
            ],
            [
                'align' => "left",
                'text' => Str::ucfirst(__('citizen.validations.is_initiate')),
                'value'  =>  "is_initiate",
                'sortable' => false
            ],
            [
                'align' => "right",
                'text' => Str::ucfirst(__('citizen.validations.created_at')),
                'value'  =>  "created_at",
                'sortable' => false
            ],
            [
                'align' => "right",
                'text' => Str::ucfirst(__('citizen.validations.updated_at')),
                'value'  =>  "updated_at",
                'sortable' => false
            ],
            [
                'align' => "right",
                'text' => Str::ucfirst(__('citizen.validations.actions')),
                'value'  =>  "actions",
                'sortable' => false
            ],
        ];
    }
}
