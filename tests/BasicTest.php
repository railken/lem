<?php

namespace Railken\Laravel\Manager\Tests;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Schema\Blueprint;

use Railken\Laravel\Manager\Generator;
use Railken\Laravel\Manager\Tests\User\User;
use Railken\Laravel\Manager\Tests\User\UserManager;
use Railken\Laravel\Manager\Tests\User\UserServiceProvider;
use Railken\Bag;
use Illuminate\Support\Facades\File;

class BasicTest extends \Orchestra\Testbench\TestCase
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
            \Railken\Laravel\Manager\ManagerServiceProvider::class,
            UserServiceProvider::class,
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

        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->nullable();
            $table->string('role')->default(User::ROLE_USER);
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::dropIfExists('foo');

        Schema::create('foo', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Return a new instance of user bag
     *
     * @return Bag
     */
    public function getUserBag()
    {
        return ['email' => 'test@test.net', 'username' => 'test123', 'password' => microtime()];
    }

    /**
     * Test generate Command
     */
    public function testGenerate()
    {

        $generator = new Generator();
        $generator->generate(__DIR__."/Generated", "Railken\Laravel\Manager\Tests\Generated\Foo");
        $this->assertEquals(true, File::exists(__DIR__."/Generated/Foo"));


        \Railken\Laravel\Manager\Tests\Generated\Foo\Foo::observe(\Railken\Laravel\Manager\Tests\Generated\Foo\FooObserver::class);
        Gate::policy(\Railken\Laravel\Manager\Tests\Generated\Foo\Foo::class, \Railken\Laravel\Manager\Tests\Generated\Foo\FooPolicy::class);


        $m = new \Railken\Laravel\Manager\Tests\Generated\Foo\FooManager();

        $um = new UserManager();
        $user = $um->create($um->parameters($this->getUserBag()))->getResource();

        $bag = new \Railken\Laravel\Manager\Tests\Generated\Foo\FooParameterBag(['name' => 'a']);
        $m->setAgent($user);
        $this->assertEquals("FOO_NAME_NOT_VALID", $m->create($bag->set('name', '2'))->getError()->getCode());
        $this->assertEquals(true, $m->create($bag->set('name', null))->ok());

        $um->remove($user);
    }

    /**
     * Test basics
     */
    public function testBasics()
    {
        $um = new UserManager();

        # Testing validation
        $this->assertEquals("USER_USERNAME_NOT_DEFINED", $um->create($um->parameters($this->getUserBag())->remove('username'))->getError()->getCode());
        $this->assertEquals("USER_USERNAME_NOT_VALID", $um->create($um->parameters($this->getUserBag())->set('username', 'wr'))->getError()->getCode());
        $this->assertEquals("USER_PASSWORD_NOT_DEFINED", $um->create($um->parameters($this->getUserBag())->remove('password'))->getError()->getCode());
        $this->assertEquals("USER_PASSWORD_NOT_VALID", $um->create($um->parameters($this->getUserBag())->set('password', 'wrong'))->getError()->getCode());
        $this->assertEquals("USER_EMAIL_NOT_DEFINED", $um->create($um->parameters($this->getUserBag())->remove('email'))->getError()->getCode());
        $this->assertEquals("USER_EMAIL_NOT_VALID", $um->create($um->parameters($this->getUserBag())->set('email', 'wrong'))->getError()->getCode());

        # Testing correct
        $resource = $um->create($um->parameters($this->getUserBag()))->getResource();
        $this->assertEquals($um->parameters($this->getUserBag())->get('username'), $resource->username);

        # Testing uniqueness
        $this->assertEquals("USER_EMAIL_NOT_UNIQUE", $um->create($um->parameters($this->getUserBag()))->getErrors()->first()->getCode());

        $um->update($resource, $um->parameters($this->getUserBag()));
        $um->remove($resource);


        # An admin can change username/email/password of all users
        # An user can change only his own information

        $user_admin = $um->create($um->parameters($this->getUserBag())->set('role', User::ROLE_ADMIN)->set('email', 'admin@test.net'))->getResource();
        $user_admin_manager = new UserManager($user_admin);

        $user = $um->create($um->parameters($this->getUserBag())->set('role', User::ROLE_USER)->set('email', 'user@test.net'))->getResource();
        $user_manager = new UserManager($user);




        $this->assertEquals(false, $user_manager->update($user_admin, $um->parameters(['email' => 'new@test.net']))->isAuthorized());
        $this->assertEquals(true, $user_manager->update($user, $um->parameters(['email' => 'new@test.net']))->isAuthorized());

        $this->assertEquals(true, $user_admin_manager->update($user_admin, $um->parameters(['email' => 'new2@test.net']))->ok());
        $this->assertEquals(true, $user_admin_manager->update($user, $um->parameters(['email' => 'new3@test.net']))->ok());

        $this->assertEquals(true, $user_manager->update($user, $um->parameters(['role' => User::ROLE_ADMIN]))->getResource()->isRoleUser());

        $um->findOneBy($um->parameters(['username' => 'test123']));
    }
}
