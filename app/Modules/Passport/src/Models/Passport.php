<?php

namespace App\Modules\Passport\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use OwenIt\Auditing\Contracts\Auditable;

class Passport extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    const SUPER_CADE_INTERNET = 8;
    const SUPER_USER = 2811456;

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
    protected $table = 'tbl_usuarios_pasaporte';

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
        'i_fk_id_usuario_supercade',
        'i_fk_id_usuario',
        'i_fk_id_superCade',
        'vc_pensionado',
        'i_fk_id_localidad',
        'i_fk_id_estrato',
        'vc_direccion',
        'vc_telefono',
        'vc_celular',
        'i_fk_id_actividades',
        'i_fk_id_eps',
        'printed',
        'email',
        'downloads',
        'file',
        'tx_observacion',
        'i_pregunta1',
        'i_pregunta2',
        'i_pregunta3',
        'i_pregunta4'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'i_fk_id_usuario_supercade' => 'int',
        'i_fk_id_usuario'   => 'int',
        'i_fk_id_superCade' => 'int',
        'i_fk_id_localidad' => 'int',
        'i_fk_id_estrato'   => 'int',
        'vc_telefono'   => 'int',
        'vc_celular'    => 'int',
        'i_fk_id_actividades'   => 'int',
        'i_fk_id_eps'   => 'int',
        'printed'   => 'bool',
        'downloads' => 'int',
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
        'i_fk_id_usuario_supercade',
        'i_fk_id_usuario',
        'i_fk_id_superCade',
        'vc_pensionado',
        'i_fk_id_localidad',
        'i_fk_id_estrato',
        'vc_direccion',
        'vc_telefono',
        'vc_celular',
        'i_fk_id_actividades',
        'i_fk_id_eps',
        'printed',
        'email',
        'downloads',
        'file',
        'tx_observacion',
        'i_pregunta1',
        'i_pregunta2',
        'i_pregunta3',
        'i_pregunta4'
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags(): array
    {
        return ['vital_passport_new_passport'];
    }

    /**
     * @param array $request
     * @return array
     */
    public function transformRequest(array $request) {
        return [
            'i_fk_id_usuario_supercade' =>  Passport::SUPER_USER,
            'i_fk_id_usuario'   => null,
            'i_fk_id_superCade' => Passport::SUPER_CADE_INTERNET,
            'vc_pensionado'     => Arr::get($request, 'pensionary', null),
            'i_fk_id_localidad' => Arr::get($request, 'locality_id', null),
            'i_fk_id_estrato'   => Arr::get($request, 'stratum', null),
            'vc_direccion'      => toUpper(Arr::get($request, 'address', null)),
            'vc_telefono'       => Arr::get($request, 'phone', null),
            'vc_celular'        => Arr::get($request, 'mobile', null),
            'email'             => Arr::get($request, 'email', null),
            'i_fk_id_actividades'   => Arr::get($request, 'interest_id', null),
            'i_fk_id_eps'           => Arr::get($request, 'eps_id', null),
            'tx_observacion'        => Arr::get($request, 'observations', null),
            'i_pregunta1'           => Arr::get($request, 'question_1', null),
            'i_pregunta2'           => Arr::get($request, 'question_2', null),
            'i_pregunta3'           => Arr::get($request, 'question_3', null),
            'i_pregunta4'           => Arr::get($request, 'question_4', null),
        ];
    }

    /*
     * ---------------------------------------------------------
     * Eloquent Relationships
     * ---------------------------------------------------------
     */

    public function getIdAttribute()
    {
        return (int) $this->i_pk_id;
    }

    public function getUserIdAttribute()
    {
        return isset($this->i_fk_id_usuario) ? (int) $this->i_fk_id_usuario : null;
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
        return $this->belongsTo(User::class, 'i_fk_id_usuario', 'Id_Persona');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function renewals()
    {
        return $this->hasMany(Renew::class, 'i_fk_id_pasaporte', 'i_pk_id');
    }
}
