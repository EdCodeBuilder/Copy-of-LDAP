<?php

namespace App\Modules\CitizenPortal\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use OwenIt\Auditing\Contracts\Auditable;

class Hour extends Model implements Auditable
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
    protected $table = "daily_schedule";

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = "id_daily_schedule";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_daily_schedule',
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
        'name_daily_schedule',
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags(): array
    {
        return ['citizen_portal_hours'];
    }

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */

    /**
     * @return int
     */
    public function getIdAttribute()
    {
        return (int) $this->id_daily_schedule ;
    }

    /**
     * @return mixed|string|null
     */
    public function getNameAttribute()
    {
        return toUpper( $this->name_daily_schedule );
    }

    /**
     * @param array $data
     * @return array
     */
    public function fillModel(array $data)
    {
        return [
            'name_daily_schedule' => Arr::get($data, 'name'),
        ];
    }

    /**
     * @param $column
     * @return string
     */
    public function getSortableColumn($column)
    {
        switch ($column) {
            case 'id':
                return 'id_daily_schedule';
            case 'name':
                return 'name_daily_schedule';
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
