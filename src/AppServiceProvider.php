<?php 

namespace Railken\Laravel\Manager;

use Illuminate\Support\ServiceProvider;
use Railken\Laravel\Manager\Commands as Commands;
use File;

class AppServiceProvider extends ServiceProvider
{

    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    public $app;

    /**
     * List of all version accepted
     *
     * @var Array
     */
    public $versions = ['5'];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->commands([Commands\Generate::class]);

        echo "a";

        $v = explode(".",$this->app->version());
        $v = $v[0].".".$v[1];

        if (!in_array($v[0], $this->versions))
            throw new \Exception("Version {$this->app->version()} not supported");

        $this->version = $v;

        $this->app->bind('src.version',function() {
            return "5.0";
        });

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {


    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    /**
     * Load package
     *
     * @return void
     */
    public function loadPackages()
    {
        $path = base_path('src');
        
        $packages = collect();

        foreach(glob($path."/*") as $directory){
        
            $name = basename($directory);

            $file = $directory."/Package.php";
            $class = "{$name}\Package";

            if(File::exists($file)){
                require $file;
                $class = new $class($this,$directory,$name);
                $class->boot();

                $packages[] = $class;
            }
        }


        $packages->map(function($package){
            $package->register();
        });


    }
}
