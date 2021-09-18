<?php

namespace App\Modules\Parks\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Scale extends Model implements Auditable
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
    protected $table = 'tipo';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'Id_Tipo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['Tipo', 'Descripcion'];

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
        'Tipo', 'Descripcion'
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags(): array
    {
        return ['park_scale'];
    }

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */

    /**
     * Get value in uppercase
     *
     * @return int
     */
    public function getIdAttribute()
    {
        return (int) $this->Id_Tipo;
    }

    /**
     * Get value in uppercase
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->Tipo;
    }

    /**
     * Get value in uppercase
     *
     * @return string
     */
    public function getDescriptionAttribute()
    {
        return $this->Descripcion;
    }

    /*
    * ---------------------------------------------------------
    * Eloquent Relations
    * ---------------------------------------------------------
    */

    /**
     * An scale has many parks
     *
     * @return HasMany
     */
    public function parks()
    {
        return $this->hasMany(Park::class, 'Id_Tipo', 'Id_Tipo');
    }
}
