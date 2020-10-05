<?php

namespace App\Modules\Orfeo\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Informed extends Model
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
    protected $table = 'informados';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'radi_nume_radi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'usua_codi',
        'depe_codi',
        'info_desc',
        'info_leido',
    ];

    /*
    * ---------------------------------------------------------
    * Eloquent Relations
    * ---------------------------------------------------------
    */

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
}
