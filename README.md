# Laravel Manager

Organize your models with a specific criteria: Model + Repository + Manager

## Installation

- Add the package and the folder psr-4 to your `composer.json` and run `composer update`.
```json
{
    "require": {
        "railken/laravel-manager": "*@dev"
    }
}
```
- Add the service provider to the `providers` array in `config/app.php`

```php
Railken\Laravel\Manager\ManagerServiceProvider::class,
```

## Usage

- Generate a new set of files `php artisan railken:make:manager [base_path] [path] [name]`. An example would be `php artisan railken:make:manager src Core/User User`. 
The final results will generate a folder in the defined path and 3 files: Model, ModelRepository, ModelManager
