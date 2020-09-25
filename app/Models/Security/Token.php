<?php

namespace App\Models\Security;


class Token extends \Laravel\Passport\Token
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = "mysql_ldap";
}
