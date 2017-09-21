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
This is a Repository, the concept is very similar to the Repository of Symfony, code all your queries here. See [ModelRepository](https://github.com/railken/laravel-manager/blob/master/src/ModelRepository.php)

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
        return $this->findOneBy(['name' => $name])
    }

}

```

