<?php

namespace App\Modules\Contractors\src\Models;

use App\Models\Security\Area;
use App\Models\Security\Subdirectorate;
use App\Models\Security\User;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Contract extends Model implements Auditable
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
    protected $table = 'contracts';

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
        'contract',
        'transport',
        'position',
        'start_date',
        'final_date',
        'start_suspension_date',
        'final_suspension_date',
        'total',
        'day',
        'risk',
        'subdirectorate_id',
        'dependency_id',
        'other_dependency_subdirectorate',
        'supervisor_email',
        'contractor_id',
        'contract_type_id',
        'lawyer_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'transport'  => 'boolean',
        'risk'      => 'int',
        'subdirectorate_id'      => 'int',
        'dependency_id'      => 'int',
        'contractor_id'    => 'int',
        'contract_type_id' => 'int',
        'lawyer_id' => 'int',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [ 'start_date', 'final_date', 'start_suspension_date', 'final_suspension_date' ];

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
        'contract',
        'transport',
        'position',
        'start_date',
        'final_date',
        'start_suspension_date',
        'final_suspension_date',
        'total',
        'day',
        'risk',
        'subdirectorate_id',
        'dependency_id',
        'other_dependency_subdirectorate',
        'supervisor_email',
        'contractor_id',
        'contract_type_id',
        'lawyer_id',
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags(): array
    {
        return ['contractors_contract'];
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
    public function setContractAttribute($value)
    {
        $this->attributes['contract'] = toUpper($value);
    }

    /**
     * Set value in uppercase
     *
     * @param $value
     */
    public function setPositionAttribute($value)
    {
        $this->attributes['position'] = toUpper($value);
    }

    /**
     * Set value in uppercase
     *
     * @param $value
     */
    public function setDayAttribute($value)
    {
        $this->attributes['day'] = implode(', ', $value);
    }

    /**
     * Set value in uppercase
     *
     * @param $value
     */
    public function setOtherDependencySubdirectorateAttribute($value)
    {
        $this->attributes['other_dependency_subdirectorate'] = toUpper($value);
    }

    /**
     * Set value in uppercase
     *
     * @param $value
     */
    public function getDayAttribute($value)
    {
        return explode(', ', $value);
    }

    /*
     * ---------------------------------------------------------
     * Eloquent Relations
     * ---------------------------------------------------------
    */

    public function subdirectorate()
    {
        return $this->belongsTo(Subdirectorate::class);
    }

    public function dependency()
    {
        return $this->belongsTo(Area::class);
    }

    public function contractor()
    {
        return $this->belongsTo(Contractor::class);
    }

    public function contract_type()
    {
        return $this->belongsTo(ContractType::class);
    }


    public function lawyer()
    {
        return $this->belongsTo(User::class, 'lawyer_id');
    }

    public function files()
    {
        return $this->hasMany(File::class, 'contract_id');
    }
}
