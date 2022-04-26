<?php

namespace App\Modules\CitizenPortal\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Status extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    // Ids de la tabla status "estados"

    // Validate Profile
    const PENDING = 1;
    const VALIDATING = 2;
    const VERIFIED = 3;
    const RETURNED = 4;

    // Validate Subscription
    const PENDING_SUBSCRIBE = 5;
    const SUBSCRIBED = 6;
    const UNSUBSCRIBED = 7;

    // Document
    const FILE_PENDING = 8;
    const FILE_VERIFIED = 9;
    const FILE_RETURNED = 10;

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
            case Status::VALIDATING:
                return 'warning';
            case Status::FILE_VERIFIED:
            case Status::SUBSCRIBED:
            case Status::VERIFIED:
                return 'success';
            case Status::UNSUBSCRIBED:
            case Status::RETURNED:
            case Status::FILE_RETURNED:
                return 'error';
            case Status::FILE_PENDING:
            case Status::PENDING_SUBSCRIBE:
            case Status::PENDING:
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
            Status::RETURNED,
            Status::PENDING,
            Status::VALIDATING,
            Status::VERIFIED,
        ]);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeSubscription($query)
    {
        return $query->wherekey([
            Status::PENDING_SUBSCRIBE,
            Status::SUBSCRIBED,
            Status::UNSUBSCRIBED,
        ]);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeFiles($query)
    {
        return $query->wherekey([
            Status::FILE_VERIFIED,
            Status::FILE_RETURNED,
            Status::FILE_PENDING,
        ]);
    }
}
