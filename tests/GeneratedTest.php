<?php

namespace Railken\Lem\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Railken\Bag;
use Railken\Lem\Generator;
use Railken\Lem\Tests\App\Managers\FooManager;
use Railken\Lem\Tests\App\Models\User;
use Railken\Lem\Tests\App\Managers\UserManager;

class GeneratedTest extends BaseTest
{
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $dotenv = new \Dotenv\Dotenv(__DIR__.'/..', '.env');
        $dotenv->load();

        parent::setUp();

        Schema::dropIfExists('foo');

        Schema::create('foo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

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

        $generator->generate(__DIR__.'/App', "Railken\Lem\Tests\App");

        $this->assertEquals(true, File::exists(__DIR__.'/App/Models/Foo'));
        $this->assertEquals(true, File::exists(__DIR__.'/App/Repositories/FooRepository'));
        $this->assertEquals(true, File::exists(__DIR__.'/App/Managers/FooManager'));
        $this->assertEquals(true, File::exists(__DIR__.'/App/Authorizers/FooAuthorizer'));
        $this->assertEquals(true, File::exists(__DIR__.'/App/Serializers/FooSerializer'));

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
