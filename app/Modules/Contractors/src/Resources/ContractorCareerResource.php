<?php


namespace App\Modules\Contractors\src\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ContractorCareerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $graduate = isset($this->graduate) ? (boolean) $this->graduate : null;
        $text = null;
        if (!is_null($graduate)) {
            $text = $graduate ? 'SI' : 'NO';
        }
        return [
            'contractor_id'                     =>  isset($this->contractor_id) ? (int) $this->contractor_id : null,
            'career_id'                         =>  isset($this->career_id) ? (int) $this->career_id : null,
            'career'                            =>  isset($this->career->name) ? $this->career->name : null,
            'graduate'                          =>  $graduate,
            'graduate_text'                     =>  $text,
            'year_approved'                     =>  isset($this->year_approved) ? (int) $this->year_approved : null,
            'academic_level_id'                 =>  isset($this->career->academic_level_id) ? (int) $this->career->academic_level_id : null,
            'academic_level'                    =>  isset($this->career->level->name) ? $this->career->level->name : null,
        ];
    }
}
