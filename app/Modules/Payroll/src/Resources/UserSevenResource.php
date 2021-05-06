<?php


namespace App\Modules\Payroll\src\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserSevenResource extends JsonResource
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
            'identificaction'      =>  isset($this->act_desc) ? $this->act_desc : null,
            'person_name'      =>  isset($this->act_desc) ? $this->act_desc : null,
            'contract_number'  =>  isset($this->act_desc) ? $this->act_desc : null,
            'contract_object'  =>  isset($this->act_desc) ? $this->act_desc : null,
            'contract_price'   =>  isset($this->act_desc) ? $this->act_desc : null,
            'contract_source'  =>  isset($this->act_desc) ? $this->act_desc : null,
            'contract_concept' =>  isset($this->act_desc) ? $this->act_desc : null,
            
            // 'paid_contributions'   =>  null,
            // 'delivered_report'   =>  null,
            // 'registry_number'   =>  null,
            // 'registry_number'   =>  null,
            // 'start_date'   =>  null,
            // 'end_date'   =>  null,
            // 'month_pay'   =>  null,
            // 'liquidation_period'   =>  null,
            // 'worked_days'   =>  null,
            // 'total_pay'   =>  null,
            // 'id'                    =>  isset($this->act_codi) ? (int) $this->act_codi : null,
        ];
    }
}
