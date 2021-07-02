<?php

namespace App\Modules\Passport\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use OwenIt\Auditing\Contracts\Auditable;

class PassportConfig extends Model implements Auditable
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
    protected $table = 'tbl_card_config';

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
        'file',
        'dark',
        'template',
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
        'file',
        'dark',
        'template',
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags(): array
    {
        return ['vital_passport_card_config'];
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
    public function getFileAttribute($value)
    {
        if ( !is_null($value) && Storage::disk('public')->exists("passport-template/$value") ) {
            return url()->asset("storage/passport-template/$value").'?v='.Str::random(6);
        }
        return url()->asset('storage/passport-template/PP-0000-0000-0000.png').'?v='.Str::random(6);
    }

    /**
     * @param $value
     * @return null|string
     */
    public function getTemplateAttribute($value)
    {
        if ( !is_null($value) && Storage::disk('local')->exists("templates/$value") ) {
            return $value;
        }
        return null;
    }
}
