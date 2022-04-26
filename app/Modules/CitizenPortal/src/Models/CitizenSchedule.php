<?php

namespace App\Modules\CitizenPortal\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Contracts\Auditable;

class CitizenSchedule extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
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
    protected $table = "user_schedule";

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = "id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'schedule_id',
        'profile_id',
        'status_id',
        'payment_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ["payment_at"];

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
        'schedule_id',
        'profile_id',
        'status_id',
        'payment_at',
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags(): array
    {
        return ['citizen_portal_citizen_schedule'];
    }

    /*
    * ---------------------------------------------------------
    * Query Scopes
    * ---------------------------------------------------------
    */

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('status_id', '=', Status::SUBSCRIBED);
    }

    /*
    * ---------------------------------------------------------
    * Eloquent Relationships
    * ---------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function files()
    {
        return $this->hasMany( File::class, 'id', 'citizen_schedule_id' );
    }

    /**
     * @return HasOne
     */
    public function status()
    {
        return $this->hasOne( Status::class, 'id', 'status_id' );
    }

    /**
     * @return BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo(Profile::class, 'profile_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function profiles_view()
    {
        return $this->belongsTo(ProfileView::class, 'profile_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function schedule_view()
    {
        return $this->belongsTo(ScheduleView::class, 'schedule_id', 'id');
    }
}
