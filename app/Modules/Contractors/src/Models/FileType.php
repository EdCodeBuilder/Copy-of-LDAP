<?php

namespace App\Modules\Contractors\src\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class FileType extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'mysql_contractors';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'file_types';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'mimes',
        'can',
    ];

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */

    /**
     * Set value in uppercase
     *
     * @param $value
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = toUpper($value);
    }

    /*
     * ---------------------------------------------------------
     * Eloquent Relations
     * ---------------------------------------------------------
    */

    public function files()
    {
        return $this->hasMany(File::class);
    }
}
