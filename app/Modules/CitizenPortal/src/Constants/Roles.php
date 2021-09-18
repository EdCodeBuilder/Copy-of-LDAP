<?php


namespace App\Modules\CitizenPortal\src\Constants;


use Illuminate\Support\Facades\Cache;
use Silber\Bouncer\Database\Role;

class Roles
{
    const IDENTIFIER = 'citizen-portal';
    const ROLE_ADMIN = 'citizen-portal-admin';
    const ROLE_VIEWER = 'citizen-portal-viewer';
    const ROLE_ASSIGNOR = 'citizen-portal-assignor';
    const ROLE_VALIDATOR = 'citizen-portal-validator';
    const ROLE_TEST = 'citizen-portal-test';

    /**
     * @param  string|object  $class
     * @param  string  $action
     * @return string
     */
    public static function actions($class, $action)
    {
        $model = toLower(class_dash_name($class)).'-'.Roles::IDENTIFIER.','.$class;
        $model_management = toLower(class_dash_name($class)).'-'.Roles::IDENTIFIER.'$'.$class;
        $actions = [
            'manage'    => "can:manage-".$model,
            'view'    => "can:view-".$model,
            'view_or_manage' => "permission:view-$model_management|manage-$model_management|create-$model_management|update-$model_management|destroy-$model_management|assign-status-$model_management|assign-validator-$model_management",
            'create'    => "can:create-".$model,
            'create_or_manage' => "permission:create-$model_management|manage-$model_management",
            'update'    => "can:update-".$model,
            'update_or_manage' => "permission:update-$model_management|manage-$model_management",
            'destroy'   => "can:destroy-".$model,
            'destroy_or_manage' => "permission:destroy-$model_management|manage-$model_management",
            'history'   => "can:view-audit-".$model,
            'status'    => "can:assign-status-".$model,
            'validator'    => "can:assign-validator-".$model,
        ];
        return $actions[$action];
    }

    /**
     * @param  string|object  $class
     * @param  string  $action
     * @return string
     */
    public static function can($class, $action, $includeModel = false)
    {
        $model = $includeModel
            ? toLower(class_dash_name($class)).'-'.Roles::IDENTIFIER.'$'.$class
            : toLower(class_dash_name($class)).'-'.Roles::IDENTIFIER;
        $actions = [
            'manage'    => "manage-".$model,
            'view'    => "view-".$model,
            'view_or_manage' => "view-$model|manage-$model|create-$model|update-$model|destroy-$model",
            'create'    => "create-".$model,
            'create_or_manage' => "create-$model|manage-$model",
            'update'    => "update-".$model,
            'update_or_manage' => "update-$model|manage-$model",
            'destroy'   => "destroy-".$model,
            'destroy_or_manage' => "destroy-$model|manage-$model",
            'history'   => "view-audit-".$model,
            'status'    => "assign-status-".$model,
            'validator'    => "assign-validator-".$model,
        ];
        return $actions[$action];
    }

    public static function canAny(array $actions, $withSuffix = true, $includeModel = false)
    {
        $result = $withSuffix ? "permission:" : '';
        foreach ($actions as $key => $action) {
            $pipe = $key > 0 ? '|' : '';
            if (is_array($action['actions'])) {
                foreach ($action['actions'] as $index => $value) {
                    $pip = $key == 0 && $index > 0 ? '|' : $pipe;
                    $result .= $pip.self::can($action['model'], $value, $includeModel);
                }
            } else {
                $result .= $pipe.self::can($action['model'], $action['actions'], $includeModel);
            }
        }
        return $result;
    }

    public static function authCan(array $actions, $class, $flag = 'and')
    {
        $status = collect();
        foreach ($actions as $action) {
            $status->push(
                [
                    'can' => auth('api')->check() && auth('api')->user()->can( self::can($class, $action), $class )
                ]
            );
        }
        if ($flag == 'and') {
            return count($actions) == $status->where('can', true)->count();
        }
        if ($flag == 'or') {
            return $status->where('can', true)->count() > 0;
        }
        return false;
    }

    public static function authCanMany(array $actions)
    {
        $result = collect();
        foreach ($actions as $action) {
            $result->push([
                'can' => self::authCan($action['actions'], $action['model'], 'or')
            ]);
        }
        return $result->where('can', true)->count() > 0;
    }

    /**
     * @return string[]
     */
    public static function all()
    {
        $name = 'citizen-roles-user'.random_img_name();
        return Cache::remember($name, 35, function () {
            return Role::where('name', 'like', self::IDENTIFIER.'-%')
                ->get()
                ->pluck('name')
                ->toArray();
        });
    }

    /**
     * @return string[]
     */
    public static function allAndRoot()
    {
        return array_merge(Roles::all(), ['superadmin']);
    }

    /**
     * @return string[]
     */
    public static function keyed()
    {
        $roles = Roles::all();
        $keys = [];
        foreach($roles as $role) {
            $keys[$role]=$role;
        }
        return $keys;
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
                    return (false !== stristr($item->name, Roles::IDENTIFIER)) || (false !== stristr($item->name, 'superadmin'));
                })->toArray()
            ),
        ];
    }
}
