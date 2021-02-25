<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class Module extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = "mysql_ldap";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'area',
        'redirect',
        'image',
        'status',
        'missionary',
        'compatible',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status'     => 'bool',
        'missionary' => 'bool',
        'compatible' => 'bool',
    ];

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */

    /**
     * Set the module's name in uppercase.
     *
     * @return string
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = toUpper($value);
    }

    /**
     * Set the module's area in uppercase.
     *
     * @return string
     */
    public function setAreaAttribute($value)
    {
        $this->attributes['area'] = toUpper($value);
    }

    /*
    * ---------------------------------------------------------
    * Query Scopes
    * ---------------------------------------------------------
    */

    /**
     * Check if user is active
     *
     * @param $query
     * @return Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

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
        'name',
        'redirect',
        'image',
        'status',
        'missionary',
        'compatible',
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags() : array
    {
        return ['module'];
    }

    /*
    * ---------------------------------------------------------
    * Eloquent
    * ---------------------------------------------------------
    */

    public function incompatible_access()
    {
        return $this->hasMany( IncompatibleAccess::class, 'Id_Modulo', 'id' );
    }
}
