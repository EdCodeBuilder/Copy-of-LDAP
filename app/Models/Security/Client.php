<?php

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Model;

class Client extends \Laravel\Passport\Client
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = "mysql_ldap";
}
