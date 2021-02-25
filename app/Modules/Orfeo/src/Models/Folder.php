<?php

namespace App\Modules\Orfeo\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

class Folder extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'pgsql_orfeo';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'carpeta';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'carp_codi';

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $withCount = ['filed', 'read', 'unread'];

    /*
    * ---------------------------------------------------------
    * Query Scopes
    * ---------------------------------------------------------
    */

    /**
     * Check if folder is active
     *
     * @param $query
     * @return Builder
     */
    public function scopeActive($query)
    {
        return $query->where('carp_estado', 1);
    }

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */

    /**
     * Get the name.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return isset($this->carp_desc)
                ? toUpper( $this->carp_desc )
                : null;
    }

    /*
    * ---------------------------------------------------------
    * Eloquent Relations
    * ---------------------------------------------------------
    */

    /**
     *  Folder has one or many filed
     *
     * @return HasMany
     */
    public function filed()
    {
        return $this->hasMany(Filed::class, 'carp_codi');
    }

    /**
     *  Folder has one or many filed read
     *
     * @return HasMany
     */
    public function read()
    {
        return $this->hasMany(Filed::class, 'carp_codi')->where('radi_leido', true);
    }

    /**
     *  Folder has one or many filed unread
     *
     * @return HasMany
     */
    public function unread()
    {
        return $this->hasMany(Filed::class, 'carp_codi')->where('radi_leido', false);
    }
}
