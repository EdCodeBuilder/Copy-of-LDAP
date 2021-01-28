<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class Area extends Model
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
    protected $table = 'areas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'subdirectorate_id',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = toUpper($value);
    }

    public function subdirectorates()
    {
        return $this->belongsTo(Subdirectorate::class);
    }
}
