<?php

namespace App\Modules\Passport\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use OwenIt\Auditing\Contracts\Auditable;

class Card extends Model implements Auditable
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
    protected $table = 'tbl_cards';

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
        'title',
        'description',
        'btn_text',
        'lottie',
        'flex',
        'src',
        'to',
        'href',
        'dashboard_id',
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
        'title',
        'description',
        'btn_text',
        'lottie',
        'flex',
        'src',
        'to',
        'href',
        'dashboard_id',
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags(): array
    {
        return ['vital_passport_card'];
    }

    /*
     * ---------------------------------------------------------
     * Accessors and Mutators
     * ---------------------------------------------------------
     */

    /**
     * @param $value
     * @return null|string
     */
    public function getSrcAttribute($value)
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        if ( !is_null($value) && Storage::disk('public')->exists("passport-services/$value") ) {
            return Storage::disk('public')->url("passport-services/$value");
        }
        return null;
    }

    /**
     * @param $value
     * @return null|string
     */
    public function getLottieAttribute($value)
    {
        if ( !is_null($value) && Storage::disk('public')->exists("lottie/$value") ) {
            return json_decode( file_get_contents( storage_path("app/public/lottie/$value") ) );
        }
        if ( !is_null($value) && !Storage::disk('public')->exists("lottie/$value") ) {
            return json_decode( file_get_contents( storage_path("app/public/lottie/404.json") ) );
        }
        return null;
    }

    /*
     * ---------------------------------------------------------
     * Eloquent Relations
     * ---------------------------------------------------------
     */

    public function dashboard()
    {
        return $this->belongsTo(Dashboard::class);
    }
}
