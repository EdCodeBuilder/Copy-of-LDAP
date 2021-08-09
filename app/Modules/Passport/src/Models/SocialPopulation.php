<?php

namespace App\Modules\Passport\src\Models;

use Illuminate\Database\Eloquent\Model;

class SocialPopulation extends Model
{
    protected $connection = 'mysql_sim';
    protected $table = 'social_poblacional';
    protected $primaryKey = 'id';
    protected $fillable = ['nombre'];
    public $timestamps = false;

    public function users()
    {
        return $this->belongsToMany(User::class, 'personas_grupos_sociales_poblacionales', 'id', 'Id_Persona');
    }
}
