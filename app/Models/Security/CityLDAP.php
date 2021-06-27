<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Model;

class CityLDAP extends Model
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
    protected $table = 'cities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'state_id', 'sim_city_id'];

    public function state()
    {
        return $this->belongsTo( StateLDAP::class, 'state_id');
    }
}
