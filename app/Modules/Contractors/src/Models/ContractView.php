<?php

namespace App\Modules\Contractors\src\Models;

use App\Models\Security\Area;
use App\Models\Security\Subdirectorate;
use App\Models\Security\User;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ContractView extends Model implements Auditable
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
    protected $table = 'contracts_view';

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
        'contractor_id',
        'contractor_document',
        'contract_type_id',
        'contract_type',
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
        'subdirectorate',
        'dependency_id',
        'dependency',
        'other_dependency_subdirectorate',
        'supervisor_email',
        'lawyer_id',
        'lawyer_name',
        'lawyer_surname',
        'lawyer_document',
        'secop_files_count',
        'arl_files_count',
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
        'secop_files_count' => 'int',
        'arl_files_count' => 'int',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [ 'start_date', 'final_date', 'start_suspension_date', 'final_suspension_date' ];

    /*
     * ---------------------------------------------------------
     * Eloquent Relations
     * ---------------------------------------------------------
    */

    public function contractor()
    {
        return $this->belongsTo(ContractorView::class, 'contractor_id');
    }

    public function files()
    {
        return $this->hasMany(FileView::class, 'contract_id');
    }
}
