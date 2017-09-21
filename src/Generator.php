<?php

namespace Railken\Laravel\Manager;

use File;

class Generator
{

    /**
     * Construct
     *
     * @param string $base_path
     */
    public function __construct()
    {

    }


    /**
     * Generate a new ModelStructure folder
     * 
     * @param string $path
     * @param string $name
     *
     * @return void
     */
    public function generate($path, $namespace)
    {
       
        $path = $path."/".str_replace("\\", "/", $namespace);
        $name = collect(explode("\\", $namespace))->last();
        
        $vars = [
            'NAMESPACE' => $namespace,
            'NAME' => $name,
            'LOW:NAME' => strtolower($name),
        ];

        $this->base_path = $path;

        $this->put("/Model.php", "/{$name}.php", $vars);
        $this->put("/ModelManager.php", "/{$name}Manager.php", $vars);
        $this->put("/ModelRepository.php", "/{$name}Repository.php", $vars);
        $this->put("/ModelValidator.php", "/{$name}Validator.php", $vars);
        $this->put("/ModelObserver.php", "/{$name}Observer.php", $vars);
        $this->put("/ModelAuthorizer.php", "/{$name}Authorizer.php", $vars);
        $this->put("/ModelSerializer.php", "/{$name}Serializer.php", $vars);
        $this->put("/ModelParameterBag.php", "/{$name}ParameterBag.php", $vars);
        $this->put("/Exceptions/ModelNotFoundException.php", "/Exceptions/{$name}NotFoundException.php", $vars);
        $this->put("/Exceptions/ModelNotAuthorizedException.php", "/Exceptions/{$name}NotAuthorizedException.php", $vars);
        $this->put("/Exceptions/ModelAttributeException.php", "/Exceptions/{$name}AttributeException.php", $vars);
        $this->put("/Exceptions/ModelNameNotDefinedException.php", "/Exceptions/{$name}NameNotDefinedException.php", $vars);
        $this->put("/Exceptions/ModelNameNotValidException.php", "/Exceptions/{$name}NameNotValidException.php", $vars);
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
