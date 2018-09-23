<?php

namespace Railken\Lem\Concerns;

use Railken\Lem\Exceptions;

trait HasPermissions
{
    /**
     * Retrieve a permission name given code.
     *
     * @param string $code
     *
     * @return string
     */
    public function getPermission($code)
    {
        if (!isset($this->permissions[$code])) {
            throw new Exceptions\PermissionNotDefinedException($this, $code);
        }

        return $this->permissions[$code];
    }

    /**
     * Retrieve all permissions.
     *
     * @return array
     */
    public function getPermissions()
    {
        return $this->permissions;
    }
}
