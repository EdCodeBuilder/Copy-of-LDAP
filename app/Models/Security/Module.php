<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;

class Module extends Model
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
    protected $table = "modulo";

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = "Id_Modulo";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Nombre_Modulo',
        'area',
        'Redireccion', //'redirect',
        'Imagen', // 'image',
        'Estado', // 'status',
        'Misional', // 'missionary',
        'compatible',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'Estado'     => 'bool',
        'Misional' => 'bool',
        'compatible' => 'bool',
    ];

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */

    /**
     * Set the module's name in uppercase.
     *
     * @return string
     */
    public function setNombreModuloAttribute($value)
    {
        $this->attributes['Nombre_Modulo'] = toUpper($value);
    }

    /**
     * Set the module's area in uppercase.
     *
     * @return string
     */
    public function setAreaAttribute($value)
    {
        $this->attributes['area'] = toUpper($value);
    }

    /**
     * @return mixed|string|null
     */
    public function getIdAttribute()
    {
        return !is_null($this->Id_Modulo) ? (int) $this->Id_Modulo : null;
    }

    /**
     * @return mixed|string|null
     */
    public function getNameAttribute()
    {
        return toUpper($this->Nombre_Modulo);
    }

    /**
     * @return mixed|string|null
     */
    public function getRedirectAttribute()
    {
        if (!is_null($this->Redireccion) && !filter_var($this->Redireccion, FILTER_VALIDATE_URL)) {
            if (str_starts_with($this->Redireccion, '../')) {
                return Str::replaceFirst('../', 'https://www.idrd.gov.co/SIM/', $this->Redireccion);
            } else if (str_starts_with($this->Redireccion, '/')) {
                return Str::replaceFirst('/', 'https://www.idrd.gov.co/SIM/', $this->Redireccion);
            }
            return 'https://www.idrd.gov.co/SIM/'.$this->Redireccion;
        }
        return trim($this->Redireccion);
    }

    /**
     * @return mixed|string|null
     */
    public function getImageAttribute()
    {
        if (!is_null($this->Imagen) && !filter_var($this->Imagen, FILTER_VALIDATE_URL)) {
            if (str_starts_with( $this->Imagen, '../')) {
                return Str::replaceFirst('../', 'https://www.idrd.gov.co/', $this->Redireccion);
            } else if (str_starts_with($this->Imagen, '/')) {
                return Str::replaceFirst('/', 'https://www.idrd.gov.co/', $this->Redireccion);
            }
            return 'https://www.idrd.gov.co/'.$this->Imagen;
        }
        return trim($this->Imagen);
    }

    /**
     * @return bool|null
     */
    public function getStatusAttribute()
    {
        return !is_null($this->Estado) ? (bool) $this->Estado : null;
    }

    /**
     * @return bool|null
     */
    public function getMissionaryAttribute()
    {
        return !is_null($this->Misional) ? (bool) $this->Misional : null;
    }

    /*
    * ---------------------------------------------------------
    * Query Scopes
    * ---------------------------------------------------------
    */

    /**
     * Check if user is active
     *
     * @param $query
     * @return Builder
     */
    public function scopeActive($query)
    {
        return $query->where('Estado', true);
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
        'Nombre_Modulo',
        'area',
        'Redireccion', //'redirect',
        'Imagen', // 'image',
        'Estado', // 'status',
        'Misional', // 'missionary',
        'compatible',
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags() : array
    {
        return ['module'];
    }

    /*
    * ---------------------------------------------------------
    * Eloquent
    * ---------------------------------------------------------
    */

    public function incompatible_access()
    {
        return $this->hasMany( IncompatibleAccess::class, 'Id_Modulo', 'Id_Modulo' );
    }
}
