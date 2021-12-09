<?php


namespace App\Modules\CitizenPortal\src\Resources;


use App\Modules\CitizenPortal\src\Constants\Roles;
use App\Modules\CitizenPortal\src\Models\Schedule;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class SchedulePublicResource extends JsonResource
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
            'program_name'      =>  isset($this->program_name) ? (string) $this->program_name : null,
            'activity_name'      =>  isset($this->activity_name) ? (string) $this->activity_name : null,
            'stage_name'      =>  isset($this->stage_name) ? (string) $this->stage_name : null,
            'park_code'      =>  isset($this->park_code) ? (string) $this->park_code : null,
            'park_name'      =>  isset($this->park_name) ? (string) $this->park_name : null,
            'park_address'      =>  isset($this->park_address) ? (string) $this->park_address : null,
            'weekday_name'      =>  isset($this->weekday_name) ? (string) $this->weekday_name : null,
            'daily_name'      =>  isset($this->daily_name) ? (string) $this->daily_name : null,
            'min_age'      =>  isset($this->min_age) ? (int) $this->min_age : null,
            'max_age'      =>  isset($this->max_age) ? (int) $this->max_age : null,
            'quota'      =>  isset($this->quota) ? (int) $this->quota : null,
            'is_paid'      =>  isset($this->is_paid) ? (bool) $this->is_paid : null,
            'is_initiate'      =>  isset($this->is_initiate) ? (bool) $this->is_initiate : null,
            'start_date'      =>  isset($this->start_date) ? $this->start_date->format('Y-m-d H:i:s') : null,
            'final_date'      =>  isset($this->final_date) ? $this->final_date->format('Y-m-d H:i:s') : null,
            'is_activated'      =>  isset($this->is_activated) ? (bool) $this->is_activated : null,
            'taken'   => isset($this->users_schedules_count) ? (int) $this->users_schedules_count : 0,
            'users_schedules_count'   => isset($this->users_schedules_count) ? (int) $this->users_schedules_count : 0,
            'created_at'    =>  isset($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'    =>  isset($this->updated_at) ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }

    public static function headers()
    {
        return [
            'headers'   => [
                [
                    'align' => "right",
                    'text' => "#",
                    'value'  =>  "id",
                    'sortable' => true
                ],
                [
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.activity')),
                    'value'  =>  "activity_name",
                    'sortable' => true
                ],
                [
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.min_age')),
                    'value'  =>  "min_age",
                    'sortable' => true
                ],
                [
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.max_age')),
                    'value'  =>  "max_age",
                    'sortable' => true
                ],
                [
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.quota')),
                    'value'  =>  "quota",
                    'sortable' => true
                ],
                [
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.users_schedules_count')),
                    'value'  =>  "users_schedules_count",
                    'sortable' => false
                ],
                [
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.is_paid')),
                    'value'  =>  "is_paid",
                    'sortable' => true
                ],
                [
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.is_activated')),
                    'value'  =>  "is_activated",
                    'sortable' => true
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('citizen.validations.actions')),
                    'value'  =>  "actions",
                    'sortable' => false
                ],
            ],
            'expanded'  => [
                [
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.weekday')),
                    'value'  =>  "weekday_name",
                    'sortable' => true
                ],
                [
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.daily')),
                    'value'  =>  "daily_name",
                    'sortable' => true
                ],
                [
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.is_initiate')),
                    'value'  =>  "is_initiate",
                    'sortable' => true
                ],
                [
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.start_date')),
                    'value'  =>  "start_date",
                    'sortable' => true
                ],
                [
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.final_date')),
                    'value'  =>  "final_date",
                    'sortable' => true
                ],
                [
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.program')),
                    'value'  =>  "program_name",
                    'sortable' => true
                ],
                [
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.stage')),
                    'value'  =>  "stage_name",
                    'sortable' => true
                ],
                [
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.park_code')),
                    'value'  =>  "park_code",
                    'sortable' => true
                ],
                [
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.park')),
                    'value'  =>  "park_name",
                    'sortable' => true
                ],
                [
                    'align' => "left",
                    'text' => Str::ucfirst(__('citizen.validations.address')),
                    'value'  =>  "park_address",
                    'sortable' => true
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('citizen.validations.created_at')),
                    'value'  =>  "created_at",
                    'sortable' => true
                ],
                [
                    'align' => "right",
                    'text' => Str::ucfirst(__('citizen.validations.updated_at')),
                    'value'  =>  "updated_at",
                    'sortable' => true
                ],
            ],
        ];
    }
}
