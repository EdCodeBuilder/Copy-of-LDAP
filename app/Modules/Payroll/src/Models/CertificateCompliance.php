<?php

namespace App\Modules\Payroll\src\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CertificateCompliance extends Model implements Auditable
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
    protected $table = 'certificate_compliance';

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
        'supervisor',
        'component',
        'profession',
        'funding_source',
        'entry',
        'total_pay',
        'settlement_period',
        /* 'name',
        'document',
        'contract',
        'virtual_file',
        'username',
        'expires_at',
        'token',
        'type',
        'contractor_id', */
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    //protected $dates = ['expires_at'];

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
        'supervisor',
        'component',
        'funding_source',
        'entry',
        'total_pay',
        'settlement_period',
        /* 'name',
        'document',
        'contract',
        'virtual_file',
        'username',
        'expires_at',
        'token',
        'type',
        'contractor_id', */
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    /* public function generateTags(): array
    {
        return ['contractors_certifications'];
    } */

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
    /* public function setNameAttribute($value)
    {
        $this->attributes['name'] = toUpper($value);
    } */
}
