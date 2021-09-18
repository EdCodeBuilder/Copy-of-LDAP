<?php

namespace App\Modules\Parks\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use OwenIt\Auditing\Contracts\Auditable;

class Origin extends Model implements Auditable
{
    const IMAGES_PATH = 'parks/images';

    use \OwenIt\Auditing\Auditable, SoftDeletes;

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mysql_parks';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'datohistorico';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'IdParque';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['Parrafo1', 'Parrafo2', 'imagen1', 'imagen2', 'imagen3', 'imagen4', 'imagen5', 'imagen6'];


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
        'Parrafo1', 'Parrafo2', 'imagen1', 'imagen2', 'imagen3', 'imagen4', 'imagen5', 'imagen6'
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags(): array
    {
        return ['park_origin'];
    }

    /*
    * ---------------------------------------------------------
    * Accessors and Mutators
    * ---------------------------------------------------------
    */

    public function getImagesAttribute()
    {
        $images = collect();
        foreach (range(1, 6) as $value) {
            if (isset($this->{"imagen$value"}) && $this->fileExist($this->{"imagen$value"})) {
                $images->push(
                    Storage::disk('public')->url("parks/images/{$this->{"imagen$value"}}")
                );
            }
        }
        return array_values( $images->toArray() );
    }

    public function fileExist($file = null)
    {
       return !is_null($file) && $file != '' && Storage::disk('public')->exists("parks/images/$file");
    }

    /*
    * ---------------------------------------------------------
    * Eloquent Relations
    * ---------------------------------------------------------
    */

    /**
     * A story belongs to park
     *
     * @return BelongsTo
     */
    public function parks()
    {
        return $this->belongsTo(Park::class, 'IdParque', 'Id');
    }
}
