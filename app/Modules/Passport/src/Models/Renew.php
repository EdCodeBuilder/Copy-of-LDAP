<?php

namespace App\Modules\Passport\src\Models;


use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Renew extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'mysql_passport';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_renovacion';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'i_pk_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'i_fk_id_pasaporte',
        'i_fk_id_usuario',
        'i_fk_id_usuario_supercade',
        'i_fk_supercade',
        'vc_denuncio',
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
        'i_fk_id_pasaporte',
        'i_fk_id_usuario',
        'i_fk_id_usuario_supercade',
        'i_fk_supercade',
        'vc_denuncio',
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags(): array
    {
        return ['vital_passport_renewals'];
    }

    /*
     * ---------------------------------------------------------
     * Eloquent Relationships
     * ---------------------------------------------------------
     */

    public function passport()
    {
        return $this->belongsTo(PassportView::class, 'i_fk_id_pasaporte', 'id');
    }

    public function passport_old()
    {
        return $this->belongsTo(PassportOldView::class, 'i_fk_id_pasaporte', 'id');
    }
}
