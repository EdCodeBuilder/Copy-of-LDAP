<?php

namespace App\Modules\Payroll\src\Models;

use Illuminate\Database\Eloquent\Model;

class UserSeven extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'oracle';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'CT_VPAGO';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'TER_NOCO';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'TER_NOCO',
        'CON_OBJT',
        'CON_NCON',
        'rubro',
        'fuente',
        'concepto',
    ];
}
