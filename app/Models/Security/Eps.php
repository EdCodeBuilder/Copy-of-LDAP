<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class Eps extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = "mysql_ldap";

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'eps';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'active',
        'nit',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
