<?php

namespace Railken\Laravel\Manager;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Collection;

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
        $base_path = $path."/".$name;

        $vars = [
            'NAMESPACE' => $namespace,
            'NAME' => $name,
            'LOW:NAME' => strtolower($name),
            'UP:NAME' => strtoupper($name),
        ];

        $this->base_path = $base_path;

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

        $this->generateAttribute($path, $namespace, 'name');
    }

    /**
     * Generate a new ModelStructure folder
     *
     * @param string $path
     * @param string $namespace
     * @param string $attribute
     *
     * @return void
     */
    public function generateAttribute($path, $namespace, $attribute)
    {
        $namespaces = collect(explode("\\", $namespace));
        $name = $namespaces->last();
        $base_path = $path."/".$name;

        $attribute_ucf = ucfirst($attribute);

        $vars = [
            'NAMESPACE' => $namespace,
            'NAME' => $name,
            'LOW:NAME' => strtolower($name),
            'UP:NAME' => strtoupper($name),
            'ATTRIBUTE:LOW' => strtolower($attribute),
            'ATTRIBUTE:FUP' => $attribute_ucf,
            'ATTRIBUTE:UP' => strtoupper($attribute)
        ];

        $this->base_path = $base_path;

        $this->put("/Exceptions/ModelAttributeNotDefinedException.php.stub", "/Exceptions/{$name}".ucfirst($attribute)."NotDefinedException.php", $vars);
        $this->put("/Exceptions/ModelAttributeNotValidException.php.stub", "/Exceptions/{$name}".ucfirst($attribute)."NotValidException.php", $vars);
        // Updating validator.

        // Retrieve class validator
        $c = "\\{$namespace}\\{$name}Validator";
        if (!in_array("validate{$attribute_ucf}", get_class_methods($c))) {

            // We are free to add code

            $class_content = file_get_contents("$base_path/{$name}Validator.php");
            $content = $this->parse($vars, File::get(__DIR__."/stubs/ModelValidator_Attribute.php.stub"));

            $p = explode("}", $class_content);
            array_pop($p);
            $class_content = implode("}", $p);
            $class_content .= "\n{$content}\n}";
            file_put_contents("$base_path/{$name}Validator.php", $class_content);
        }
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

        $content = $this->parse($data, $content);


        File::put($to, $content);
    }

    public function parse($vars, $content)
    {
        foreach ($vars as $n => $k) {
            $content = str_replace("$".$n."$", $k, $content);
        }

        return $content;
    }
}
