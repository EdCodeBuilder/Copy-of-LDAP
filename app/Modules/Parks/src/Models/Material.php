<?php

namespace App\Modules\Parks\src\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mysql_parks';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'material';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Material',
    ];

    /**a
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'IdMaterial';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

}
