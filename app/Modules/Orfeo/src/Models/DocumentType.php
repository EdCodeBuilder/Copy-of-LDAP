<?php

namespace App\Modules\Orfeo\src\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
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
    protected $table = 'sgd_tpr_tpdcumento';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'sgd_tpr_codigo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sgd_tpr_descrip'];

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */

    /**
     * Get the full name.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return toUpper( $this->sgd_tpr_descrip );
    }

    /**
     * Get the business days
     *
     * @return int
     */
    public function getBusinessDaysAttribute()
    {
        return (int) $this->sgd_tpr_termino;
    }
}
