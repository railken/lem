<?php

namespace Railken\Laravel\Manager\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Railken\Bag;
use Railken\Laravel\Manager\Generator;
use Railken\Laravel\Manager\Tests\Generated\Foo\FooManager;
use Railken\Laravel\Manager\Tests\Generated\Foo\FooParameterBag;
use Railken\Laravel\Manager\Tests\Generated\Foo\FooServiceProvider;
use Railken\Laravel\Manager\Tests\User\User;

class GeneratedTest extends \Orchestra\Testbench\TestCase
{
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
    }

    protected function getPackageProviders($app)
    {
        return [
            \Railken\Laravel\Manager\ManagerServiceProvider::class
        ];
    }

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $dotenv = new \Dotenv\Dotenv(__DIR__."/..", '.env');
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
     * Return a new instance of user bag
     *
     * @return Bag
     */
    public function getUserBag()
    {
        return new Bag(['email' => 'test@test.net', 'username' => 'test123', 'password' => microtime()]);
    }

    /**
     * Test generate Command
     */
    public function testGenerate()
    {
        $generator = new Generator();

        if (!File::exists(__DIR__."/Generated/Foo")) {
            $generator->generate(__DIR__."/Generated", "Railken\Laravel\Manager\Tests\Generated\Foo");
        }


        $this->assertEquals(true, File::exists(__DIR__."/Generated/Foo"));
        (new FooServiceProvider($this->app))->register();

        $user = new User();
        $m = new FooManager($user);

        $bag = new FooParameterBag(['name' => 'ban']);

        $this->assertEquals("FOO_NOT_AUTHORIZED", $m->create($bag)->getError()->getCode());


        $user->addPermission('foo.*');


        $foo = $m->create($bag->set('name', 'baar'))->getResource();
        $m->update($foo, $bag->set('name', 'fee'))->getResource();

        $foo_s = $m->findOneBy(['name' => 'fee']);

        $this->assertEquals($foo->id, $foo_s->id);
    }
}
