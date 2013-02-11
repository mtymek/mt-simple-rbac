<?php

namespace MtSimpleRbac\Identity;

interface RoleProviderInterface
{
    const ROLE_GUEST = 'guest';

    /**
     * Return name of identity role
     * @return string
     */
    public function getRole();

}