<?php


namespace App\Modules\Passport\src\Constants;


class Roles
{
    const IDENTIFIER = 'vital-passport';
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
        $abilities = collect($abilities)->filter(function ($item) {
            return false !== stristr($item->name, Roles::IDENTIFIER) || $item->name == '*';
        })->toArray();
        return [
            'id'    => auth('api')->user()->id,
            'abilities' => array_values($abilities),
            'roles' => array_values(
                collect(auth('api')->user()->roles)->filter(function ($item) {
                    return (false !== stristr($item->name, Roles::IDENTIFIER)) || (false !== stristr($item->name, 'superadmin'));
                })->toArray()
            ),
        ];
    }
}
