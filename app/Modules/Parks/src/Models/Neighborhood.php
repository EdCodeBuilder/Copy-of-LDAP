<?php

namespace App\Modules\Parks\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Neighborhood extends Model implements Auditable
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
    protected $table = 'Barrios';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'IdBarrio';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['Barrio', 'CodUpz', 'CodBarrio'];

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
        'Barrio', 'CodUpz', 'CodBarrio'
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags(): array
    {
        return ['park_neighborhood'];
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
        return toUpper($this->Barrio);
    }

    /**
     * Set name in uppercase
     *
     * @param $value
     * @return void
     */
    public function setBarrioAttribute($value)
    {
        $this->attributes['Barrio'] = toUpper($value);
    }

    /*
   * ---------------------------------------------------------
   * Eloquent Relations
   * ---------------------------------------------------------
   */

    /**
     * A Neighborhood Belongs To UPZ
     *
     * @return BelongsTo
     */
    public function upz()
    {
        return $this->belongsTo(Upz::class, 'CodUpz', 'cod_upz');
    }
}
