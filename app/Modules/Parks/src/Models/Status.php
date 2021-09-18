<?php

namespace App\Modules\Parks\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Status extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, SoftDeletes;
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mysql_parks';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'estado';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'Id_Estado';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['Estado'];

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
        'Estado',
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags(): array
    {
        return ['park_status'];
    }

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */

    /**
     * Get id
     *
     * @return int
     */
    public function getIdAttribute()
    {
        return (int) $this->Id_Estado;
    }

    /**
     * Get name in uppercase
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->Estado;
    }

    /**
     * Get name in uppercase
     *
     * @return string
     */
    public function setEstadoAttribute($value)
    {
        $this->attributes['Estado'] = toUpper($value);
    }
}
