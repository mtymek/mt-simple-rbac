<?php

namespace MtSimpleRbac;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Zend\Permissions\Rbac\Rbac;
use Zend\Permissions\Rbac\Role;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $events = $e->getApplication()->getEventManager();
        $strategy = new View\AccessDeniedStrategy();
        $events->attach($strategy);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getControllerPluginConfig()
    {
        return array(
            'factories' => array(
                'rbac' => function ($sm) {
                    $serviceLocator = $sm->getServiceLocator();
                    $rbac = $serviceLocator->get('Zend\Permissions\Rbac\Rbac');
                    $authService = $serviceLocator->get('zfcuser_auth_service');
                    $plugin = new Controller\Plugin\Rbac($rbac, $authService);
                    return $plugin;
                },
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Zend\Permissions\Rbac\Rbac' => function() {
                    $rbac = new Rbac();

                    $guest = new Role('guest');
                    $member = new Role('member');
                    $member->addPermission('member_access');
                    $admin = new Role('admin');
                    $admin->addPermission('admin_access');

                    $rbac->addRole($guest);
                    $rbac->addRole($member, 'guest');
                    $rbac->addRole($admin, 'member');

                    return $rbac;
                }
            )
        );
    }
}
