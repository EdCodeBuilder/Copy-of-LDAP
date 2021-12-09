<?php

namespace App\Modules\CitizenPortal\src\Models;

use App\Traits\PaginationWithHavings;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScheduleView extends Model
{
    use SoftDeletes, PaginationWithHavings;

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
    protected $table = "schedule_view";

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
        'program_id',
        'program_name',
        'activity_id',
        'activity_name',
        'stage_id',
        'stage_name',
        'park_id',
        'park_code',
        'park_name',
        'park_address',
        'weekday_id',
        'weekday_name',
        'daily_id',
        'daily_name',
        'min_age',
        'max_age',
        'quota',
        'taken',
        'is_paid',
        'is_initiate',
        'start_date',
        'final_date',
        'is_activated',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'start_date',
        'final_date',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'program_id'    => 'int',
        'activity_id'   => 'int',
        'stage_id'      => 'int',
        'park_id'       => 'int',
        'weekday_id'    => 'int',
        'daily_id'      => 'int',
        'min_age'       => 'int',
        'max_age'       => 'int',
        'quota'         => 'int',
        'is_paid'       => 'boolean',
        'is_initiate'   => 'boolean',
        'is_activated'  => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [ 'name' ];

    /*
     * ---------------------------------------------------------
     * Query Scopes
     * ---------------------------------------------------------
     */

    /**
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeSearch($query, $value)
    {
        return $query->where(function ($query) use ($value) {
            return $query
                ->where('program_name', 'like', "%$value%")
                ->orWhere('activity_name', 'like', "%$value%")
                ->orWhere('stage_name', 'like', "%$value%")
                ->orWhere('park_code', 'like', "%$value%")
                ->orWhere('park_name', 'like', "%$value%")
                ->orWhere('park_address', 'like', "%$value%")
                ->orWhere('weekday_name', 'like', "%$value%")
                ->orWhere('daily_name', 'like', "%$value%")
                ->orWhere('min_age', 'like', "%$value%")
                ->orWhere('max_age', 'like', "%$value%")
                ->orWhere('quota', 'like', "%$value%");
        });
    }

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */

    /**
     * @return string
     */
    public function getNameAttribute()
    {
        return "{$this->weekday_name} {$this->daily_name} - ({$this->min_age}-{$this->max_age}) AÑOS";
    }

    /**
     * @param $column
     * @return string
     */
    public function getSortableColumn($column)
    {
        switch ($column) {
            case 'created_at':
            case 'updated_at':
            case 'deleted_at':
                return $column;
            default:
                return in_array($column, $this->fillable)
                    ? $column
                    : $this->primaryKey;
        }
    }

    /*
     * ---------------------------------------------------------
     * Eloquent Relationship
     * ---------------------------------------------------------
     */

    /**
     * @return HasMany
     */
    public function users_schedules()
    {
        return $this->hasMany(CitizenSchedule::class, 'schedule_id', 'id');
    }

    /**
     * @return mixed
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'id', 'id');
    }
}
