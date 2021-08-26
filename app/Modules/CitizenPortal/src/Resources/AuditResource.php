<?php

namespace App\Modules\CitizenPortal\src\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class AuditResource extends JsonResource
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
            'id'        =>  isset( $this->id ) ? (int) $this->id : null,
            'user'      =>  isset( $this->user->full_name ) ? toUpper($this->user->full_name) : null,
            'event'     =>  isset( $this->event ) ? __("validation.events.{$this->event}") : null,
            'color'     =>  $this->color(),
            'type'      =>  isset( $this->auditable_type ) ? (string) $this->auditable_type : null,
            'type_trans'      =>  isset( $this->auditable_type ) ? __("citizen.classes.{$this->auditable_type}") : null,
            'type_id'   =>  isset( $this->auditable_id ) ? (int) $this->auditable_id : null,
            'old_values'=>  isset( $this->old_values ) ? $this->old_values : null,
            'new_values'=>  isset( $this->new_values ) ? $this->new_values : null,
            'url'       =>  isset( $this->url ) ? (string) $this->url : null,
            'ip'        =>  isset( $this->ip_address ) ? (string) $this->ip_address : null,
            'user_agent'=>  isset( $this->user_agent ) ? (string) $this->user_agent : null,
            'tags'      =>  isset( $this->tags ) ? (string) $this->tags : null,
            "created_at"  =>    isset( $this->created_at ) ? $this->created_at->format('Y-m-d H:i:s') : null,
            "updated_at"  =>    isset( $this->updated_at ) ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }

    public static function headers()
    {
        return [
            [
                'text' => "#",
                'value'  =>  "id",
            ],
            [
                'align' => "right",
                'text' => Str::ucfirst(__('citizen.validations.event')),
                'value'  =>  "event",
            ],
            [
                'text' => Str::ucfirst(__('citizen.validations.type_trans')),
                'value'  =>  'type_trans',
            ],
            [
                'text' => Str::ucfirst(__('citizen.validations.user')),
                'value'  =>  'user',
            ],
            [
                'text' => "IP",
                'value'  =>  "ip",
            ],
            [
                'text' => Str::ucfirst(__('citizen.validations.tags')),
                'value'  =>  "tags",
            ],
            [
                'text'  => Str::ucfirst(__('citizen.validations.created_at')),
                'value'  => 'created_at',
            ],
        ];
    }

    public static function additionalData()
    {
        return [
            [
                'align' => "right",
                'text' => Str::ucfirst(__('citizen.validations.url')),
                'value'  =>  "url",
            ],
            [
                'align' => "right",
                'text' => Str::ucfirst(__('citizen.validations.user_agent')),
                'value'  =>  "user_agent",
            ],
            [
                'align' => "right",
                'text' => Str::ucfirst(__('citizen.validations.new_values')),
                'value'  =>  "new_values",
            ],
            [
                'align' => "right",
                'text' => Str::ucfirst(__('citizen.validations.old_values')),
                'value'  =>  "old_values",
            ],
            [
                'text'  => Str::ucfirst(__('citizen.validations.created_at')),
                'value'  => 'created_at',
            ],
            [
                'text'  => Str::ucfirst(__('citizen.validations.updated_at')),
                'value'  => 'updated_at',
            ],
        ];
    }

    public function color()
    {
        $evt = isset( $this->event ) ? $this->event : null;
        $colors = [
            'created'   =>  'success',
            'updated'   =>  'warning',
            'deleted'   =>  'error',
            'restored'  =>  'info',
        ];
        return isset( $colors[$evt] ) ? $colors[$evt] : 'primary';
    }
}
