<?php

namespace App\Modules\Contractors\src\Models;

use App\Models\Security\User;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class FileView extends Model implements Auditable
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
    protected $table = 'files_view';

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
        'contract_id',
        'contract',
        'contract_type_id',
        'contract_type',
        'file_name',
        'file_type_id',
        'file_type',
        'user_id',
        'user_name',
        'user_surname',
        'user_document',
        'contractor_id',
        'contractor_document',
    ];

    /*
     * ---------------------------------------------------------
     * Eloquent Relations
     * ---------------------------------------------------------
    */

    public function contracts()
    {
        return $this->belongsTo(ContractView::class, 'contract_id');
    }
}
