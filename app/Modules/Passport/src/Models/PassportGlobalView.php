<?php

namespace App\Modules\Passport\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

class PassportGlobalView extends Model
{

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'mysql_passport';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'global_passports_view';

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
        'user_cade',
        'created_at'
    ];

    protected $dates = ['birthday'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['user_cade_id', 'user_cade_name'];

    /*
     * ---------------------------------------------------------
     * Eloquent Relationships
     * ---------------------------------------------------------
     */
    public function cade()
    {
        return $this->hasOne(User::class, 'Cedula', 'user_cade');
    }

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */

    /**
     * @return string|null
     */
    public function getUserCadeNameAttribute()
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
}
