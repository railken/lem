<?php

namespace Railken\Laravel\Manager\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Railken\Bag;
use Railken\Laravel\Manager\Agents\SystemAgent;
use Railken\Laravel\Manager\Tests\Core\Article\ArticleManager;
use Railken\Laravel\Manager\Tests\Core\Article\ArticleServiceProvider;
use Railken\Laravel\Manager\Tests\Core\Comment\CommentManager;
use Railken\Laravel\Manager\Tests\Core\Comment\CommentServiceProvider;
use Railken\Laravel\Manager\Tests\User\User;
use Railken\Laravel\Manager\Tests\User\UserManager;
use Railken\Laravel\Manager\Tests\User\UserServiceProvider;

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
            ArticleServiceProvider::class,
            CommentServiceProvider::class,
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

        Schema::dropIfExists('comments');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('users');
        Schema::dropIfExists('foo');

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

        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('notes')->nullable();
            $table->integer('author_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('author_id')->references('id')->on('users');
        });



        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('content');
            $table->integer('author_id')->unsigned();
            $table->integer('article_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('author_id')->references('id')->on('users');
            $table->foreign('article_id')->references('id')->on('users');
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
    public function testPermission()
    {
        $um = new UserManager();
        $user_1 = $um->create(['email' => 'test1@test.net', 'username' => 'test1', 'password' => microtime()])->getResource();
        $user_2 = $um->create(['email' => 'test2@test.net', 'username' => 'test2', 'password' => microtime()])->getResource();
        // $generator = new Generator();
        $am = new ArticleManager();
        $cm = new CommentManager();

        $ab = ['title' => 'foo', 'description' => 'bar'];

        $this->assertEquals(1, $am->setAgent($user_1)->create($ab)->ok());
        $this->assertEquals(1, $am->setAgent($user_2)->create($ab)->ok());

        $this->assertEquals('ARTICLE_NOT_AUTHORIZED', $am->setAgent($user_2)->update($am->findOneBy(['author_id' => $user_1->id]), ['title' => 'ban'])->getError()->getCode());
        $this->assertEquals('ARTICLE_NOT_AUTHORIZED', $am->setAgent($user_1)->update($am->findOneBy(['author_id' => $user_2->id]), ['title' => 'ban'])->getError()->getCode());

        $this->assertEquals(1, $am->setAgent(new SystemAgent())->create(['title' => 'bar', 'description' => 'bar', 'author_id' => '1'])->ok());
        $this->assertEquals('ARTICLE_AUTHOR_NOT_VALID', $am->setAgent(new SystemAgent())->create(['title' => 'bar', 'description' => 'bar', 'author_id' => '1111'])->getError()->getCode());

        // Creating a new comment
        $cb = new Bag(['article_id' => 1, 'content' => 'foo']);
        $this->assertEquals(1, $cm->setAgent(new SystemAgent())->create($cb->set('author_id', 1))->ok());
        $this->assertEquals(1, $cm->setAgent($user_1)->create($cb)->ok());
        $this->assertEquals(1, $cm->setAgent($user_2)->create($cb)->ok());


        // Creating a new comment
        $cb = new Bag(['article_id' => 1, 'content' => 'foo']);
        $this->assertEquals(1, $cm->setAgent(new SystemAgent())->create($cb->set('author_id', 1))->ok());
        $this->assertEquals(1, $cm->setAgent($user_1)->create($cb)->ok());
        $this->assertEquals(1, $cm->setAgent($user_2)->create($cb)->ok());
    }

    /**
     * Test basics
     */
    public function testBasics()
    {
        $um = new UserManager();

        # Testing validation
        $this->assertEquals("USER_USERNAME_NOT_DEFINED", $um->create($this->getUserBag()->remove('username'))->getError()->getCode());
        $this->assertEquals("USER_USERNAME_NOT_VALID", $um->create($this->getUserBag()->set('username', 'wr'))->getError()->getCode());
        $this->assertEquals("USER_PASSWORD_NOT_DEFINED", $um->create($this->getUserBag()->remove('password'))->getError()->getCode());
        $this->assertEquals("USER_PASSWORD_NOT_VALID", $um->create($this->getUserBag()->set('password', 'wrong'))->getError()->getCode());
        $this->assertEquals("USER_EMAIL_NOT_DEFINED", $um->create($this->getUserBag()->remove('email'))->getError()->getCode());
        $this->assertEquals("USER_EMAIL_NOT_VALID", $um->create($this->getUserBag()->set('email', 'wrong'))->getError()->getCode());

        # Testing correct
        $resource = $um->create($this->getUserBag())->getResource();
        $this->assertEquals($this->getUserBag()->get('username'), $resource->username);

        # Testing uniqueness
        $this->assertEquals("USER_EMAIL_NOT_UNIQUE", $um->create($this->getUserBag())->getErrors()->first()->getCode());

        $um->update($resource, $this->getUserBag());
        $um->remove($resource);


        # An admin can change username/email/password of all users
        # An user can change only his own information

        $user_admin = $um->create($this->getUserBag()->set('role', User::ROLE_ADMIN)->set('email', 'admin@test.net'))->getResource();
        $user_admin_manager = new UserManager($user_admin);

        $user = $um->create($this->getUserBag()->set('role', User::ROLE_USER)->set('email', 'user@test.net'))->getResource();
        $user_manager = new UserManager($user);




        $this->assertEquals(false, $user_manager->update($user_admin, ['email' => 'new@test.net'])->isAuthorized());
        $this->assertEquals(true, $user_manager->update($user, ['email' => 'new@test.net'])->isAuthorized());

        $this->assertEquals(true, $user_admin_manager->update($user_admin, ['email' => 'new2@test.net'])->ok());
        $this->assertEquals(true, $user_admin_manager->update($user, ['email' => 'new3@test.net'])->ok());

        $this->assertEquals(true, $user_manager->update($user, ['role' => User::ROLE_ADMIN])->getResource()->isRoleUser());

        $um->findOneBy(['username' => 'test123']);
    }
}
