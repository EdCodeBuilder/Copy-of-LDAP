<?php

namespace App\Modules\Passport\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use OwenIt\Auditing\Contracts\Auditable;

class Dashboard extends Model implements Auditable
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
    protected $table = 'tbl_dashboard';

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
    protected $fillable = [
        'background',
        'title',
        'icon',
        'text',
        'banner',
    ];

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
        'background',
        'title',
        'icon',
        'text',
        'banner',
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags(): array
    {
        return ['vital_passport_dashboard'];
    }

    /*
     * ---------------------------------------------------------
     * Accessors and Mutators
     * ---------------------------------------------------------
     */

    public function getBackgroundAttribute($value)
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        if ( Storage::disk('public')->exists("passport-services/$value") ) {
            return Storage::disk('public')->url("passport-services/$value");
        }
        return 'https://images.unsplash.com/photo-1568480289356-5a75d0fd47fc?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80';
    }

    /*
     * ---------------------------------------------------------
     * Eloquent Relations
     * ---------------------------------------------------------
     */

    public function cards()
    {
        return $this->hasMany(Card::class);
    }
}
