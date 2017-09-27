<?php

namespace Railken\Laravel\Manager;

use Illuminate\Support\Facades\File;

class Generator
{

    /**
     * Construct
     */
    public function __construct()
    {
    }


    /**
     * Generate a new ModelStructure folder
     *
     * @param string $path
     * @param string $namespace
     *
     * @return void
     */
    public function generate($path, $namespace)
    {
        $namespaces = collect(explode("\\", $namespace));
        $name = $namespaces->last();
        $path = $path."/".$name;

        $vars = [
            'NAMESPACE' => $namespace,
            'NAME' => $name,
            'LOW:NAME' => strtolower($name),
            'UP:NAME' => strtoupper($name),
        ];

        $this->base_path = $path;

        $this->put("/Model.php.stub", "/{$name}.php", $vars);
        $this->put("/ModelManager.php.stub", "/{$name}Manager.php", $vars);
        $this->put("/ModelRepository.php.stub", "/{$name}Repository.php", $vars);
        $this->put("/ModelValidator.php.stub", "/{$name}Validator.php", $vars);
        $this->put("/ModelObserver.php.stub", "/{$name}Observer.php", $vars);
        $this->put("/ModelAuthorizer.php.stub", "/{$name}Authorizer.php", $vars);
        $this->put("/ModelSerializer.php.stub", "/{$name}Serializer.php", $vars);
        $this->put("/ModelParameterBag.php.stub", "/{$name}ParameterBag.php", $vars);
        $this->put("/ModelPolicy.php.stub", "/{$name}Policy.php", $vars);
        $this->put("/ModelServiceProvider.php.stub", "/{$name}ServiceProvider.php", $vars);
        $this->put("/Exceptions/ModelException.php.stub", "/Exceptions/{$name}Exception.php", $vars);
        $this->put("/Exceptions/ModelNotFoundException.php.stub", "/Exceptions/{$name}NotFoundException.php", $vars);
        $this->put("/Exceptions/ModelNotAuthorizedException.php.stub", "/Exceptions/{$name}NotAuthorizedException.php", $vars);
        $this->put("/Exceptions/ModelAttributeException.php.stub", "/Exceptions/{$name}AttributeException.php", $vars);
        $this->put("/Exceptions/ModelNameNotDefinedException.php.stub", "/Exceptions/{$name}NameNotDefinedException.php", $vars);
        $this->put("/Exceptions/ModelNameNotValidException.php.stub", "/Exceptions/{$name}NameNotValidException.php", $vars);
    }

    /**
     * Generate a new file from $source to $to
     *
     * @param string $source
     * @param string $to
     * @param array $data
     *
     * @return void
     */
    public function put($source, $to, $data = [])
    {
        $content = File::get(__DIR__."/stubs".$source);

        $to = $this->base_path.$to;


        $to_dir = dirname($to);

        !File::exists($to_dir) && File::makeDirectory($to_dir, 0775, true);

        foreach ($data as $n => $k) {
            $content = str_replace("$".$n."$", $k, $content);
        }


        File::put($to, $content);
    }
}
