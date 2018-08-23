<?php

namespace Railken\Laravel\Manager\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Railken\Bag;
use Railken\Laravel\Manager\Tests\Core\Article\ArticleManager;
use Railken\Laravel\Manager\Tests\Core\Article\ArticleServiceProvider;
use Railken\Laravel\Manager\Tests\User\User;
use Railken\Laravel\Manager\Tests\User\UserManager;
use Railken\Laravel\Manager\Tests\User\UserServiceProvider;

class BasicTest extends \Orchestra\Testbench\TestCase
{
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $dotenv = new \Dotenv\Dotenv(__DIR__.'/..', '.env');
        $dotenv->load();

        parent::setUp();

        Schema::dropIfExists('comments');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('users');
        Schema::dropIfExists('foo');

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->nullable();
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
     * Test basics.
     */
    public function testBasics()
    {
        $um = new UserManager();

        // Testing validation
        $this->assertEquals('USER_USERNAME_NOT_DEFINED', $um->create($this->getUserBag()->remove('username'))->getError()->getCode());
        $this->assertEquals('USER_USERNAME_NOT_VALID', $um->create($this->getUserBag()->set('username', 'wr'))->getError()->getCode());
        $this->assertEquals('USER_PASSWORD_NOT_DEFINED', $um->create($this->getUserBag()->remove('password'))->getError()->getCode());
        $this->assertEquals('USER_PASSWORD_NOT_VALID', $um->create($this->getUserBag()->set('password', 'wrong'))->getError()->getCode());
        $this->assertEquals('USER_EMAIL_NOT_DEFINED', $um->create($this->getUserBag()->remove('email'))->getError()->getCode());
        $this->assertEquals('USER_EMAIL_NOT_VALID', $um->create($this->getUserBag()->set('email', 'wrong'))->getError()->getCode());

        // Testing correct
        $resource = $um->create($this->getUserBag())->getResource();
        $this->assertEquals($this->getUserBag()->get('username'), $resource->username);

        // Testing uniqueness
        $this->assertEquals('USER_EMAIL_NOT_UNIQUE', $um->create($this->getUserBag())->getErrors()->first()->getCode());

        $um->update($resource, $this->getUserBag());

        $this->assertEquals('test123', $um->getRepository()->findOneBy(['username' => 'test123'])->username);
        $this->assertEquals(1, count($um->getRepository()->findBy(['username' => 'test123'])));
        $this->assertEquals(1, count($um->getRepository()->findWhereIn(['username' => ['test123']])));

        $um->remove($resource);
    }

    /**
     * @expectedException \Railken\Laravel\Manager\Exceptions\Exception
     */
    public function testCreateOrFail()
    {
        $um = new UserManager();
        $um->createOrFail($this->getUserBag()->remove('username'));
    }

    /**
     * @expectedException \Railken\Laravel\Manager\Exceptions\Exception
     */
    public function testUpdateOrFail()
    {
        $um = new UserManager();
        $user = $um->create($this->getUserBag())->getResource();
        $um->updateOrFail($user, $this->getUserBag()->set('username', '1'));
    }

    /**
     * Test.
     */
    public function testArticles()
    {
        $um = new UserManager();
        $user = $um->create(['email' => 'test1@test.net', 'username' => 'test1', 'password' => microtime()])->getResource();

        // $generator = new Generator();

        $am = new ArticleManager($user);

        $ab = ['title' => 'foo', 'description' => 'bar', 'author_id' => $user->id];

        $this->assertEquals(8, $am->create($ab)->getErrors()->count());
        $this->assertEquals('ARTICLE_NOT_AUTHORIZED', $am->create($ab)->getError(0)->getCode());
        $this->assertEquals('ARTICLE_ID_NOT_AUTHTORIZED', $am->create($ab)->getError(1)->getCode());
        $this->assertEquals('ARTICLE_TITLE_NOT_AUTHTORIZED', $am->create($ab)->getError(2)->getCode());
        $this->assertEquals('ARTICLE_DESCRIPTION_NOT_AUTHTORIZED', $am->create($ab)->getError(3)->getCode());
        $this->assertEquals('ARTICLE_AUTHOR_ID_NOT_AUTHTORIZED', $am->create($ab)->getError(4)->getCode());
        $this->assertEquals('ARTICLE_CREATED_AT_NOT_AUTHTORIZED', $am->create($ab)->getError(5)->getCode());
        $this->assertEquals('ARTICLE_UPDATED_AT_NOT_AUTHTORIZED', $am->create($ab)->getError(6)->getCode());
        $this->assertEquals('ARTICLE_DELETED_AT_NOT_AUTHTORIZED', $am->create($ab)->getError(7)->getCode());

        $user->addPermission('article.create');
        $user->addPermission('article.attributes.id.*');
        $user->addPermission('article.attributes.title.*');
        $user->addPermission('article.attributes.description.*');
        $user->addPermission('article.attributes.author_id.*');
        $user->addPermission('article.attributes.created_at.*');
        $user->addPermission('article.attributes.updated_at.*');
        $user->addPermission('article.attributes.deleted_at.*');

        $result = $am->create($ab);
        $this->assertEquals(1, $result->ok());

        $resource = $result->getResource();

        $this->assertEquals([
            'title'       => $resource->title,
            'description' => $resource->description,
            'author_id'   => $resource->author->id,
            'id'          => $resource->id,
            'created_at'  => $resource->created_at,
            'updated_at'  => $resource->updated_at,
        ], $am->serializer->serialize($resource)->toArray());

        $um->remove($user);
    }

    public function testDefaultValue()
    {
        $um = new UserManager();
        $user = $um->create(['email' => 'test1@test.net', 'username' => 'test1', 'password' => microtime()])->getResource();

        $am = new ArticleManager();

        $result = $am->create(['author_id' => $user->id]);

        $this->assertEquals(true, $result->ok());
        $this->assertEquals('a default value', $result->getResource()->title);
    }

    /**
     * Get package providers.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            AppServiceProvider::class,
            \Railken\Laravel\Manager\ManagerServiceProvider::class,
            UserServiceProvider::class,
            ArticleServiceProvider::class,
        ];
    }
}
