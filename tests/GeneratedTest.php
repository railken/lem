<?php

namespace Railken\Lem\Tests;

use Illuminate\Support\Facades\File;
use Railken\Bag;
use Railken\Lem\Generator;
use Railken\Lem\Tests\App\Managers\FooManager;
use Railken\Lem\Tests\App\Models\User;

class GeneratedTest extends BaseTest
{
    /**
     * Return a new instance of user bag.
     *
     * @return Bag
     */
    public function getUserBag()
    {
        return new Bag(['email' => 'test@test.net', 'username' => 'test123', 'password' => microtime()]);
    }

    /**
     * Test generate Command.
     */
    public function testGenerate()
    {
        $generator = new Generator();

        $generator->generate(__DIR__.'/App', "Railken\Lem\Tests\App", 'Foo');

        $this->assertEquals(true, File::exists(__DIR__.'/App/Models/Foo.php'));
        $this->assertEquals(true, File::exists(__DIR__.'/App/Repositories/FooRepository.php'));
        $this->assertEquals(true, File::exists(__DIR__.'/App/Managers/FooManager.php'));
        $this->assertEquals(true, File::exists(__DIR__.'/App/Authorizers/FooAuthorizer.php'));
        $this->assertEquals(true, File::exists(__DIR__.'/App/Serializers/FooSerializer.php'));

        $user = new User();
        $m = new FooManager($user);

        $bag = new Bag(['name' => 'ban']);

        $this->assertEquals('FOO_NOT_AUTHORIZED', $m->create($bag)->getError()->getCode());

        $user->addPermission('foo.*');

        $foo = $m->create($bag->set('name', 'baar'))->getResource();
        $m->update($foo, $bag->set('name', 'fee'))->getResource();

        $foo_s = $m->getRepository()->findOneBy(['name' => 'fee']);

        $this->assertEquals(true, $m->findOrCreate(['name' => 'test'])->ok());
        $this->assertEquals(true, $m->findOrCreate(['name' => 'test'])->ok());

        $this->assertEquals($foo->id, $foo_s->id);
    }
}
