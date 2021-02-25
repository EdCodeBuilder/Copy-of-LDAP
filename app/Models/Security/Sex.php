<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Model;

class Sex extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mysql_sim';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'genero';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'Id_Genero';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['Nombre_Genero'];

    public function getIdAttribute()
    {
        return (int) $this->Id_Genero;
    }

    public function getNameAttribute()
    {
        return toUpper($this->Nombre_Genero);
    }
}
