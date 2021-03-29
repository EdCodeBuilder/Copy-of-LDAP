<?php

namespace App\Modules\Orfeo\src\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
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
    protected $table = 'usuario';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'usua_codi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'usua_nomb',
        'usua_doc',
        'usua_email'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */

    /**
     * Get the user id.
     *
     * @return integer
     */
    public function getIdAttribute()
    {
        return (int) $this->usua_codi;
    }

    /**
     * Get the full name.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return toUpper( $this->usua_nomb );
    }

    /**
     * Get the document.
     *
     * @return string
     */
    public function getDocumentAttribute()
    {
        return toUpper( $this->usua_doc );
    }

    /**
     * Get the email.
     *
     * @return string
     */
    public function getEmailAttribute()
    {
        return toLower( $this->usua_email );
    }
}
