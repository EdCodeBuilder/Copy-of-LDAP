<?php

namespace App\Modules\Parks\src\Models;

use Illuminate\Database\Eloquent\Model;

class EconomicUsePark extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mysql_parks';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ActividadesAprovechamientoSino';

    public function economic_use()
    {
        return $this->hasOne( EconomicUse::class, 'Id', 'IdActividad');
    }
}
