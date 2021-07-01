<?php

namespace App\Modules\Passport\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use OwenIt\Auditing\Contracts\Auditable;

class Image extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'mysql_passport';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_images';

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
    protected $fillable = ['name', 'agreement_id'];

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
        'name', 'agreement_id'
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags(): array
    {
        return ['vital_passport_image'];
    }

    /*
     * ---------------------------------------------------------
     * Accessors and Mutators
     * ---------------------------------------------------------
     */

    /**
     * @return null|string
     */
    public function getUrlAttribute()
    {
        if ( Storage::disk('public')->exists("passport-services/{$this->name}") ) {
            return Storage::disk('public')->url("passport-services/{$this->name}");
        }
        return null;
    }

    /*
     * ---------------------------------------------------------
     * Eloquent Relations
     * ---------------------------------------------------------
     */

    public function agreements()
    {
        return $this->belongsTo(Agreements::class, 'agreement_id', 'id');
    }
}
