<?php


namespace App\Modules\Contractors\src\Constants;


class Roles
{
    const ROLE_ADMIN = 'contractors-portal-super-admin';
    const ROLE_ARL = 'contractors-portal-arl';
    const ROLE_HIRING = 'contractors-portal-hiring';
    const ROLE_LEGAL = 'contractors-portal-legal';
    const ROLE_RP = 'contractors-portal-rp';
    const ROLE_THIRD_PARTY = 'contractors-portal-third-party';
    const ROLE_OBSERVER = 'contractors-portal-observer';

    /**
     * @return string[]
     */
    public static function all()
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_ARL,
            self::ROLE_HIRING,
            self::ROLE_LEGAL,
            self::ROLE_RP,
            self::ROLE_OBSERVER,
            self::ROLE_THIRD_PARTY,
        ];
    }

    /**
     * @return string[]
     */
    public static function keyed()
    {
        return [
            self::ROLE_ADMIN    => self::ROLE_ADMIN,
            self::ROLE_ARL      => self::ROLE_ARL,
            self::ROLE_HIRING   => self::ROLE_HIRING,
            self::ROLE_LEGAL    => self::ROLE_LEGAL,
            self::ROLE_RP       => self::ROLE_RP,
            self::ROLE_OBSERVER => self::ROLE_OBSERVER,
            self::ROLE_THIRD_PARTY => self::ROLE_THIRD_PARTY,
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
            Roles::ROLE_ADMIN
        ];
        return $find ? array_push($roles, [$find]) : $roles;
    }
}
