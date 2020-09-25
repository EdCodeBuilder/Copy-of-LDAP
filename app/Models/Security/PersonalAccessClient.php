<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Model;

class PersonalAccessClient extends \Laravel\Passport\PersonalAccessClient
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = "mysql_ldap";
}
