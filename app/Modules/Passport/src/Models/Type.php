<?php

namespace App\Modules\Passport\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Type extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'mysql_sim';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tipo';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'Id_Tipo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['Nombre','Id_Modulo'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /*
     * ---------------------------------------------------------
     * Eloquent Relationships
     * ---------------------------------------------------------
     */

    /**
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'persona_tipo', 'Id_Tipo', 'Id_Persona');
    }
}
