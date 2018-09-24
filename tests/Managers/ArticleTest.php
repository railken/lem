<?php

namespace Railken\Lem\Tests\Managers;

use Railken\Lem\Support\Testing\TestableBaseTrait;
use Railken\Lem\Tests\App\Fakers\ArticleFaker;
use Railken\Lem\Tests\App\Managers\ArticleManager;
use Railken\Lem\Tests\BaseTest;

class ArticleTest extends BaseTest
{
    use TestableBaseTrait;

    /**
     * Manager class.
     *
     * @var string
     */
    protected $manager = ArticleManager::class;

    /**
     * Faker class.
     *
     * @var string
     */
    protected $faker = ArticleFaker::class;
}
