<?php

namespace App\Modules\CitizenPortal\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use OwenIt\Auditing\Contracts\Auditable;

class Schedule extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

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
    protected $table = "schedule";

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
        'weekday_id',
        'daily_id',
        'min_age',
        'max_age',
        'quota',
        'activity_id',
        'stage_id',
        'is_paid',
        'program_id',
        'fecha_apertura',
        'fecha_cierre',
        'is_activated',
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
        'weekday_id'       => 'int',
        'daily_id'       => 'int',
        'fecha_apertura'=> 'datetime',
        'fecha_cierre'  => 'datetime',
        'min_age'       => 'int',
        'max_age'       => 'int',
        'quota'         => 'int',
        'is_paid'       => 'boolean',
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
     * Data Change Auditor
     * ---------------------------------------------------------
     */

    /**
     * Attributes to include in the Audit.
     *
     * @var array
     */
    protected $auditInclude = [
        'weekday_id',
        'daily_id',
        'min_age',
        'max_age',
        'quota',
        'activity_id',
        'stage_id',
        'is_paid',
        'program_id',
        'fecha_apertura',
        'fecha_cierre',
        'is_activated',
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags(): array
    {
        return ['citizen_portal_schedule'];
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
        return "{$this->day->name} {$this->hour->name} - ({$this->min_age}-{$this->max_age}) AÑOS";
    }

    /**
     * @param array $data
     * @return array
     */
    public function fillModel(array $data)
    {
        return [
            'program_id'    => Arr::get($data, 'program_id'),
            'activity_id'   => Arr::get($data, 'activity_id'),
            'stage_id'      => Arr::get($data, 'stage_id'),
            'weekday_id'    => Arr::get($data, 'weekday_id'),
            'daily_id'      => Arr::get($data, 'daily_id'),
            'min_age'       => Arr::get($data, 'min_age'),
            'max_age'       => Arr::get($data, 'max_age'),
            'quota'         => Arr::get($data, 'quota'),
            'fecha_apertura' => Arr::get($data, 'start_date'),
            'fecha_cierre'   => Arr::get($data, 'final_date'),
            'is_paid'        => Arr::get($data, 'is_paid'),
            'is_activated'   => Arr::get($data, 'is_activated'),
        ];
    }

    /**
     * @param $column
     * @return string
     */
    public function getSortableColumn($column)
    {
        switch ($column) {
            case 'start_date':
                return 'fecha_apertura';
            case 'final_date':
                return 'fecha_cierre';
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
     * @return BelongsTo
     */
    public function activities()
    {
        return $this->belongsTo( Activity::class, 'activity_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function stage()
    {
        return $this->belongsTo( Stage::class, 'stage_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function users_schedules()
    {
        return $this->hasMany(CitizenSchedule::class, 'schedule_id', 'id');
    }

    /**
     * @return HasOne
     */
    public function day()
    {
        return $this->hasOne(Day::class, 'id_weekdays_schedule','weekday_id');
    }

    /**
     * @return HasOne
     */
    public function hour()
    {
        return $this->hasOne(Hour::class, 'id_daily_schedule','daily_id');
    }
}
