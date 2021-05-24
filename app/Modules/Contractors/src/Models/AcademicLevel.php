<?php

namespace App\Modules\Contractors\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class AcademicLevel extends Model implements Auditable
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
    protected $table = 'academic_level';

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
    protected $fillable = [ 'name' ];

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
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags(): array
    {
        return ['contractors_academic_level'];
    }

    /*
     * ---------------------------------------------------------
     * Eloquent Relations
     * ---------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function careers()
    {
        // https://cloud.google.com/maps-platform/pricing/sheet/
        // https://codelabs.developers.google.com/?cat=mapsplatform
        // https://developers.google.com/maps/api-key-best-practices
        // https://developers.google.com/maps/documentation/api-picker
        return $this->hasMany(Career::class);
    }
}
