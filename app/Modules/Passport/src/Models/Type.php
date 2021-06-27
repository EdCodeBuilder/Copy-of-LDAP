<?php

namespace App\Modules\Passport\src\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $connection = 'mysql_sim';
    protected $table = 'tipo';
    protected $primaryKey = 'Id_Tipo';
    protected $fillable = ['Nombre','Id_Modulo'];
    public $timestamps = false;

    public function users()
    {
        return $this->belongsToMany(User::class, 'persona_tipo', 'Id_Tipo', 'Id_Persona');
    }
}
