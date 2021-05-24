<?php


namespace App\Modules\Contractors\src\Resources;


use App\Modules\Contractors\src\Constants\Roles;
use Illuminate\Http\Resources\Json\JsonResource;

class UserContractorResource extends JsonResource
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
        return [
            'id'                    =>  isset($this->id) ? (int) $this->id : null,
            'document_type_id'      =>  isset($this->document_type_id) ? (int) $this->document_type_id : null,
            'document_type'         =>  isset($this->document_type->name) ? $this->document_type->name : null,
            'document'              =>  isset($this->document) ? $this->document : null,
            'name'                  =>  isset($this->name) ? $this->name : null,
            'surname'               =>  isset($this->surname) ? $this->surname : null,
            'birthdate'             =>  isset($this->birthdate) ? $this->birthdate->format('Y-m-d') : null,
            'birthdate_country_id'  =>  isset($this->birthdate_country_id) ? (int) $this->birthdate_country_id : null,
            'birthdate_country'     =>  isset($this->birthdate_country->name) ? $this->birthdate_country->name : null,
            'birthdate_state_id'    =>  isset($this->birthdate_state_id) ? (int) $this->birthdate_state_id : null,
            'birthdate_state'       =>  isset($this->birthdate_state->name) ? $this->birthdate_state->name : null,
            'birthdate_city_id'     =>  isset($this->birthdate_city_id) ? (int) $this->birthdate_city_id : null,
            'birthdate_city'        =>  isset($this->birthdate_city->name) ? $this->birthdate_city->name : null,
            'age'                   =>  isset($this->birthdate) ? $this->birthdate->age : null,
            'sex_id'                =>  isset($this->sex_id) ? $this->sex_id : null,
            'sex'                   =>  isset($this->sex->name) ? $this->sex->name : null,
            'email'                 =>  isset($this->email) ? $this->email : null,
            'institutional_email'   =>  isset($this->institutional_email) ? $this->institutional_email : null,
            'phone'                 =>  isset($this->phone) ? $this->phone : null,
            'eps_id'                =>  isset($this->eps_id) ? (int) $this->eps_id : null,
            'eps_name'              =>  isset($this->eps_name->name) ? $this->eps_name->name : null,
            'eps'                   =>  isset($this->eps) ? $this->eps : null,
            'afp_id'                =>  isset($this->afp_id) ? (int) $this->afp_id : null,
            'afp_name'              =>  isset($this->afp_name->name) ? $this->afp_name->name : null,
            'afp'                   =>  isset($this->afp) ? $this->afp : null,
            'rut'                   =>  null,
            'bank'                   =>  null,
            /*
            'rut'                   =>  isset($this->rut) ? $this->getOriginal('rut') : null,
            'rut_file'              =>  isset($this->rut) ? $this->rut : null,
            'bank'                  =>  isset($this->bank) ? $this->getOriginal('bank') : null,
            'bank_file'             =>  isset($this->bank) ? $this->bank: null,
            */
            'residence_country_id'  =>  isset($this->residence_country_id) ? (int) $this->residence_country_id : null,
            'residence_country'     =>  isset($this->residence_country->name) ? $this->residence_country->name : null,
            'residence_state_id'    =>  isset($this->residence_state_id) ? (int) $this->residence_state_id : null,
            'residence_state'       =>  isset($this->residence_state->name) ? $this->residence_state->name : null,
            'residence_city_id'     =>  isset($this->residence_city_id) ? (int) $this->residence_city_id : null,
            'residence_city'        =>  isset($this->residence_city->name) ? $this->residence_city->name : null,
            'locality_id'           =>  $this->setLocalityId(),
            'locality'              =>  $this->setLocalityName(),
            'upz_id'                =>  $this->setUpzId(),
            'upz'                   =>  $this->setUpzName(),
            'neighborhood_id'       =>  $this->setNeighborhoodId(),
            'neighborhood_name'     =>  $this->setNeighborhoodName(),
            'neighborhood'          =>  isset($this->neighborhood) ? $this->neighborhood : null,
            'address'               =>  isset($this->address) ? $this->address : null,
            $this->merge(new ContractResource($this->contracts()->latest()->first())),
            $this->merge(new ContractorCareerResource($this->careers()->latest()->first())),
            'user_id'               =>  isset($this->user_id) ? (int) $this->user_id : null,
            'user'                  =>  isset($this->user->full_name) ? $this->user->full_name : null,
            'created_at'            =>  isset($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'            =>  isset($this->updated_at) ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }

    public function setNeighborhoodId()
    {
        $id = isset($this->neighborhood_id) ? $this->neighborhood_id : null;
        return isset($this->neighborhood) ? 9999 : $id;
    }

    public function setNeighborhoodName()
    {
        $state = isset($this->residence_city_id) ? (int) $this->residence_city_id : null;
        $neighborhood = isset($this->neighborhood_name->name) ? $this->neighborhood_name->name : null;
        return !is_null($state) && $state != 12688 && is_null($neighborhood) ? 'OTRO' : $neighborhood;
    }

    public function setLocalityId() {
        $state = isset($this->residence_city_id) ? (int) $this->residence_city_id : null;
        $locality = isset($this->locality_id) ? (int) $this->locality_id : null;
        return !is_null($state) && $state != 12688 && is_null($locality) ? 9999 : $locality;
    }

    public function setUpzId()
    {
        $state = isset($this->residence_city_id) ? (int) $this->residence_city_id : null;
        $upz = isset($this->upz_id) ? (int) $this->upz_id : null;
        return !is_null($state) && $state != 12688 && is_null($upz) ? 9999 : $upz;
    }

    public function setLocalityName()
    {
        $state = isset($this->residence_city_id) ? (int) $this->residence_city_id : null;
        $locality = isset($this->locality->name) ? $this->locality->name : null;
        return !is_null($state) && $state != 12688 && is_null($locality) ? 'OTRO' : $locality;
    }

    public function setUpzName()
    {
        $state = isset($this->residence_city_id) ? (int) $this->residence_city_id : null;
        $upz = isset($this->upz->name) ? $this->upz->name : null;
        return !is_null($state) && $state != 12688 && is_null($upz) ? 'OTRO' : $upz;
    }
}
