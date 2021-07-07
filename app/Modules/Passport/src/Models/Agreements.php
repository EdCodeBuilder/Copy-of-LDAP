<?php

namespace App\Modules\Passport\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Agreements extends Model implements Auditable
{
    use SoftDeletes, \OwenIt\Auditing\Auditable;

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'mysql_passport';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tbl_agreements';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'agreement',
        'description',
        'company_id',
        'rate_5',
        'rate_4',
        'rate_3',
        'rate_2',
        'rate_1'
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
        'agreement',
        'description',
        'company_id',
        'rate_5',
        'rate_4',
        'rate_3',
        'rate_2',
        'rate_1'
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags(): array
    {
        return ['vital_passport_agreements'];
    }

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */

    public function setAgreementAttribute($value)
    {
        $this->attributes['agreement'] = toUpper($value);
    }

    /**
     * Get rate
     *
     * @return float
     */
    public function getRateAttribute()
    {
        $partial = (
            5 * (int) $this->rate_5 +
            4 * (int) $this->rate_4 +
            3 * (int) $this->rate_3 +
            2 * (int) $this->rate_2 +
            1 * (int) $this->rate_1
        );
        $sum =  (
            (int) $this->rate_5 +
            (int) $this->rate_4 +
            (int) $this->rate_3 +
            (int) $this->rate_2 +
            (int) $this->rate_1
        );
        $rate = $sum > 0 ? $partial / $sum : 0;
        return (float) $rate;
    }

    /**
     * Get the agreement's name.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return toUpper( "{$this->agreement}" );
    }

    /**
     * Set the description field in text.
     */
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = json_encode($value);
    }

    /*
     * ---------------------------------------------------------
     * Eloquent Relationships
     * ---------------------------------------------------------
     */

    /**
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function images()
    {
        return $this->hasMany(Image::class, 'agreement_id');
    }

    /**
     * @return HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'agreement_id')
            ->latest()->take(50);
    }
}
