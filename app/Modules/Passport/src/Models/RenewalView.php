<?php

namespace App\Modules\Passport\src\Models;

use App\Modules\Passport\src\Models\PassportOldView;
use App\Modules\Passport\src\Models\PassportView;
use Illuminate\Database\Eloquent\Model;

class RenewalView extends Model
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
    protected $table = 'renewal_passport_view';

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
        'passport_id',
        'user_id',
        'user_cade_id',
        'user_cade_document',
        'user_cade_name',
        'supercade_id',
        'supercade',
        'denounce',
    ];

    /*
     * ---------------------------------------------------------
     * Eloquent Relationships
     * ---------------------------------------------------------
     */

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'Id_Persona');
    }

    public function passport()
    {
        return $this->belongsTo(PassportView::class, 'passport_id', 'id');
    }

    public function passport_old()
    {
        return $this->belongsTo(PassportOldView::class, 'passport_id', 'id');
    }
}
