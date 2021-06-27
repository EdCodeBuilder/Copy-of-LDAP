<?php

namespace App\Modules\Passport\src\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
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
    protected $table = 'tbl_cards';

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
        'title',
        'description',
        'lottie',
        'flex',
        'src',
        'to',
        'href',
        'dashboard_id',
    ];

    /*
     * ---------------------------------------------------------
     * Eloquent Relations
     * ---------------------------------------------------------
     */

    public function dashboard()
    {
        return $this->belongsTo(Dashboard::class);
    }
}
