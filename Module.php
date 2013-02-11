<?php

namespace MtSimpleRbac;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

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
                    // TODO: create separate factory class
                    $serviceLocator = $sm->getServiceLocator();
                    $rbac = $serviceLocator->get('Zend\Permissions\Rbac\Rbac');
                    $config = $serviceLocator->get('Config');
                    if (!isset($config['mt_simple_rbac'], $config['mt_simple_rbac']['authentication_service'])) {
                        throw new Exception("Unable to instantiate RBAC plugin: no authentication service is available.");
                    }
                    $authService = $serviceLocator->get($config['mt_simple_rbac']['authentication_service']);
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
                'Zend\Permissions\Rbac\Rbac' => function($sm) {
                    $config = $sm->get('Config');
                    return Rbac\Factory::factory(
                        isset($config['mt_simple_rbac'], $config['mt_simple_rbac']['roles'])
                            ? $config['mt_simple_rbac']['roles'] : array()
                    );
                }
            )
        );
    }
}
