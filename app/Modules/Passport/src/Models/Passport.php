<?php

namespace App\Modules\Passport\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

class Passport extends Model
{
    const SUPER_CADE_INTERNET = 8;

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
        'email',
        'downloads',
        'tx_observacion',
        'i_pregunta1',
        'i_pregunta2',
        'i_pregunta3',
        'i_pregunta4'
    ];

    /**
     * @param array $request
     * @return array
     */
    public function transformRequest(array $request) {
        return [
            'i_fk_id_usuario_supercade' =>  0,
            'i_fk_id_usuario'   => 0,
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
}
