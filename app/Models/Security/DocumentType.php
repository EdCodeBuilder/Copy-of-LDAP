<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
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
    protected $table = 'tipo_documento';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'Id_TipoDocumento';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['Nombre_TipoDocumento', 'Descripcion_TipoDocumento'];

    public function getIdAttribute()
    {
        return (int) $this->Id_TipoDocumento;
    }

    public function getNameAttribute()
    {
        return toUpper($this->Nombre_TipoDocumento);
    }

    public function getDescriptionAttribute()
    {
        return toUpper($this->Descripcion_TipoDocumento);
    }
}
