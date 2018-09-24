<?php

namespace Railken\Lem\Tests\App\Fakers;

use Faker\Factory;
use Railken\Bag;
use Railken\Lem\Faker;

class ArticleFaker extends Faker
{
    /**
     * @return \Railken\Bag
     */
    public function parameters()
    {
        $faker = Factory::create();

        $bag = new Bag();
        $bag->set('title', $faker->title);
        $bag->set('author', UserFaker::make()->parameters()->toArray());

        return $bag;
    }
}
