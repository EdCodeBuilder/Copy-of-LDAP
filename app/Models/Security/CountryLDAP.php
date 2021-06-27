<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Model;

class CountryLDAP extends Model
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
    protected $table = 'countries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['code', 'name', 'phone_code', 'sim_country_id'];

    public function states()
    {
        return $this->hasMany( StateLDAP::class, 'country_id');
    }
}
