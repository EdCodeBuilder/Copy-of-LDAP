<?php

namespace App\Modules\Parks\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Upz extends Model implements Auditable
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
    protected $table = 'upz';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'Id_Upz';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['Upz', 'cod_upz', 'IdLocalidad', 'Tipo'];

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
        'Upz', 'cod_upz', 'IdLocalidad', 'Tipo',
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags(): array
    {
        return ['park_upz'];
    }

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */

    /**
     * Get name in uppercase
     *
     * @return int
     */
    public function getIdAttribute()
    {
        return (int) $this->Id_Upz;
    }

    /**
     * Get name in uppercase
     *
     * @return string
     */
    public function getCodeAttribute()
    {
        return $this->cod_upz;
    }

    /**
     * Get name in uppercase
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return toUpper($this->Upz);
    }

   /*
   * ---------------------------------------------------------
   * Eloquent Relations
   * ---------------------------------------------------------
   */

    /**
     * Upz has many neighborhoods
     *
     * @return HasMany
     */
    public function neighborhoods()
    {
        return $this->hasMany(Neighborhood::class, 'CodUpz','cod_upz');
    }

    /**
     * A Neighborhood Belongs To UPZ
     *
     * @return BelongsTo
     */
    public function locality()
    {
        return $this->belongsTo(Location::class, 'IdLocalidad', 'Id_Localidad');
    }

    /**
     * A Neighborhood Belongs To UPZ
     *
     * @return BelongsTo
     */
    public function upz_type()
    {
        return $this->belongsTo(UpzType::class, 'Tipo', 'Id');
    }
}
