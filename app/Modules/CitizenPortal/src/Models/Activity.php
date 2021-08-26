<?php

namespace App\Modules\CitizenPortal\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use OwenIt\Auditing\Contracts\Auditable;

class Activity extends Model implements Auditable
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
    protected $table = "activities";

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
        'activity_name',
        'is_initiate',
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
        'activity_name',
        'is_initiate',
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags(): array
    {
        return ['citizen_portal_activity'];
    }

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */

    /**
     * @return mixed|string|null
     */
    public function getNameAttribute()
    {
        return toUpper( $this->activity_name );
    }

    /**
     * @param array $data
     * @return array
     */
    public function fillModel(array $data)
    {
        return [
            'activity_name' => Arr::get($data, 'name'),
            'is_initiate'   => (bool) Arr::get($data, 'is_initiate', true),
        ];
    }

    /*
     * ---------------------------------------------------------
     * Eloquent Relationship
     * ---------------------------------------------------------
     */

    /**
     * @return BelongsTo
     */
    public function stage()
    {
        return $this->belongsTo(Stage::class, 'stage_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function schedules()
    {
        return $this->belongsTo(Schedule::class, 'id', 'activity_id');
    }

    /**
     * @param $column
     * @return string
     */
    public function getSortableColumn($column)
    {
        switch ($column) {
            case 'name':
                return 'activity_name';
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
}
