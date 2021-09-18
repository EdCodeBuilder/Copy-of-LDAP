<?php

namespace App\Modules\Parks\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Story extends Model implements Auditable
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
    protected $table = 'subtitulo';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'IdSubtitulo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['idParque', 'Subtitulo', 'Parrafo'];

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */
    public function setSubtituloAttribute($value)
    {
        $this->attributes['Subtitulo'] = toUpper($value);
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
        'idParque',
        'Subtitulo',
        'Parrafo',
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags(): array
    {
        return ['park_story'];
    }

    /*
    * ---------------------------------------------------------
    * Eloquent Relations
    * ---------------------------------------------------------
    */

    /**
     * A story belongs to park
     *
     * @return BelongsTo
     */
    public function parks()
    {
        return $this->belongsTo(Park::class, 'idParque', 'Id');
    }
}
