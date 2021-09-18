<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IncompatibleAccess extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = "mysql_sim";

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'actividades';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = "Id_Actividad";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "Id_Modulo",
        "Nombre_Actividad",
        "Descripcion",
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        "Id_Modulo" =>  "int"
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */

    /**
     * Get the access's name.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->Nombre_Actividad;
    }

    /**
     * Get the access's name.
     *
     * @return string
     */
    public function getDescriptionAttribute()
    {
        return $this->Descripcion;
    }

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
        'Id_Modulo',
        'Nombre_Actividad',
        'Descripcion',
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags() : array
    {
        return ['incompatible_access'];
    }

    /*
    * ---------------------------------------------------------
    * Eloquent Relations
    * ---------------------------------------------------------
    */

    /**
     * An activity belongs to a module
     *
     * @return BelongsTo
     */
    public function module()
    {
        return $this->belongsTo(Module::class, 'Id_Modulo', 'Id_Modulo');
    }

    /**
     * An activity has restricted permission for an specific user
     *
     * @return HasMany
     */
    public function permission()
    {
        return $this->hasMany(ActivityAccess::class, 'Id_Actividad', 'Id_Actividad');
    }
}
