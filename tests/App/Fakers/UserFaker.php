<?php

namespace Railken\Lem\Tests\App\Fakers;

use Faker\Factory;
use Railken\Bag;
use Railken\Lem\Faker;

class UserFaker extends Faker
{
    /**
     * @return \Railken\Bag
     */
    public function parameters()
    {
        $faker = Factory::create();

        $bag = new Bag();
        $bag->set('username', $faker->name);
        $bag->set('email', $faker->email);
        $bag->set('password', $faker->name);

        return $bag;
    }
}
