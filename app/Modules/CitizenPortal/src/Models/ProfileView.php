<?php

namespace App\Modules\CitizenPortal\src\Models;

use App\Models\Security\DocumentType;
use App\Models\Security\User;
use App\Traits\FullTextSearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class ProfileView extends Model
{
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
    protected $table = 'profiles_view_upd';

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
        'profile_type',
        'document_type_id',
        'document_type',
        'document',
        'name',
        's_name',
        'surname',
        's_surname',
        'email',
        'sex_id',
        'sex',
        'blood_type_id',
        'blood_type',
        'birthdate',
        'country_birth_id',
        'country_birth',
        'state_birth_id',
        'state_birth',
        'city_birth_id',
        'city_birth',
        'country_residence_id',
        'country_residence',
        'state_residence_id',
        'state_residence',
        'city_residence_id',
        'city_residence',
        'locality_id',
        'locality',
        'upz_id',
        'upz',
        'neighborhood_id',
        'neighborhood',
        'other_neighborhood_name',
        'address',
        'stratum',
        'ethnic_group_id',
        'ethnic_group',
        'population_group_id',
        'population_group',
        'gender_id',
        'gender',
        'sexual_orientation_id',
        'sexual_orientation',
        'eps_id',
        'eps',
        'has_disability',
        'disability_id',
        'disability',
        'contact_name',
        'contact_phone',
        'contact_relationship',
        'verified_at',
        'assigned_by_id',
        'assignor_name',
        'assignor_surname',
        'assignor_document',
        'assigned_at',
        'checker_id',
        'checker_name',
        'checker_surname',
        'checker_document',
        'status_id',
        'status',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [ 'verified_at', 'assigned_at', 'birthdate' ];

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
        return toUpper( "{$this->name} {$this->s_name} {$this->surname} {$this->s_surname}" );
    }


    /**
     * @return mixed|string|null
     */
    public function getFullNameAssignorAttribute()
    {
        return toUpper( "{$this->assignor_name} {$this->assignor_surname}" );
    }

    /**
     * @return mixed|string|null
     */
    public function getFullNameVerifierAttribute()
    {
        return toUpper( "{$this->checker_name} {$this->checker_surname}" );
    }

    /**
     * @return string|null
     */
    public function getContactRelationshipNameAttribute()
    {
        $data = collect( Profile::RELATIONSHIP )->firstWhere('id', '=', $this->contact_relationship);
        return isset($data->name) ? (string) $data->name : null;
    }

    /**
     * @param $column
     * @return string
     */
    public function getSortableColumn($column)
    {
        switch ($column) {
            case 'id':
            case 'created_at':
            case 'updated_at':
                return $column;
            default:
                return in_array($column, $this->fillable)
                    ? $column
                    : $this->primaryKey;
        }
    }

    /*
     * ---------------------------------------------------------
     * Eloquent Relationships
     * ---------------------------------------------------------
     */

    /**
     * @return HasMany
     */
    public function observations()
    {
        return $this->hasMany(Observation::class, 'profile_id', 'id')->latest();
    }

    /**
     * @return HasMany
     */
    public function files()
    {
        return $this->hasMany(File::class, 'profile_id', 'id')->latest();
    }

    /**
     * @return HasMany
     */
    public function user_schedules()
    {
        return $this->hasMany(CitizenSchedule::class, 'user_schedule_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(Citizen::class, 'user_id', 'id');
    }
}
