MtSimpleRbac
============

This module utilizes `Zend\Permissions\Rbac` in order to provide simple (static) access control.
Use it when your application defines only few roles, with basic access rules.
For anything more complex (roles and permissions stored in DB, multiple roles per user, etc.),
 try [https://github.com/ZF-Commons/ZfcRbac](ZfcRbac)

Installation
------------

Currently this module is just a prototype - not ready for Packagist. It can be installed by cloning this
repo to your "module" directory, and adding `MtSimpleRbac` to application.config.php file.

Configuration
-------------

```php
'mt_simple_rbac' => array(
    'authentication_service' => 'zfcuser_auth_service',
    'roles' => array(
        'guest',
        'member' => array(
            'parent' => 'guest',
            'permissions' => array(
                'dashboard_access'
            ),
        ),
        'admin' => array(
            'parent' => 'member',
            'permissions' => array(
                'admin_access'
            ),
        ),
    ),
),
```

Usage
-----

