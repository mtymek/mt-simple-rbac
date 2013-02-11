<?php

namespace MtSimpleRbac\Controller\Plugin;

use Zend\Permissions\Rbac\Rbac as ZendRbac;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Authentication\AuthenticationService;
use MtSimpleRbac\Exception\AccessDeniedException;

class Rbac extends AbstractPlugin
{

    /**
     * @var \Zend\Permissions\Rbac\Rbac
     */
    protected $rbac;

    /**
     * @var AuthenticationService
     */
    protected $authenticationService;

    public function __construct(ZendRbac $rbac, AuthenticationService $auth)
    {
        $this->rbac = $rbac;
        $this->authenticationService = $auth;
    }

    /**
     * @param ZendRbac $rbac
     */
    public function setRbac(ZendRbac $rbac)
    {
        $this->rbac = $rbac;
    }

    /**
     * @return \Zend\Permissions\Rbac\Rbac
     */
    public function getRbac()
    {
        return $this->rbac;
    }

    /**
     * @param \Zend\Authentication\AuthenticationService $authenticationService
     */
    public function setAuthenticationService($authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    /**
     * @return \Zend\Authentication\AuthenticationService
     */
    public function getAuthenticationService()
    {
        return $this->authenticationService;
    }

    public function checkAccess($permission, $assert = null)
    {
        if (!$this->getAuthenticationService()->hasIdentity()) {
            $role = 'guest';
        } else {
            $role = $this->getAuthenticationService()->getIdentity()->getRole();
        }

        if (!$this->rbac->isGranted($role, $permission, $assert)) {
            throw new AccessDeniedException("Role '$role' has no permission to '$permission'.");
        }
    }
}