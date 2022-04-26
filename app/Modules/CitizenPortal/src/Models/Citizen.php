<?php

namespace App\Modules\CitizenPortal\src\Models;

use App\Modules\CitizenPortal\src\Traits\CitizenNotifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class Citizen extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable, CitizenNotifiable;

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = "mysql_citizen_portal";

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "users";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'token',
        'remember_token',
        'verified_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [ 'verified_at' ];

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
        'email',
        'verified_at',
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags(): array
    {
        return ['citizen_portal_citizen'];
    }

    /*
     * ---------------------------------------------------------
     * Eloquent Relationship
     * ---------------------------------------------------------
     */

    /**
     * @return HasMany
     */
    public function profiles()
    {
        return $this->hasMany(Profile::class, 'user_id', 'id');
    }
}
