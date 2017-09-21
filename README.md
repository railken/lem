# Laravel Manager

Organize your project with a defined structure to handle Models in laravel: manipulate, validate, authorize, serialize, etc...

## Requirements

PHP 7.0.0 or later.

## Composer

You can install it via [Composer](https://getcomposer.org/) by typing the following command:

```bash
composer require railken/laravel-manager
```

## Installation
- Add the service provider to the `providers` array in `config/app.php`

```php
Railken\Laravel\Manager\ManagerServiceProvider::class,
```

## Usage

- Generate a new set of files `php artisan railken:make:manager [path] [namespace]`. An example would be `php artisan railken:make:manager src/Core Core/User`. 

```php
<?php

$manager = new FooManager();
$result = $manager->create($manager->parameters(['name' => 'foo']));

if ($result->ok()) {
    $foo = $result->getResource(); 
} else {
    $result->getErrors(); // All errors goes here.
} 

```
### ModelManager
This is the main class, all the operations are performed using this: create, update, retrieve, remove

See [ModelRepository](https://github.com/railken/laravel-manager/blob/master/src/ModelManager.php) for more information.
```php
<?php

namespace Railken\Laravel\Manager\Tests\Generated\Foo;

use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\ParameterBag;

use Railken\Laravel\Manager\Tests\Generated\Foo\Foo;

class FooManager extends ModelManager
{

    /**
     * Construct
     */
    public function __construct(AgentContract $agent = null)
    {
        $this->repository = new FooRepository($this);
        $this->authorizer = new FooAuthorizer($this);
        $this->validator = new FooValidator($this);
        $this->serializer = new FooSerializer($this);

        parent::__construct($agent);
    }

    /**
     * Fill the entity
     *
     * @param EntityContract $entity
     * @param FooParameterBag $parameters
     *
     * @return EntityContract
     */
    public function fill(EntityContract $entity, ParameterBag $parameters)
    {
        $parameters = $parameters->only(['name']);

        return parent::fill($entity, $parameters);
    }
}
```

### Model
This is the Eloquent Model, nothing changes, excepts for an interface

```php
<?php

namespace Core\Foo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Railken\Laravel\Manager\Contracts\EntityContract;

class Foo extends Model implements EntityContract
{

    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'foo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
}
```

### ModelRepository
This is a Repository, the concept is very similar to the Repository of Symfony, code all your queries here. 

See [ModelRepository](https://github.com/railken/laravel-manager/blob/master/src/ModelRepository.php) for more information.

```php
<?php

namespace Core\Foo;

use Railken\Laravel\Manager\ModelRepository;

class FooRepository extends ModelRepository
{

    /**
     * Class name entity
     *
     * @var string
     */
    public $entity = Foo::class;

    /**
     * Custom method
     *
     * @param string $name
     *
     * @return Foo
     */
    public function findOneByName($name)
    {
        return $this->findOneBy(['name' => $name]);
    }

}

```

### ModelParameterBag
This is a [Bag](https://github.com/railken/bag).

```php
<?php

namespace Core\Foo;

use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\ParameterBag;

class FooParameterBag extends ParameterBag
{
        
    /**
     * Filter current bag using agent
     *
     * @param AgentContract $agent
     *
     * @return this
     */
    public function filterByAgent(AgentContract $agent)
    {  
        return $this;
    }
        
    /**
     * Filter current bag using agent for a search
     *
     * @param AgentContract $agent
     *
     * @return this
     */
    public function filterSearchableByAgent(AgentContract $agent)
    {  
        return $this;
    }
}

```
