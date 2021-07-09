<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Model;

class PlantOfficials extends Model
{

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'mysql_forms';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_data';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'live_with',
        'name',
        'surname',
        'birthday',
        'country_of_birth_id',
        'state_of_birth_id',
        'city_of_birth_id',
        'document',
        'country_issuance_document_id',
        'state_issuance_document_id',
        'city_issuance_document_id',
        'email',
        'institutional_email',
        'phone',
        'mobile',
        'address',
        'locality_id',
        'upz_id',
        'neighborhood_id',
        'neighborhood',
        'residence_country_id',
        'residence_state_id',
        'residence_city_id',
        'civil_status',
        'spouse_document',
        'spouse_name',
        'spouse_birthday',
        'children_number',
        'children_data',
        'parents_data',
        'dependents',
        'parents_live',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['birthday', 'spouse_birthday'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = toUpper($value);
    }
    public function setSurnameAttribute($value)
    {
        $this->attributes['surname'] = toUpper($value);
    }
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = toLower($value);
    }
    public function setInstitutionalEmailAttribute($value)
    {
        $this->attributes['institutional_email'] = toLower($value);
    }
    public function setAddressAttribute($value)
    {
        $this->attributes['address'] = toUpper($value);
    }
    public function setSpouseNameAttribute($value)
    {
        $this->attributes['spouse_name'] = toUpper($value);
    }
}
