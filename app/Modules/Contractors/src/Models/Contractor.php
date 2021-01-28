<?php

namespace App\Modules\Contractors\src\Models;

use App\Models\Security\Afp;
use App\Models\Security\CityLDAP;
use App\Models\Security\CountryLDAP;
use App\Models\Security\DocumentType;
use App\Models\Security\Eps;
use App\Models\Security\Sex;
use App\Models\Security\StateLDAP;
use App\Models\Security\User;
use App\Modules\Parks\src\Models\Location;
use App\Modules\Parks\src\Models\Neighborhood;
use App\Modules\Parks\src\Models\Upz;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;

class Contractor extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, Notifiable;

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'mysql_contractors';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contractors';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'document_type_id',
        'document',
        'name',
        'surname',
        'birthdate',
        'sex_id',
        'email',
        'institutional_email',
        'phone',
        'eps_id',
        'eps',
        'afp_id',
        'afp',
        'residence_country_id',
        'residence_state_id',
        'residence_city_id',
        'locality_id',
        'upz_id',
        'neighborhood_id',
        'neighborhood',
        'address',
        'user_id',
        'modifiable',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'document_type_id'  => 'int',
        'document'  => 'int',
        'modifiable'  => 'bool',
        'sex_id'    => 'int',
        'eps_id'    => 'int',
        'afp_id'    => 'int',
        'residence_country_id'  => 'int',
        'residence_state_id'    => 'int',
        'residence_city_id' => 'int',
        'locality_id'       => 'int',
        'upz_id'            => 'int',
        'neighborhood_id'   => 'int',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [ 'birthdate' ];

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */

    /**
     * Set value in uppercase
     *
     * @param $value
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = toUpper($value);
    }

    /**
     * Set value in uppercase
     *
     * @param $value
     */
    public function setSurnameAttribute($value)
    {
        $this->attributes['surname'] = toUpper($value);
    }

    /**
     * Set value in lowercase
     *
     * @param $value
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = toLower($value);
    }

    /**
     * Set value in lowercase
     *
     * @param $value
     */
    public function setInstitutionalEmailAttribute($value)
    {
        $this->attributes['institutional_email'] = toLower($value);
    }

    /**
     * Set value in uppercase
     *
     * @param $value
     */
    public function setEpsAttribute($value)
    {
        $this->attributes['eps'] = toUpper($value);
    }

    /**
     * Set value in uppercase
     *
     * @param $value
     */
    public function setAfpAttribute($value)
    {
        $this->attributes['afp'] = toUpper($value);
    }

    /**
     * Set value in uppercase
     *
     * @param $value
     */
    public function setNeighborhoodAttribute($value)
    {
        $this->attributes['neighborhood'] = toUpper($value);
    }

    /**
     * Set value in uppercase
     *
     * @param $value
     */
    public function setAddressAttribute($value)
    {
        $this->attributes['address'] = toUpper($value);
    }

    /**
     * Set value in uppercase
     *
     * @param $value
     */
    public function setLocalityIdAttribute($value)
    {
        $this->attributes['locality_id'] = $value == 9999 ? null : $value;
    }

    /**
     * Set value in uppercase
     *
     * @param $value
     */
    public function setUpzIdAttribute($value)
    {
        $this->attributes['upz_id'] = $value == 9999 ? null : $value;
    }

    /**
     * Set value in uppercase
     *
     * @param $value
     */
    public function setNeighborhoodIdAttribute($value)
    {
        $this->attributes['neighborhood_id'] = $value == 9999 ? null : $value;
    }

    /*
     * ---------------------------------------------------------
     * Eloquent Relations
     * ---------------------------------------------------------
    */

    public function contracts()
    {
        return $this->hasMany(Contract::class)->latest();
    }

    public function document_type()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    public function eps_name()
    {
        return $this->hasOne(Eps::class, 'id', 'eps_id');
    }

    public function sex()
    {
        return $this->hasOne(Sex::class, 'Id_Genero', 'sex_id');
    }

    public function afp_name()
    {
        return $this->hasOne(Afp::class, 'id', 'afp_id');
    }

    public function residence_country()
    {
        return $this->hasOne(CountryLDAP::class, 'id','residence_country_id');
    }

    public function residence_state()
    {
        return $this->hasOne(StateLDAP::class, 'id','residence_state_id');
    }

    public function residence_city()
    {
        return $this->hasOne(CityLDAP::class, 'id','residence_city_id');
    }

    public function locality()
    {
        return $this->hasOne(Location::class, 'Id_Localidad', 'locality_id');
    }

    public function upz()
    {
        return $this->hasOne(Upz::class, 'Id_Upz','upz_id');
    }

    public function neighborhood_name()
    {
        return $this->hasOne(Neighborhood::class, 'IdBarrio','neighborhood_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
