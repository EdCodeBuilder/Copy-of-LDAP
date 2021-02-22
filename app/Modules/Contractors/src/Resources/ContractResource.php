<?php


namespace App\Modules\Contractors\src\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ContractResource extends JsonResource
{
    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $transport = isset($this->transport) ? $this->transport : null;
        return [
            'contract_id'                       =>  isset($this->id) ? (int) $this->id : null,
            'contract'                          =>  isset($this->contract) ? $this->contract : null,
            'transport'                         =>  $transport,
            'transport_text'                    =>  $transport ? 'SI' : 'NO',
            'position'                          =>  isset($this->position) ? $this->position : null,
            'start_date'                        =>  isset($this->start_date) ? $this->start_date->format('Y-m-d') : null,
            'final_date'                        =>  isset($this->final_date) ? $this->final_date->format('Y-m-d') : null,
            'start_suspension_date'             =>  isset($this->start_suspension_date) ? $this->start_suspension_date->format('Y-m-d') : null,
            'final_suspension_date'             =>  isset($this->final_suspension_date) ? $this->final_suspension_date->format('Y-m-d') : null,
            'total'                             =>  isset($this->total) ? (int) $this->total : null,
            'day'                               =>  isset($this->day) ? $this->day : [],
            'day_string'                        =>  isset($this->day) ? $this->getOriginal('day') : null,
            'risk'                              =>  isset($this->risk) ? $this->risk : null,
            'subdirectorate_id'                 =>  isset($this->subdirectorate_id) ? (int) $this->subdirectorate_id : null,
            'subdirectorate'                    =>  isset($this->subdirectorate->name) ? $this->subdirectorate->name : null,
            'dependency_id'                     =>  isset($this->dependency_id) ? (int) $this->dependency_id : null,
            'dependency'                        =>  isset($this->dependency->name) ? $this->dependency->name : null,
            'other_dependency_subdirectorate'   =>  isset($this->other_dependency_subdirectorate) ? $this->other_dependency_subdirectorate : null,
            'supervisor_email'                  =>  isset($this->supervisor_email) ? $this->supervisor_email : null,
            'contractor_id'                     =>  isset($this->contractor_id) ? (int) $this->contractor_id : null,
            'lawyer_id'                         =>  isset($this->lawyer_id) ? (int) $this->lawyer_id : null,
            'lawyer'                            =>  isset($this->lawyer->full_name) ? $this->lawyer->full_name : null,
            'contractor'                        =>  new ContractorResource($this->whenLoaded('contractor' )),
            'contract_type_id'                  =>  isset($this->contract_type_id) ? (int) $this->contract_type_id : null,
            'contract_type'                     =>  isset($this->contract_type->name) ? $this->contract_type->name : null,
            'files'                             =>  FileResource::collection( $this->files ),
            'created_at'                        =>  isset($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'                        =>  isset($this->updated_at) ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }

    public static function headers()
    {
        return [
            [
                'text' => "#",
                'value'  =>  "contract_id",
            ],
            [
                'text' => 'Número de contrato',
                'value'  =>  'contract',
                'icon'  =>  'mdi-file',
            ],
            [
                'text' => 'Tipo de trámite',
                'value'  =>  'contract_type',
                'icon'  =>  'mdi-clipboard-text-outline',
            ],
            [
                'text' => 'Se suministra transporte',
                'value'  =>  'transport_text',
                'icon'  =>  'mdi-car',
            ],
            [
                'text'  => 'Cargo a desempeñar',
                'value'  => 'position',
                'icon'   => 'mdi-book-account-outline',
            ],
            [
                'text'  => 'Fecha inicio contrato',
                'value'  => 'start_date',
                'icon'   => 'mdi-calendar',
            ],
            [
                'text'  => 'Fecha fin contrato',
                'value'  => 'final_date',
                'icon'   => 'mdi-calendar',
            ],
            [
                'text'  => 'Fecha inicio suspención',
                'value'  => 'start_suspension_date',
                'icon'   => 'mdi-calendar',
            ],
            [
                'text'  => 'Fecha fin suspención',
                'value'  => 'final_suspension_date',
                'icon'   => 'mdi-calendar',
            ],
            [
                'text'  => 'Valor total del contrato',
                'value'  => 'total',
                'icon'   => 'mdi-currency-usd',
            ],
            [
                'text'  => 'Día(s) que no trabaja',
                'value'  => 'day_string',
                'icon'   => 'mdi-calendar',
            ],
            [
                'text'  => 'Nivel de riesgo',
                'value'  => 'risk',
                'icon'   => 'mdi-alert-outline',
            ],
            [
                'text'  => 'Subdirección',
                'value'  => 'subdirectorate',
                'icon'   => 'mdi-domain',
            ],
            [
                'text'  => 'Dependencia',
                'value'  => 'dependency',
                'icon'   => 'mdi-layers',
            ],
            [
                'text'  => 'Otra subdirección o dependencia',
                'value'  => 'other_dependency_subdirectorate',
                'icon'   => 'mdi-domain',
            ],
            [
                'text'  => 'Creado por',
                'value'  => 'lawyer',
                'icon'   => 'mdi-account',
            ],
            [
                'text'  => 'Fecha de Registro',
                'value'  => 'created_at',
                'icon'   => 'mdi-calendar',
            ],
            [
                'text'  => 'Fecha de Actualización',
                'value'  => 'updated_at',
                'icon'   => 'mdi-calendar',
            ],
        ];
    }
}
