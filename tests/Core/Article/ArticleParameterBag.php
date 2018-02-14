<?php

namespace Railken\Laravel\Manager\Tests\Core\Article;

use Railken\Laravel\Manager\Contracts\ManagerContract;
use Railken\Laravel\Manager\ParameterBag;


use Railken\Laravel\Manager\Tests\User\UserManager;
use Railken\Laravel\Manager\Tests\User\User;

class ArticleParameterBag extends ParameterBag
{

    /**
     * Filter current bag
     *
     * @return $this
     */
    public function filterWrite()
    {
        
        $this->exists('author_id') && $this->set('author', (new UserManager(new User()))->findOneBy(['id' => $this->get('author_id')]));


        # GuestAgentContract not allowed.

        return $this;
    }
}
