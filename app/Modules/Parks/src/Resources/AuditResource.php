<?php

namespace App\Modules\Parks\src\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
            'type'      =>  isset( $this->auditable_type ) ? $this->auditable_type : null,
            'type_trans'      =>  isset( $this->auditable_type ) ? __("parks.classes.{$this->auditable_type}") : null,
            'type_id'   =>  isset( $this->auditable_id ) ? (int) $this->auditable_id : null,
            'old_values'=>  isset( $this->old_values ) ? $this->old_values : null,
            'new_values'=>  isset( $this->new_values ) ? $this->new_values : null,
            'url'       =>  isset( $this->url ) ? $this->url : null,
            'ip'        =>  isset( $this->ip_address ) ? $this->ip_address : null,
            'user_agent'=>  isset( $this->user_agent ) ? $this->user_agent : null,
            'tags'      =>  isset( $this->tags ) ? $this->tags : null,
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
                'sortable' => false
            ],
            [
                'align' => "right",
                'text' => "Evento",
                'value'  =>  "event",
                'sortable' => false
            ],
            [
                'text' => 'Tipo',
                'value'  =>  'type_trans',
                'sortable' => false
            ],
            [
                'text' => 'Usuario',
                'value'  =>  'user',
                'sortable' => false
            ],
            [
                'text' => "IP",
                'value'  =>  "ip",
                'sortable' => false
            ],
            [
                'text' => "Tags",
                'value'  =>  "tags",
                'sortable' => false
            ],
            [
                'text'  => 'Fecha de Registro',
                'value'  => 'created_at',
                'sortable' => false
            ],
        ];
    }

    public static function additionalData()
    {
        return [
            [
                'align' => "right",
                'label' => "URL",
                'field'  =>  "url",
            ],
            [
                'align' => "right",
                'label' => "Navegador",
                'field'  =>  "user_agent",
            ],
            [
                'align' => "right",
                'label' => "Nuevos Valores",
                'field'  =>  "new_values",
            ],
            [
                'align' => "right",
                'label' => "Valores Anteriores",
                'field'  =>  "old_values",
            ],
            [
                'label'  => 'Fecha de Registro',
                'field'  => 'created_at',
            ],
            [
                'label'  => 'Fecha de Actualización',
                'field'  => 'updated_at',
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
