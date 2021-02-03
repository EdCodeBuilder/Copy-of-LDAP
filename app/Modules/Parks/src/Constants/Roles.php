<?php


namespace App\Modules\Parks\src\Constants;


class Roles
{
    const ROLE_ADMIN = 'park-administrator';

    public static function roles()
    {
        return [
            self::ROLE_ADMIN,
        ];
    }

    public static function permissions()
    {
        return [
            ['name' => 'manage-parks',    'can' =>  auth('api')->user()->can('manage-parks')],
            ['name' => 'manage-parks-users',    'can' =>  auth('api')->user()->can('manage-parks-users')],
        ];
    }
}
