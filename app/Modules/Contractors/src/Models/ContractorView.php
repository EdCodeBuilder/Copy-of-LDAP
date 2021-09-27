<?php

namespace App\Modules\Contractors\src\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class ContractorView extends Model
{

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'mysql_contractors';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contractors_view';

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
        'document_type_id',
        'document_type',
        'document',
        'name',
        'surname',
        'birthdate',
        'birthdate_country_id',
        'birt_country',
        'birthdate_state_id',
        'birt_state',
        'birthdate_city_id',
        'birt_city',
        'sex_id',
        'email',
        'institutional_email',
        'phone',
        'eps_id',
        'eps',
        'other_eps',
        'afp_id',
        'afp',
        'other_afp',
        'residence_country_id',
        'residence_country',
        'residence_state_id',
        'residence_state',
        'residence_city_id',
        'residence_city',
        'locality_id',
        'locality',
        'upz_id',
        'upz',
        'neighborhood_id',
        'neighborhood',
        'other_neighborhood',
        'address',
        'user_id',
        'creator_name',
        'creator_surname',
        'creator_document',
        'modifiable',
        'third_party',
        'rut',
        'bank',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'document_type_id'  => 'int',
        'document'  => 'int',
        'sex_id'    => 'int',
        'eps_id'    => 'int',
        'afp_id'    => 'int',
        'birthdate_country_id'  => 'int',
        'birthdate_state_id'    => 'int',
        'birthdate_city_id' => 'int',
        'residence_country_id'  => 'int',
        'residence_state_id'    => 'int',
        'residence_city_id' => 'int',
        'locality_id'       => 'int',
        'upz_id'            => 'int',
        'neighborhood_id'   => 'int',
        'third_party'   => 'bool',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [ 'birthdate', 'modifiable' ];

    /**
     * Get file url
     *
     * @param $value
     * @return string|null
     */
    public function getRutAttribute($value)
    {
        return Storage::disk('contractor')->exists($value)
            ? route('file.contractors.rut', ['contractor' => $this->id, 'name' => $value])
            : null;
    }

    /**
     * Get value in uppercase
     *
     * @param $value
     * @return string|null
     */
    public function getBankAttribute($value)
    {
        return Storage::disk('contractor')->exists($value)
            ? route('file.contractors.bank', ['contractor' => $this->id, 'name' => $value])
            : null;
    }


    /*
     * ---------------------------------------------------------
     * Eloquent Relations
     * ---------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function contracts()
    {
        return $this->hasMany(ContractView::class, 'contractor_id')->latest();
    }

    /**
     * @return HasMany
     */
    public function careers()
    {
        return $this->hasMany(ContractorCareer::class)->latest();
    }
}
