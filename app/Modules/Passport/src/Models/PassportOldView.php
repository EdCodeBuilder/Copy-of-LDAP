<?php

namespace App\Modules\Passport\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

class PassportOldView extends Model
{

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'mysql_passport_old';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'passport_old_query_view';

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
        'user',
        'user_id',
        'full_name',
        'first_name',
        'middle_name',
        'first_last_name',
        'second_last_name',
        'card_name',
        'document',
        'document_type',
        'document_type_name',
        'birthday',
        'gender',
        'gender_name',
        'country',
        'country_name',
        'state_id',
        'state',
        'city',
        'city_id',
        'city_name',
        'location',
        'location_name',
        'address',
        'stratum',
        'mobile',
        'phone',
        'email',
        'retired',
        'hobbies',
        'hobbies_name',
        'eps',
        'eps_name',
        'supercade',
        'supercade_name',
        'observations',
        'question_1',
        'question_2',
        'question_3',
        'question_4',
        'downloads',
        'created_at',
        'ethnicity_id',
        'ethnicity',
        'gender_identity_id',
        'gender_identity',
        'user_created_at',
        'status_passport',
        'printed',
        'user_cade',
        'supercade_user_document',
        'renew',
        'supercade_passport',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['birthday', 'created_at'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['user_cade_id', 'user_cade_fullname', 'user_cade_document'];

    /*
     * ---------------------------------------------------------
     * Eloquent Relationships
     * ---------------------------------------------------------
     */

    public function cade()
    {
        return $this->hasOne(User::class, 'Cedula', 'user_cade');
    }

    public function renewals()
    {
        return $this->hasManyRenewals(RenewalView::class, 'passport_id', 'id');
    }

    /**
     * Define a one-to-many relationship.
     *
     * @param  string  $related
     * @param  string  $foreignKey
     * @param  string  $localKey
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hasManyRenewals($related, $foreignKey = null, $localKey = null)
    {
        $instance = $this->newRelatedInstance($related);

        $foreignKey = $foreignKey ?: $this->getForeignKey();

        $localKey = $localKey ?: $this->getKeyName();

        return $this->newHasMany(
            $instance->newQuery(), $this, env('DB_PASSPORT_DATABASE', 'forge').'.'.$instance->getTable().'.'.$foreignKey, $localKey
        );
    }

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */

    /**
     * @return string|null
     */
    public function getUserCadeFullnameAttribute()
    {
        return isset( $this->cade->full_name ) ? (string) $this->cade->full_name : null;
    }

    /**
     * @return int|null
     */
    public function getUserCadeIdAttribute()
    {
        return isset( $this->cade->Id_Persona ) ? (int) $this->cade->Id_Persona : null;
    }

    /**
     * @return int|null
     */
    public function getUserCadeDocumentAttribute()
    {
        return isset( $this->supercade_user_document ) ? (int) $this->supercade_user_document : null;
    }
}
