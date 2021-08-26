<?php

namespace App\Modules\CitizenPortal\src\Models;

use App\Modules\Parks\src\Models\Park;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use OwenIt\Auditing\Contracts\Auditable;

class Stage extends Model implements Auditable
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
    protected $table = "scenarios";

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
        'scenario_name',
        'park_id',
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
        'scenario_name',
        'park_id',
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags(): array
    {
        return ['citizen_portal_stage'];
    }

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */

    /**
     * @param array $data
     * @return array
     */
    public function fillModel(array $data)
    {
        return [
            'scenario_name' => Arr::get($data, 'name'),
            'park_id' => Arr::get($data, 'park_id'),
        ];
    }

    /**
     * @return mixed|string|null
     */
    public function getNameAttribute()
    {
        return toUpper( $this->scenario_name );
    }

    /**
     * @return mixed|string|null
     */
    public function getParkNameAttribute()
    {
        return isset($this->park->Nombre) ? toUpper( $this->park->Nombre ) : null;
    }

    /**
     * @return mixed|string|null
     */
    public function getParkCodeAttribute()
    {
        return isset($this->park->Id_IDRD) ? toUpper( $this->park->Id_IDRD ) : null;
    }

    /**
     * @param $column
     * @return string
     */
    public function getSortableColumn($column)
    {
        switch ($column) {
            case 'name':
                return 'scenario_name';
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
    public function schedules()
    {
        return $this->belongsTo(Schedule::class, 'id', 'stage_id');
    }

    /**
     * @return BelongsTo
     */
    public function park()
    {
        return $this->belongsTo(Park::class, 'park_id', 'Id');
    }
}
