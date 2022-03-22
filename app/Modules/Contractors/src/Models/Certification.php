<?php

namespace App\Modules\Contractors\src\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Certification extends Model implements Auditable
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
    protected $table = 'certifications';

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
        'name',
        'document',
        'contract',
        'virtual_file',
        'username',
        'expires_at',
        'token',
        'type',
        'year',
        'contractor_id',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['expires_at'];

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
        'document',
        'contract',
        'virtual_file',
        'username',
        'expires_at',
        'token',
        'type',
        'year',
        'contractor_id',
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags(): array
    {
        return ['contractors_certifications'];
    }

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */

    /**
     * Set value in uppercase
     *
     * @param $value
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = toUpper($value);
    }

    /**
     * Set value in uppercase
     *
     * @param $value
     */
    public function setContractAttribute($value)
    {
        $this->attributes['contract'] = toUpper($value);
    }

    /**
     * Set value in uppercase
     *
     * @param $value
     */
    public function setVirtualFileAttribute($value)
    {
        $this->attributes['virtual_file'] = toUpper($value);
    }
}
