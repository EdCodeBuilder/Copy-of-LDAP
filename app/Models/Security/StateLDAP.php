<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Model;

class StateLDAP extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'mysql_ldap';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'states';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'country_id', 'sim_state_id'];

    public function cities()
    {
        return $this->hasMany( CityLDAP::class, 'state_id');
    }

    public function country()
    {
        return $this->belongsTo( CountryLDAP::class, 'country_id');
    }
}
