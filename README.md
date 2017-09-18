# Laravel Manager

Organize your models with a specific criteria: Model + Repository + Manager

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

- Generate a new set of files `php artisan railken:make:manager [base_path] [path] [name]`. An example would be `php artisan railken:make:manager src Core/User User`. 

The command generates a folder with a bunch of files that will help to structure the management of a model
