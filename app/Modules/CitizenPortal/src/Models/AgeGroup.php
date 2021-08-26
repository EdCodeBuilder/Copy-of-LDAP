<?php

namespace App\Modules\CitizenPortal\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use OwenIt\Auditing\Contracts\Auditable;

class AgeGroup extends Model implements Auditable
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
    protected $table = "age_groups";

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
        'grupo_etario',
        'min',
        'max',
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
        'name',
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags(): array
    {
        return ['citizen_portal_age_group'];
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
        return toUpper( $this->grupo_etario );
    }

    /**
     * @param array $data
     * @return array
     */
    public function fillModel(array $data)
    {
        return [
            'grupo_etario' => Arr::get($data, 'name'),
            'min' => Arr::get($data, 'min'),
            'max' => Arr::get($data, 'max'),
        ];
    }

    /**
     * @param $column
     * @return string
     */
    public function getSortableColumn($column)
    {
        switch ($column) {
            case 'name':
                return 'grupo_etario';
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
