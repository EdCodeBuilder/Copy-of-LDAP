<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = "mysql_ldap";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'document_type_id',
        'birthdate',
        'contract_number',
        'virtual_file',
        'city_id',
        'gender_id',
        'ethnicity_id',
        'gender_identity_id',
        'user_id',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [ 'birthdate' ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'birthdate' => 'date',
        'document_type_id' => 'int',
        'city_id' => 'int',
        'gender_id' => 'int',
        'ethnicity_id' => 'int',
        'gender_identity_id' => 'int',
        'user_id' => 'int',
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
        'document_type_id',
        'birthdate',
        'contract_number',
        'virtual_file',
        'city_id',
        'gender_id',
        'ethnicity_id',
        'gender_identity_id',
        'user_id',
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags() : array
    {
        return ['profile'];
    }

    /*
   * ---------------------------------------------------------
   * Eloquent Relations
   * ---------------------------------------------------------
   */

    /**
     *  Profile belongs to an user
     *
     * @return BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo(User::class);
    }
}
