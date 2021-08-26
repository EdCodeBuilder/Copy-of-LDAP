<?php


namespace App\Modules\CitizenPortal\src\Constants;


class Roles
{
    const IDENTIFIER = 'citizen-portal';
    const ROLE_ADMIN = 'citizen-portal-admin';
    const ROLE_VIEWER = 'citizen-portal-viewer';
    const ROLE_ASSIGNOR = 'citizen-portal-assignor';
    const ROLE_VALIDATOR = 'citizen-portal-validator';

    /**
     * @param  string|object  $class
     * @param  string  $action
     * @return string
     */
    public static function actions($class, $action)
    {
        $actions = [
            'create'    => "can:create-".toLower(class_dash_name($class)).'-'.Roles::IDENTIFIER.','.$class,
            'update'    => "can:update-".toLower(class_dash_name($class)).'-'.Roles::IDENTIFIER.','.$class,
            'destroy'   => "can:destroy-".toLower(class_dash_name($class)).'-'.Roles::IDENTIFIER.','.$class,
            'history'   => "can:view-audit-".toLower(class_dash_name($class)).'-'.Roles::IDENTIFIER.','.$class,
            'status'    => "can:assign-status-".toLower(class_dash_name($class)).'-'.Roles::IDENTIFIER.','.$class,
            'validator'    => "can:assign-validator-".toLower(class_dash_name($class)).'-'.Roles::IDENTIFIER.','.$class,
        ];
        return $actions[$action];
    }

    /**
     * @param  string|object  $class
     * @param  string  $action
     * @return string
     */
    public static function can($class, $action)
    {
        $actions = [
            'create'    => "create-".toLower(class_dash_name($class)).'-'.Roles::IDENTIFIER,
            'update'    => "update-".toLower(class_dash_name($class)).'-'.Roles::IDENTIFIER,
            'destroy'   => "destroy-".toLower(class_dash_name($class)).'-'.Roles::IDENTIFIER,
            'history'   => "view-audit-".toLower(class_dash_name($class)).'-'.Roles::IDENTIFIER,
            'status'    => "assign-status-".toLower(class_dash_name($class)).'-'.Roles::IDENTIFIER,
            'validator'    => "assign-validator-".toLower(class_dash_name($class)).'-'.Roles::IDENTIFIER,
        ];
        return $actions[$action];
    }

    /**
     * @return string[]
     */
    public static function all()
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_ASSIGNOR,
            self::ROLE_VALIDATOR,
            self::ROLE_VIEWER,
        ];
    }

    /**
     * @return string[]
     */
    public static function allAndRoot()
    {
        return [
            'superadmin',
            self::ROLE_ADMIN,
            self::ROLE_ASSIGNOR,
            self::ROLE_VALIDATOR,
            self::ROLE_VIEWER,
        ];
    }

    /**
     * @return string[]
     */
    public static function keyed()
    {
        return [
            self::ROLE_ADMIN    => self::ROLE_ADMIN,
            self::ROLE_ASSIGNOR      => self::ROLE_ASSIGNOR,
            self::ROLE_VALIDATOR      => self::ROLE_VALIDATOR,
            self::ROLE_VIEWER      => self::ROLE_VIEWER,
        ];
    }

    public static function find($role)
    {
        return self::keyed()[$role] ?? null;
    }

    public static function adminAnd($role)
    {
        $roles = [
            'superadmin',
            Roles::ROLE_ADMIN,
        ];
        if (is_array($role)) {
            foreach ($role as $value) {
                $find = self::keyed()[$role] ?? null;
                if ($find) {
                    array_push($roles, $find);
                }
            }
        } else {
            $find = self::keyed()[$role] ?? null;
            if ($find) {
                array_push($roles, $find);
            }
        }
        return $roles;
    }

    public static function onlyAdmin()
    {
        return [
            'superadmin',
            Roles::ROLE_ADMIN,
        ];
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
                    return (false !== stristr($item->name, Roles::IDENTIFIER)) ||
                        (false !== stristr($item->name, 'superadmin'));
                })->toArray()
            ),
        ];
    }
}
