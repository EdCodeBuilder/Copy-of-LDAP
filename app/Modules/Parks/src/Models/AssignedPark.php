<?php


namespace App\Modules\Parks\src\Models;


use App\Models\Security\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AssignedPark extends Model
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
    protected $table = 'assigned_parks';

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
    protected $fillable = [ 'user_id', 'park_id' ];

    /*
    * ---------------------------------------------------------
    * Eloquent Relations
    * ---------------------------------------------------------
    */

    /**
     *
     *
     * @return HasOne
     */
    public function park()
    {
        return $this->hasOne(Park::class, 'park_id', 'Id');
    }

    /**
     *
     *
     * @return HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'user_id', 'id');
    }
}
