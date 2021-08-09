<?php

namespace App\Modules\Passport\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class SuperCade extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, SoftDeletes;
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
    protected $table = 'tbl_supercades';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'i_pk_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['vc_nombre', 'i_estado'];

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
        'vc_nombre', 'i_estado'
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags(): array
    {
        return ['vital_passport_cades'];
    }

    /*
     * ---------------------------------------------------------
     * Query Scopes
     * ---------------------------------------------------------
     */

    public function scopeActive($query)
    {
        return $query->where('i_estado', true);
    }

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */

    /**
     * Get the id
     *
     * @return int
     */
    public function getIdAttribute()
    {
        return isset($this->i_pk_id) ? (int) $this->i_pk_id : null;
    }

    /**
     * Get the eps's name.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return toUpper( "{$this->vc_nombre}" );
    }

    /**
     * Get the eps's status.
     *
     * @return bool
     */
    public function getStatusAttribute()
    {
        return isset($this->i_estado) ? (bool) $this->i_estado : null;
    }

    /*
     * ---------------------------------------------------------
     * Eloquent Relationships
     * ---------------------------------------------------------
     */

    /**
     * @return HasMany
     */
    public function passports()
    {
        return $this->hasMany(Passport::class, 'i_fk_id_superCade', 'i_pk_id');
    }
}
