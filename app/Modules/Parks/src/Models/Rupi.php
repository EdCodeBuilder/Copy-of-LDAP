<?php

namespace App\Modules\Parks\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use OwenIt\Auditing\Contracts\Auditable;

class Rupi extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

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
    protected $table = 'ParqueRupi';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id_Rupi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['Id_Parque', 'Rupi'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

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
        'idParque',
        'Rupi',
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags() : array
    {
        return ['park_rupi'];
    }

    /*
    * ---------------------------------------------------------
    * Eloquent Relations
    * ---------------------------------------------------------
    */

    /**
     * A rupi belongs to park
     *
     * @return BelongsTo
     */
    public function park(): BelongsTo
    {
        return $this->belongsTo(Park::class, 'idParque', 'Id');
    }
}
