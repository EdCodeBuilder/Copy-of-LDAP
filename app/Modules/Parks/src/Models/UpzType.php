<?php

namespace App\Modules\Parks\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class UpzType extends Model implements Auditable
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
    protected $table = 'TipoUPZ';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'Id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['Tipo'];

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
        'Tipo',
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags(): array
    {
        return ['park_upz_type'];
    }

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */

    /**
     * Get name in uppercase
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return toUpper($this->Tipo);
    }

   /*
   * ---------------------------------------------------------
   * Eloquent Relations
   * ---------------------------------------------------------
   */

    /**
     * A Neighborhood Belongs To UPZ
     *
     * @return HasMany
     */
    public function upz()
    {
        return $this->hasMany(Upz::class, 'Tipo', 'Id');
    }
}
