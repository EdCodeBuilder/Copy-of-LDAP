<?php

namespace App\Models\Security;

use Adldap\Auth\BindException;
use Adldap\Auth\PasswordRequiredException;
use Adldap\Auth\UsernameRequiredException;
use Adldap\Laravel\Facades\Adldap;
use Adldap\Laravel\Traits\HasLdapUser;
use App\Notifications\Auth\ResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;
use Silber\Bouncer\Database\HasRolesAndAbilities;

class User extends Authenticatable implements Auditable
{
    use Notifiable, HasApiTokens, SoftDeletes, HasLdapUser, HasRolesAndAbilities, \OwenIt\Auditing\Auditable, CanResetPassword;

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
        'guid',
        'name',
        'surname',
        'document',
        'email',
        'username',
        'description',
        'dependency',
        'company',
        'phone',
        'ext',
        'password',
        'vacation_start_date',
        'vacation_final_date',
        'expires_at',
        'sim_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [ 'expires_at', 'vacation_start_date', 'vacation_final_date' ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'vacation_start_date' => 'datetime',
        'vacation_final_date' => 'datetime',
    ];

    /*
     * ---------------------------------------------------------
     * Accessors and Mutator
     * ---------------------------------------------------------
     */

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return toUpper( "{$this->name} {$this->surname}" );
    }

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
        'guid',
        'name',
        'surname',
        'document',
        'email',
        'username',
        'description',
        'dependency',
        'company',
        'phone',
        'ext',
        'vacation_start_date',
        'vacation_final_date',
        'expires_at',
        'sim_id',
    ];

    /**
     * Attributes to exclude from the Audit.
     *
     * @var array
     */
    protected $auditExclude = [
        'password',
    ];

    /**
     * Generating tags for each model audited.
     *
     * @return array
     */
    public function generateTags() : array
    {
        return ['user'];
    }

    /*
    * ---------------------------------------------------------
    * Query Scopes
    * ---------------------------------------------------------
    */

    /**
     * Check if user is active
     *
     * @param $query
     * @return Builder
     */
    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now()->format('Y-m-d H:i:s'));
    }

    /*
    * ---------------------------------------------------------
    * Passport Validations
    * ---------------------------------------------------------
    */

    /**
     * Find the user instance for the given username.
     *
     * @param string $username
     * @return User
     */
    public function findForPassport( string $username )
    {
        return $this->active()->where('username', $username)->first();
    }

    /**
     * Validate the password of the user for the Passport password grant.
     *
     * @param string $password
     * @return bool
     * @throws PasswordRequiredException
     * @throws UsernameRequiredException
     */
    public function validateForPassportPasswordGrant(string $password)
    {
        try {
            if ( Adldap::auth()->attempt($this->username, $password, $bindAsUser = true) ) {
                if ( ! Hash::check($password, $this->password) ) {
                    $this->password = Hash::make( $password );
                    $this->save();
                    return Hash::check($password, $this->password);
                }
                return Hash::check($password, $this->password);
            }
        } catch (BindException $e) {
            return Hash::check($password, $this->password);
        }
    }

    /*
    * ---------------------------------------------------------
    * Password Reset Notification
    * ---------------------------------------------------------
    */

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification( $token )
    {
        $this->notify( new ResetPassword( $token, request()->get('email') ) );
    }

    /*
    * ---------------------------------------------------------
    * Eloquent Relations
    * ---------------------------------------------------------
    */

    /**
     *  User has profile
     *
     * @return HasOne
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
}
