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
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
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
        'birthdate_country_id',
        'birthdate_state_id',
        'birthdate_city_id',
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
        'rut',
        'bank',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [ 'third_party' ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'document_type_id'  => 'int',
        'document'  => 'int',
        'sex_id'    => 'int',
        'eps_id'    => 'int',
        'afp_id'    => 'int',
        'birthdate_country_id'  => 'int',
        'birthdate_state_id'    => 'int',
        'birthdate_city_id' => 'int',
        'residence_country_id'  => 'int',
        'residence_state_id'    => 'int',
        'residence_city_id' => 'int',
        'locality_id'       => 'int',
        'upz_id'            => 'int',
        'neighborhood_id'   => 'int',
        'third_party'   => 'bool',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [ 'birthdate', 'modifiable' ];

    /*
    * ---------------------------------------------------------
    * Data Change Auditor
    * ---------------------------------------------------------
    */

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'document_type_id',
        'document',
        'name',
        'surname',
        'birthdate',
        'birthdate_country_id',
        'birthdate_state_id',
        'birthdate_city_id',
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
        'rut',
        'bank',
        'third_party',
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags(): array
    {
        return ['contractors'];
    }

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

    /**
     * Get file url
     *
     * @param $value
     * @return string|null
     */
    public function getRutAttribute($value)
    {
        return Storage::disk('contractor')->exists($value)
            ? route('file.contractors.rut', ['contractor' => $this->id, 'name' => $value])
            : null;
    }

    /**
     * Get value in uppercase
     *
     * @param $value
     * @return string|null
     */
    public function getBankAttribute($value)
    {
        return Storage::disk('contractor')->exists($value)
            ? route('file.contractors.bank', ['contractor' => $this->id, 'name' => $value])
            : null;
    }

    /**
     * Full name
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "$this->name $this->surname";
    }

    /**
     * Encrypted url
     *
     * @return string|null
     */
    public function getModifiableLinkAttribute()
    {
        if (isset($this->document)) {
            $document = Crypt::encrypt($this->document);
            $path = env('APP_ENV') == 'local'
                ? env('APP_PATH_DEV')
                : env('APP_PATH_PROD');
            return "https://sim.idrd.gov.co/{$path}/es/contracts?payload=$document";
        }
        return null;
    }

    /**
     * WhatsApp Link
     *
     * @return string|null
     */
    public function getWhatsappLinkAttribute()
    {
        if (isset($this->phone) && strlen($this->phone) > 9 && isset($this->modifiable_link)) {
            $base = "https://api.whatsapp.com/send?";
            $phone = "phone=57{$this->phone}";
            $text = "&text=%F0%9F%91%8B%20%20Hola%2C%20hemos%20generado%20el%20siguiente%20enlace%20desde%20el%20Portal%20Contratista%20para%20que%20completes%20tu%20informaci%C3%B3n%20personal.%20C%C3%B3pialo%20y%20p%C3%A9galo%20en%20el%20navegador%20de%20tu%20computador.%20Gracias.%0A%0A{$this->modifiable_link}";
            return "{$base}{$phone}{$text}";
        }
        return null;
    }

    /*
     * ---------------------------------------------------------
     * Eloquent Relations
     * ---------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function contracts()
    {
        return $this->hasMany(Contract::class)->latest();
    }

    /**
     * @return HasMany
     */
    public function careers()
    {
        return $this->hasMany(ContractorCareer::class)->latest();
    }

    /**
     * @return BelongsTo
     */
    public function document_type()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }

    /**
     * @return HasOne
     */
    public function eps_name()
    {
        return $this->hasOne(Eps::class, 'id', 'eps_id');
    }

    /**
     * @return HasOne
     */
    public function sex()
    {
        return $this->hasOne(Sex::class, 'Id_Genero', 'sex_id');
    }

    /**
     * @return HasOne
     */
    public function afp_name()
    {
        return $this->hasOne(Afp::class, 'id', 'afp_id');
    }

    /**
     * @return HasOne
     */
    public function residence_country()
    {
        return $this->hasOne(CountryLDAP::class, 'id','residence_country_id');
    }

    /**
     * @return HasOne
     */
    public function residence_state()
    {
        return $this->hasOne(StateLDAP::class, 'id','residence_state_id');
    }

    /**
     * @return HasOne
     */
    public function residence_city()
    {
        return $this->hasOne(CityLDAP::class, 'id','residence_city_id');
    }

    /**
     * @return HasOne
     */
    public function birthdate_country()
    {
        return $this->hasOne(CountryLDAP::class, 'id','birthdate_country_id');
    }

    /**
     * @return HasOne
     */
    public function birthdate_state()
    {
        return $this->hasOne(StateLDAP::class, 'id','birthdate_state_id');
    }

    /**
     * @return HasOne
     */
    public function birthdate_city()
    {
        return $this->hasOne(CityLDAP::class, 'id','birthdate_city_id');
    }

    /**
     * @return HasOne
     */
    public function locality()
    {
        return $this->hasOne(Location::class, 'Id_Localidad', 'locality_id');
    }

    /**
     * @return HasOne
     */
    public function upz()
    {
        return $this->hasOne(Upz::class, 'Id_Upz','upz_id');
    }

    /**
     * @return HasOne
     */
    public function neighborhood_name()
    {
        return $this->hasOne(Neighborhood::class, 'IdBarrio','neighborhood_id');
    }

    /**
     * @return HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
