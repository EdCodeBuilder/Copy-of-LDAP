<?php


namespace App\Modules\Parks\src\Constants;


use App\Modules\Parks\src\Models\Park;
use Silber\Bouncer\Bouncer;
use Silber\Bouncer\Database\Role;

class Roles
{
    const ROLE_ADMIN = 'park-administrator';
    const ROLE_ASSIGNED = 'park-assigned-parks';

    public static function roles()
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_ASSIGNED,
        ];
    }

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
