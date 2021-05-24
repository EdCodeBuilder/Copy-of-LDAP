<?php

namespace App\Modules\Contractors\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class Career extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'mysql_contractors';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'careers';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'name', 'academic_level_id' ];

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
        'academic_level_id'
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags(): array
    {
        return ['contractors_careers'];
    }

    /*
     * ---------------------------------------------------------
     * Eloquent Relations
     * ---------------------------------------------------------
    */

    /**
     * @return BelongsTo
     */
    public function level()
    {
        return $this->belongsTo(AcademicLevel::class, 'academic_level_id', 'id');
    }
}
