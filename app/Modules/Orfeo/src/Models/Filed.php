<?php

namespace App\Modules\Orfeo\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Filed extends Model
{
    const FILED = 1;
    const PRINCIPAL = 2;
    const PRINTED = 3;
    const SENT = 4;

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
    protected $table = 'radicado';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'radi_nume_radi';

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['user', 'dependency', 'city', 'document_type', 'folder'];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $withCount = ['attachments', 'associates', 'informed'];

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */

    /**
     * Get the addressee full name.
     *
     * @return string
     */
    public function getAddresseeFullNameAttribute()
    {
        $first_name = toUpper( $this->radi_nomb );
        $last_name  = toUpper( $this->radi_prim_apel );
        $second_last_name  = toUpper( $this->radi_segu_apel );
        $name = toUpper( "{$first_name} {$last_name} {$second_last_name}" );
        return $name ? $name : null;
    }

    /*
    * ---------------------------------------------------------
    * Eloquent Relations
    * ---------------------------------------------------------
    */

    /**
     *  Filed belongs to user
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'radi_usua_actu');
    }

    /**
     *  Filed belongs to dependency
     *
     * @return BelongsTo
     */
    public function dependency()
    {
        return $this->belongsTo(Dependency::class, 'radi_depe_actu');
    }

    /**
     *  Filed belongs to city
     *
     * @return BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'muni_codi');
    }

    /**
     *  Filed belongs to document type
     *
     * @return BelongsTo
     */
    public function document_type()
    {
        return $this->belongsTo(DocumentType::class, 'tdoc_codi');
    }

    /**
     *  Filed belongs to document type
     *
     * @return BelongsTo
     */
    public function folder()
    {
        return $this->belongsTo(Folder::class, 'carp_codi');
    }

    /**
     *  Filed has one or many attachments
     *
     * @return HasMany
     */
    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'anex_radi_nume')->orderBy('anex_numero');
    }

    /**
     *  Filed has one or many attachments
     *
     * @return HasMany
     */
    public function history()
    {
        return $this->hasMany(History::class, 'radi_nume_radi')->orderByDesc('hist_fech');
    }

    /**
     *  Filed has one or many associated
     *
     * @return HasMany
     */
    public function associates()
    {
        return $this->hasMany(Associated::class, 'radi_padre', 'radi_nume_radi')->orderByDesc('fech_crea');
    }

    /**
     *  Filed has one or many associated
     *
     * @return HasMany
     */
    public function informed()
    {
        return $this->hasMany(Informed::class, 'radi_nume_radi');
    }
}
