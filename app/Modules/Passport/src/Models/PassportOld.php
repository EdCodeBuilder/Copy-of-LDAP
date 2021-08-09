<?php

namespace App\Modules\Passport\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

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
     * Accessors and Mutators
     * ---------------------------------------------------------
     */

    public function getIdAttribute()
    {
        return (int) $this->idPasaporte;
    }

    public function getUserIdAttribute()
    {
        return isset($this->user->Id_Persona) ? (int) $this->user->Id_Persona : null;
    }

    /*
     * ---------------------------------------------------------
     * Eloquent Relationships
     * ---------------------------------------------------------
     */

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'documento', 'Cedula');
    }

    /**
     * @return HasMany
     */
    public function renewals()
    {
        return $this->hasMany(Renew::class, 'i_fk_id_pasaporte', 'idPasaporte');
    }
}
