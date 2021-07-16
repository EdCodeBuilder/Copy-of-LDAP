<?php

namespace App\Modules\Payroll\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
    protected $primaryKey = 'TER_CODI';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'TER_CODI',
        'TER_NOCO',
        'CON_OBJT',
        'CON_NCON',
        'RUBRO',
        'FUENTE',
        'CONCEPTO',
    ];
    //Add extra attribute
    protected $attributes = ['id_aux'];

    //Make it available in the json response
    protected $appends = ['id_aux'];

    //implement the attribute
    public function getIdAuxAttribute()
    {
        return Str::random(9);
    }
}
