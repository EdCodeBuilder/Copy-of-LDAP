<?php

namespace App\Modules\Orfeo\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class History extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'pgsql_orfeo';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hist_eventos';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'radi_nume_radi',
        'depe_codi',
        'usua_codi',
        'hist_obse',
        'usua_codi_dest',
        'depe_codi_dest',
        'sgd_ttr_codigo',
        'hist_fech',
    ];

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */

    /**
     * Get the full name.
     *
     * @return string
     */
    public function getObservationAttribute()
    {
        return toUpper( $this->hist_obse );
    }

    /*
    * ---------------------------------------------------------
    * Eloquent Relations
    * ---------------------------------------------------------
    */

    /**
     *  Filed has one type of transaction
     *
     * @return HasOne
     */
    public function transaction()
    {
        return $this->hasOne( Transaction::class, 'sgd_ttr_codigo', 'sgd_ttr_codigo');
    }

    /**
     *  Filed belongs to user
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'usua_codi');
    }

    /**
     *  Filed belongs to dependency
     *
     * @return BelongsTo
     */
    public function dependency()
    {
        return $this->belongsTo(Dependency::class, 'depe_codi');
    }

    /**
     *  Filed belongs to user
     *
     * @return BelongsTo
     */
    public function addressee_user()
    {
        return $this->belongsTo(User::class, 'usua_codi_dest');
    }

    /**
     *  Filed belongs to dependency
     *
     * @return BelongsTo
     */
    public function addressee_dependency()
    {
        return $this->belongsTo(Dependency::class, 'depe_codi_dest');
    }
}
