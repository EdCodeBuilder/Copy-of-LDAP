<?php

namespace App\Modules\Passport\src\Models;

use App\Models\Security\CityLDAP;
use App\Models\Security\CountryLDAP;
use App\Models\Security\DocumentType;
use App\Models\Security\StateLDAP;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Arr;

class User extends Model
{

    const COLOMBIA_LDAP = 47;
    const COLOMBIA = 41;
    const BOGOTA_DC_LDAP = 779;
    const BOGOTA_DC = 33;
    const BOGOTA_LDAP = 12688;
    const BOGOTA = 111;
    const ETHNICITY = 4;

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'mysql_sim';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'persona';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'Id_Persona';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Cedula',
        'Id_TipoDocumento',
        'Primer_Apellido',
        'Segundo_Apellido',
        'Primer_Nombre',
        'Segundo_Nombre',
        'Fecha_Nacimiento',
        'Id_Pais',
        'Id_Departamento',
        'i_fk_id_ciudad',
        'Nombre_Ciudad',
        'Id_Genero',
        'Id_Etnia',
        'Id_Identidad_Genero',
        'Estado',
        'Vinculación',
        'FinalContrato',
        'TimeStamp',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @param array $request
     * @return array
     */
    public function transformRequest(array $request)
    {
        $country = CountryLDAP::find( Arr::get($request, 'country_id', User::COLOMBIA_LDAP) );
        $state = StateLDAP::find( Arr::get($request, 'state_id', User::BOGOTA_DC_LDAP) );
        $city = CityLDAP::find( Arr::get($request, 'city_id', User::BOGOTA_LDAP) );

        Arr::set(
            $request,
            'country_id',
            isset($country->sim_country_id) ? (int) $country->sim_country_id : User::COLOMBIA
        );
        Arr::set(
            $request,
            'state_id',
            isset($state->sim_state_id) ? (int) $state->sim_state_id : User::BOGOTA_DC
        );
        Arr::set(
            $request,
            'city_id',
            isset($city->sim_city_id) ? (int) $city->sim_city_id : User::BOGOTA
        );
        Arr::set(
            $request,
            'city',
            isset($city->name) ? $city->name : 'BOGOTÁ'
        );

        return [
            'Cedula'                => Arr::get($request, 'document', null),
            'Id_TipoDocumento'      => Arr::get($request, 'document_type_id', null),
            'Primer_Apellido'       => toUpper( Arr::get($request, 'first_last_name', null) ),
            'Segundo_Apellido'      => toUpper( Arr::get($request, 'second_last_name', null) ),
            'Primer_Nombre'         => toUpper( Arr::get($request, 'first_name', null) ),
            'Segundo_Nombre'        => toUpper( Arr::get($request, 'middle_name', null) ),
            'Fecha_Nacimiento'      => Arr::get($request, 'birthdate', null),
            'Id_Pais'               => Arr::get($request, 'country_id', User::COLOMBIA),
            'Id_Departamento'       => Arr::get($request, 'state_id', User::BOGOTA_DC),
            'i_fk_id_ciudad'        => Arr::get($request, 'city_id', User::BOGOTA),
            'Nombre_Ciudad'         => toUpper( Arr::get($request, 'city', 'BOGOTÁ') ),
            'Id_Genero'             => Arr::get($request, 'sex_id', null),
            'Id_Etnia'              =>  User::ETHNICITY, //OTRO
            'Id_Identidad_Genero'   => Arr::get($request, 'sex_id', null),
            'Estado'        =>  0,
            'Vinculación'   =>  'otro', // From database enum type ['Contratista', 'Planta', 'otro']
            'FinalContrato' =>  null,
            'TimeStamp'     =>  now(),
        ];
    }

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */

    public function getFullNameAttribute()
    {
        $n1 = toUpper($this->Primer_Nombre);
        $n2 = toUpper($this->Segundo_Nombre);
        $n3 = toUpper($this->Primer_Apellido);
        $n4 = toUpper($this->Segundo_Apellido);
        $name = $n1.' '.$n3;
        if (strlen($n2) > 0) {
            $name = $n1.' '.$n2." ".$n3;
        }
        if (strlen($n4) > 0) {
            $name = $n1.' '.$n2." ".$n3.' '.$n4;
        }
        return $name;
    }

    public function getCardNameAttribute()
    {
        $n1 = toUpper($this->Primer_Nombre);
        $n2 = toUpper($this->Segundo_Nombre);
        $n3 = toUpper($this->Primer_Apellido);
        $n4 = toUpper($this->Segundo_Apellido);
        $name = $n1.' '.$n3;
        if (strlen($n2) > 0) {
            $name = $n1.' '.substr($n2, 0, 1).". ".$n3;
        }
        if (strlen($n4) > 0) {
            $name = $n1.' '.substr($n2, 0, 1)." ".$n3.' '.substr($n4, 0, 1).'.';
        }
        return $name;
    }

    public function getDocumentAttribute()
    {
        return (int) $this->Cedula;
    }

    /*
     * ---------------------------------------------------------
     * Eloquent Relationships
     * ---------------------------------------------------------
     */

    public function document_type()
    {
        return $this->belongsTo(DocumentType::class, 'Id_TipoDocumento');
    }

    /**
     * @return HasOne
     */
    public function passport()
    {
        return $this->hasOnePassport(Passport::class, 'i_fk_id_usuario', 'Id_Persona');
    }

    /**
     * @return HasOne
     */
    public function passport_old()
    {
        return $this->hasOne(PassportOldView::class, 'user_id', 'Id_Persona');
    }

    /**
     * @return BelongsToMany
     */
    public function type()
    {
        return $this->belongsToMany(Type::class, 'persona_tipo', 'Id_Persona', 'Id_Tipo');
    }

    /**
     * @return BelongsToMany
     */
    public function social_population()
    {
        return $this->belongsToMany(SocialPopulation::class, 'personas_grupos_sociales_poblacionales', 'Id_Persona', 'id');
    }

    /**
     * Define a one-to-one relationship.
     *
     * @param  string  $related
     * @param  string  $foreignKey
     * @param  string  $localKey
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function hasOnePassport($related, $foreignKey = null, $localKey = null)
    {
        $instance = $this->newRelatedInstance($related);

        $foreignKey = $foreignKey ?: $this->getForeignKey();

        $localKey = $localKey ?: $this->getKeyName();

        return $this->newHasOne($instance->newQuery(), $this, env('DB_PASSPORT_DATABASE', 'forge').'.'.$instance->getTable().'.'.$foreignKey, $localKey);
    }
}
