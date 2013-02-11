<?php

namespace MtSimpleRbac\Rbac;

use Zend\Permissions\Rbac\Rbac;
use Zend\Permissions\Rbac\Role;

/**
 * TODO: refactor to make it ServiceManager friendly
 */
class Factory
{
    public static function factory($config)
    {
        $rbac = new Rbac();

        if ($config == null) {
            return $rbac;
        }

        foreach ($config as $key => $role) {

            if (is_string($role)) {
                $roleName = $role;
                $parent = null;
                $permissions = null;
            } elseif (is_array($role)) {

                $roleName = $key;
                if (isset($role['parent'])) {
                    $parent = $role['parent'];
                }
                if (isset($role['permissions'])) {
                    $permissions = $role['permissions'];
                }
            } else {
                throw new Exception("Invalid role configuration: role must be string or array.");
            }

            $role = new Role($roleName);
            if ($permissions) {
                foreach ($permissions as $permission) {
                    $role->addPermission($permission);
                }
            }
            $rbac->addRole($role, $parent);
        }
        return $rbac;
    }
}

