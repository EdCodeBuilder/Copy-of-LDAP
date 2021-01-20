<?php

namespace App\Modules\Parks\src\Models;

use Illuminate\Database\Eloquent\Model;

class ParkEndowment extends Model
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
    protected $table = 'parquedotacion';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'Id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function park()
    {
        return $this->belongsTo( Park::class, 'Id_Parque', 'Id' );
    }

    public function material()
    {
        return $this->belongsTo( Material::class, 'MaterialPiso', 'IdMaterial');
    }

    public function endowment()
    {
        return $this->belongsTo(Endowment::class, 'Id_Dotacion');
    }

    public function enclosure()
    {
        return $this->belongsTo(Enclosure::class, 'Cerramiento');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'Estado');
    }
}
