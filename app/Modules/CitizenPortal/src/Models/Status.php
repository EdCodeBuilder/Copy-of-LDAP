<?php

namespace App\Modules\CitizenPortal\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Status extends Model implements Auditable
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
    protected $table = "status";

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
        'name',
    ];

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
        return ['citizen_portal_status'];
    }

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */

    /**
     * @param $value
     * @return mixed|string|null
     */
    public function getNameAttribute($value)
    {
        return toUpper( $value );
    }

    /**
     * @param $status
     * @return string
     */
    public static function getColor($status)
    {
        switch ($status) {
            case Profile::VALIDATING:
                return 'warning';
            case Profile::SUBSCRIBED:
            case Profile::VERIFIED:
                return 'success';
            case Profile::UNSUBSCRIBED:
            case Profile::RETURNED:
                return 'error';
            case Profile::PENDING_SUBSCRIBE:
            case Profile::PENDING:
            default:
                return 'yellow';
        }
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
     * Query Scopes
     * ---------------------------------------------------------
     */

    /**
     * @param $query
     * @return mixed
     */
    public function scopeProfile($query)
    {
        return $query->wherekey([
            Profile::RETURNED,
            Profile::PENDING,
            Profile::VALIDATING,
            Profile::VERIFIED,
        ]);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeSubscription($query)
    {
        return $query->wherekey([
            Profile::PENDING_SUBSCRIBE,
            Profile::SUBSCRIBED,
            Profile::UNSUBSCRIBED,
        ]);
    }
}
