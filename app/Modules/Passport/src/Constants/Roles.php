<?php


namespace App\Modules\Passport\src\Constants;


class Roles
{
    const ROLE_SUPER_ADMIN = 'vital-passport-super-admin';
    const ROLE_ADMIN = 'vital-passport-admin';

    /**
     * @return string[]
     */
    public static function all()
    {
        return [
            self::ROLE_SUPER_ADMIN,
            self::ROLE_ADMIN,
        ];
    }

    /**
     * @return string[]
     */
    public static function keyed()
    {
        return [
            self::ROLE_ADMIN    => self::ROLE_ADMIN,
            self::ROLE_SUPER_ADMIN      => self::ROLE_SUPER_ADMIN,
        ];
    }

    public static function find($role)
    {
        return isset(self::keyed()[$role]) ? self::keyed()[$role] : null;
    }

    public static function adminAnd($role)
    {
        $find = isset(self::keyed()[$role]) ? self::keyed()[$role] : null;
        $roles = [
            Roles::ROLE_ADMIN,
            Roles::ROLE_SUPER_ADMIN,
        ];
        return $find ? array_push($roles, [$find]) : $roles;
    }

    /**
     * @return array
     */
    public static function permissions()
    {
        $abilities = auth('api')->user()->getAbilities()->merge(auth('api')->user()->getForbiddenAbilities());
        $abilities->each(function ($ability) {
            $ability->forbidden = auth('api')->user()->getForbiddenAbilities()->contains($ability);
        });
        return [
            'id'    => auth('api')->user()->id,
            'abilities' => $abilities,
            'roles' => auth('api')->user()->roles,
        ];
    }
}
