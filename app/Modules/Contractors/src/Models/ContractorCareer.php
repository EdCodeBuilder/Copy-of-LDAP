<?php

namespace App\Modules\Contractors\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class ContractorCareer extends Model implements Auditable
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
    protected $table = 'contractor_careers';

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
    protected $fillable = [ 'contractor_id', 'career_id', 'graduate', 'year_approved' ];

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
        'contractor_id', 'career_id', 'graduated', 'year_approved'
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags(): array
    {
        return ['contractors_careers_relation'];
    }

    /*
     * ---------------------------------------------------------
     * Eloquent Relations
     * ---------------------------------------------------------
    */

    /**
     * @return BelongsTo
     */
    public function career()
    {
        return $this->belongsTo(Career::class);
    }

    /**
     * @return BelongsTo
     */
    public function contractor()
    {
        return $this->belongsTo(Contractor::class);
    }
}
