<?php

namespace App\Modules\Passport\src\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PassportOld extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'mysql_passport_old';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pasaporte_pasaporte';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'idPasaporte';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'documento',
        'fechaExpedicion',
        'horaExpedicion',
        'estadoPasaporte',
        'impreso',
        'idOperario',
        'operario',
        'downloads',
        'Renovacion',
        'supercade',
    ];

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
        'documento',
        'fechaExpedicion',
        'horaExpedicion',
        'estadoPasaporte',
        'impreso',
        'idOperario',
        'operario',
        'downloads',
        'Renovacion',
        'supercade',
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags(): array
    {
        return ['vital_passport_old_passport'];
    }

    /*
     * ---------------------------------------------------------
     * Eloquent Relationships
     * ---------------------------------------------------------
     */

    public function getIdAttribute()
    {
        return (int) $this->idPasaporte;
    }
}
