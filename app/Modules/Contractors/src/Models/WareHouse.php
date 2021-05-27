<?php

namespace App\Modules\Contractors\src\Models;

use Illuminate\Database\Eloquent\Model;

class WareHouse extends Model
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
    protected $table = 'af_activ';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'act_codi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ter_carg',
        'ter_resp',
        'act_desc',
        'act_cant',
        'act_cost',
    ];
}
