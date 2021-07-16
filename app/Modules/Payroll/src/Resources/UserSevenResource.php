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
        /* return [
            'identification'      =>  isset($this->ter_codi) ? $this->ter_codi : null,
            'contract_object'  =>  isset($this->con_objt) ? $this->con_objt : null,
            'person_name'      =>  isset($this->ter_noco) ? $this->ter_noco : null,
            'contract_number'  =>  isset($this->con_ncon) ? $this->con_ncon : null,
            'entry'   =>  isset($this->rubro) ? $this->rubro : null,
            'source'  =>  isset($this->fuente) ? $this->fuente : null,
            'exspense_concept' =>  isset($this->concepto) ? $this->concepto : null,
        ]; */
        /* return [
            'identification'      =>  isset($this->ter_codi) ? $this->ter_codi : null,
            //'contract_object'  =>  isset($this->con_objt) ? $this->con_objt : null,
            'contract_object'  =>  isset($this->mpr_desc) ? $this->mpr_desc : null,
            'person_name'      =>  isset($this->ter_noco) ? $this->ter_noco : null,
            'contract_number'  =>  isset($this->con_ncon) ? $this->con_ncon : null,
            'entry'   =>  isset($this->rubro) ? $this->rubro : null,
            'source'  =>  isset($this->fuente) ? $this->fuente : null,
            'exspense_concept' =>  isset($this->concepto) ? $this->concepto : null,
        ]; */
        return [
            'identification'      =>  isset($this->ter_codi) ? $this->ter_codi : null,
            'contract_object'  =>  isset($this->mpr_desc) ? $this->mpr_desc : null,
            'final_date'  =>  isset($this->con_ffin) ? $this->con_ffin : null,
            'start_date'  =>  isset($this->con_fini) ? $this->con_fini : null,
            'term'      =>  isset($this->con_plaz) ? $this->con_plaz : null,
            'person_name'      =>  isset($this->ter_noco) ? $this->ter_noco : null,
            'contract_number'  =>  isset($this->mpr_ndos) ? $this->mpr_ndos : null,
            'registry_number'  =>  isset($this->mpr_nume) ? $this->mpr_nume : null,
            'entry'   =>  isset($this->rub_codi) ? $this->rub_codi : null,
            'source'  =>  isset($this->arb_care) ? $this->arb_care : null,
            'exspense_concept' =>  isset($this->arb_cpro) ? $this->arb_cpro : null,
            'balance' =>  isset($this->dmp_sald) ? $this->dmp_sald : null,
            'id_aux' =>  isset($this->id_aux) ? $this->id_aux : null,
        ];
    }
}
