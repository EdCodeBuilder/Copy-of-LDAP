<?php

namespace App\Models\Security;

class AuthCode extends \Laravel\Passport\AuthCode
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = "mysql_ldap";
}
