<?php

namespace App\Modules\CitizenPortal\src\Models;

use App\Models\Security\DocumentType;
use App\Models\Security\User;
use App\Traits\FullTextSearch;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Profile extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, FullTextSearch;

    // Relationship
    const RELATIONSHIP = [
        [
            'id'    =>  3,
            'name'  =>  'PADRE - MADRE',
        ],
        [
            'id'    =>  7,
            'name'  =>  'TUTOR LEGAL',
        ],
    ];

    const PROFILE_PERSONAL = 1; // Id de la tabla profile_types
    const PROFILE_BENEFICIARY = 2; // Id de la tabla profile_types

    // Ids de la tabla status "estados"

    // Validate Profile
    const PENDING = 1;
    const VALIDATING = 2;
    const VERIFIED = 3;
    const RETURNED = 4;

    // Validate Subscription
    const PENDING_SUBSCRIBE = 5;
    const SUBSCRIBED = 6;
    const UNSUBSCRIBED = 7;

    // Document
    const FILE_PENDING = 8;
    const FILE_VERIFIED = 9;
    const FILE_RETURNED = 10;

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'mysql_citizen_portal';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'profiles';

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
        'user_id',
        'profile_type_id',
        'document_type_id',
        'document',
        'name',
        'surname',
        'sex',
        'blood_type',
        'birthdate',
        'country_birth_id',
        'state_birth_id',
        'city_birth_id',
        'country_residence_id',
        'state_residence_id',
        'city_residence_id',
        'locality_id',
        'upz_id',
        'neighborhood_id',
        'other_neighborhood_name',
        'address',
        'stratum',
        'ethnic_group_id',
        'population_group_id',
        'gender_id',
        'sexual_orientation_id',
        'has_disability',
        'disability_id',
        'contact_name',
        'contact_phone',
        'contact_relationship',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [ 'verified_at', 'assigned_by_id', 'assigned_at', 'checker_id', 'status_id' ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [ 'verified_at', 'assigned_at', 'birthdate' ];

    /**
     * The columns of the full text index
     *
     * @var array
     */
    protected $searchable = [
        'document',
        'name',
        'surname',
    ];

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
        'user_id',
        'profile_type_id',
        'document_type_id',
        'document',
        'name',
        'surname',
        'sex',
        'blood_type',
        'birthdate',
        'country_birth_id',
        'state_birth_id',
        'city_birth_id',
        'country_residence_id',
        'state_residence_id',
        'city_residence_id',
        'locality_id',
        'upz_id',
        'neighborhood_id',
        'other_neighborhood_name',
        'address',
        'stratum',
        'ethnic_group_id',
        'population_group_id',
        'gender_id',
        'sexual_orientation_id',
        'has_disability',
        'disability_id',
        'contact_name',
        'contact_phone',
        'contact_relationship',
        'verified_at',
        'assigned_by_id',
        'assigned_at',
        'checker_id',
        'status_id'
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags(): array
    {
        return ['citizen_portal_profiles'];
    }

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */

    /**
     * @return mixed|string|null
     */
    public function getFullNameAttribute()
    {
        return toUpper( "{$this->name} {$this->surname}" );
    }

    /**
     * @return string|null
     */
    public function getContactRelationshipNameAttribute()
    {
        $data = collect( Profile::RELATIONSHIP )->firstWhere('id', '=', $this->contact_relationship);
        return isset($data->name) ? (string) $data->name : null;
    }

    public function observations()
    {
        return $this->hasMany(Observation::class, 'profile_id', 'id')->latest();
    }

    public function files()
    {
        return $this->hasMany(File::class, 'profile_id', 'id')->latest();
    }

    public function user_schedules()
    {
        return $this->hasMany(CitizenSchedule::class, 'user_schedule_id', 'id');
    }

    public function assigned()
    {
        return $this->hasOne( User::class, 'id', 'assigned_by_id' );
    }

    public function viewer()
    {
        return $this->hasOne( User::class, 'id', 'checker_id' );
    }

    public function status()
    {
        return $this->hasOne( Status::class, 'id', 'status_id' );
    }

    public function user()
    {
        return $this->belongsTo(Citizen::class, 'user_id', 'id');
    }

    public function profile_type()
    {
        return $this->belongsTo(ProfileType::class, 'profile_type_id', 'id');
    }

    public function document_type()
    {
        return $this->hasOne(DocumentType::class, 'Id_TipoDocumento', 'document_type_id');
    }
}
