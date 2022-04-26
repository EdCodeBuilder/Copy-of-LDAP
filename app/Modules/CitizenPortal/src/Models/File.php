<?php

namespace App\Modules\CitizenPortal\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Contracts\Auditable;

class File extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = "mysql_citizen_portal";

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "files";

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = "id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file',
        'file_type_id',
        'status_id',
        'profile_id',
        'citizen_schedule_id',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['verified_at'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [ 'verified_at' ];

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
        'file',
        'file_type_id',
        'status_id',
        'profile_id',
        'citizen_schedule_id',
        'verified_at',
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags(): array
    {
        return ['citizen_portal_file'];
    }

    /*
   * ---------------------------------------------------------
   * Eloquent Relationships
   * ---------------------------------------------------------
   */

    /**
     * @return BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo(Profile::class, 'profile_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function status()
    {
        return $this->hasOne( Status::class, 'id', 'status_id' );
    }

    /**
     * @return BelongsTo
     */
    public function file_type()
    {
        return $this->belongsTo(FileType::class, 'file_type_id', 'id');
    }
}
