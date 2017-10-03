<?php

namespace Railken\Laravel\Manager\Tests\User\Attributes;

class UserUsernameAttribute
{
    protected $name = 'username';

    protected $aliases = ['username'];

    protected $required = false;

    public function validate($value)
    {
        return strlen($value) >= 3 && strlen($value) < 32;
    }

    public function setValue(AgentContract $agent) 
    {

        return $agent->isRoleAdmin() || ($agent->isRoleUser() && $agent->id == $entity->id);
        
        return $value;
    }

    public function getValue($value)
    {
        return $value;
    }
}
