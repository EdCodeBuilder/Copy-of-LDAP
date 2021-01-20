<?php

namespace App\Modules\Parks\src\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
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
    protected $table = 'estado';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'Id_Estado';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['Estado'];

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */

    /**
     * Get id
     *
     * @return int
     */
    public function getIdAttribute(): int
    {
        return (int) $this->Id_Estado;
    }

    /**
     * Get name in uppercase
     *
     * @return string
     */
    public function getNameAttribute(): string
    {
        return $this->Estado;
    }
}
