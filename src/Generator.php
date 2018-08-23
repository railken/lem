<?php

namespace Railken\Laravel\Manager;

use PhpParser\NodeVisitorAbstract;
use Railken\Laravel\Manager\Parser\Visitors as Visitors;

class Generator
{
    /**
     * Construct.
     */
    public function __construct()
    {
    }

    /**
     * Camelizes a string.
     *
     * @param string $id A string to camelize
     *
     * @return string The camelized string
     */
    public static function camelize($id)
    {
        return strtr(ucwords(strtr($id, ['_' => ' ', '.' => '_ ', '\\' => '_ '])), [' ' => '']);
    }

    /**
     * A string to underscore.
     *
     * @param string $id The string to underscore
     *
     * @return string The underscored string
     */
    public static function underscore($id)
    {
        return strtolower(preg_replace(['/([A-Z]+)([A-Z][a-z])/', '/([a-z\d])([A-Z])/'], ['\\1_\\2', '\\1_\\2'], $id));
    }

    /**
     * Generate a new ModelStructure folder.
     *
     * @param string $path
     * @param string $namespace
     */
    public function generate(string $path, string $namespace)
    {
        $namespaces = collect(explode('\\', $namespace));
        $name = $namespaces->last();
        $base_path = $path;

        $name = $this->camelize($name);
        $vars = [
            'NAMESPACE'       => $namespace,
            'NAME'            => $name,
            'NAME:CAMELIZED'  => $name,
            'NAME:UNDERSCORE' => $this->underscore($name),
            'NAME:UPPERCASE'  => strtoupper($name),
        ];

        $this->put($base_path, '/Model.php.stub', "/{$name}.php", $vars);
        $this->put($base_path, '/ModelManager.php.stub', "/{$name}Manager.php", $vars);
        $this->put($base_path, '/ModelRepository.php.stub', "/{$name}Repository.php", $vars);
        $this->put($base_path, '/ModelValidator.php.stub', "/{$name}Validator.php", $vars);
        $this->put($base_path, '/ModelAuthorizer.php.stub', "/{$name}Authorizer.php", $vars);
        $this->put($base_path, '/ModelObserver.php.stub', "/{$name}Observer.php", $vars);
        $this->put($base_path, '/ModelSerializer.php.stub', "/{$name}Serializer.php", $vars);
        $this->put($base_path, '/ModelServiceProvider.php.stub', "/{$name}ServiceProvider.php", $vars);
        $this->put($base_path, '/Exceptions/ModelException.php.stub', "/Exceptions/{$name}Exception.php", $vars);
        $this->put($base_path, '/Exceptions/ModelNotFoundException.php.stub', "/Exceptions/{$name}NotFoundException.php", $vars);
        $this->put($base_path, '/Exceptions/ModelNotAuthorizedException.php.stub', "/Exceptions/{$name}NotAuthorizedException.php", $vars);
        $this->put($base_path, '/Exceptions/ModelAttributeException.php.stub', "/Exceptions/{$name}AttributeException.php", $vars);

        $this->generateAttribute($path, $namespace, 'id');
        $this->generateAttribute($path, $namespace, 'name');
        $this->generateAttribute($path, $namespace, 'created_at');
        $this->generateAttribute($path, $namespace, 'updated_at');
        $this->generateAttribute($path, $namespace, 'deleted_at');
    }

    /**
     * Generate a new ModelStructure folder.
     *
     * @param string $path
     * @param string $namespace
     * @param string $attribute
     */
    public function generateAttribute(string $path, string $namespace, string $attribute, array $arguments = [])
    {
        $namespaces = collect(explode('\\', $namespace));
        $name = $namespaces->last();
        $base_path = $path;
        $name = $this->camelize($name);

        $attribute_ucf = ucfirst($attribute);

        $attribute_camelized = $this->camelize($attribute);
        $attribute_underscore = $this->underscore($attribute);

        $vars = [
            'NAMESPACE'            => $namespace,
            'NAME'                 => $name,
            'NAME:UNDERSCORE'      => strtolower($name),
            'NAME:UPPERCASE'       => strtoupper($name),
            'ATTRIBUTE:UNDERSCORE' => $attribute_underscore,
            'ATTRIBUTE:CAMELIZED'  => $attribute_camelized,
            'ATTRIBUTE:UPPERCASE'  => strtoupper($attribute),
        ];

        $this->put(
            $base_path,
            '/Attributes/ModelAttribute.php.stub',
            "/Attributes/{$attribute_camelized}/{$attribute_camelized}Attribute.php",
            $vars
        );

        $this->put(
            $base_path,
            '/Attributes/Exceptions/ModelAttributeNotDefinedException.php.stub',
            "/Attributes/{$attribute_camelized}/Exceptions/{$name}{$attribute_camelized}NotDefinedException.php",
            $vars
        );

        $this->put(
            $base_path,
            '/Attributes/Exceptions/ModelAttributeNotValidException.php.stub',
            "/Attributes/{$attribute_camelized}/Exceptions/{$name}{$attribute_camelized}NotValidException.php",
            $vars
        );

        $this->put(
            $base_path,
            '/Attributes/Exceptions/ModelAttributeNotAuthorizedException.php.stub',
            "/Attributes/{$attribute_camelized}/Exceptions/{$name}{$attribute_camelized}NotAuthorizedException.php",
            $vars
        );

        $this->put(
            $base_path,
            '/Attributes/Exceptions/ModelAttributeNotUniqueException.php.stub',
            "/Attributes/{$attribute_camelized}/Exceptions/{$name}{$attribute_camelized}NotUniqueException.php",
            $vars
        );

        $this->parseCode($base_path."/{$name}.php", new Visitors\ModelVisitor($attribute_underscore));
        $this->parseCode($base_path."/{$name}Manager.php", new Visitors\ModelManagerVisitor(['Attributes', $attribute_camelized, "{$attribute_camelized}Attribute"]));
    }

    /**
     * Parse the code with a visitor.
     *
     * @param string                         $path
     * @param \PhpParser\NodeVisitorAbstract $visitor
     */
    public function parseCode($path, NodeVisitorAbstract $visitor)
    {
        $parser = new Parser\Parser();

        return $parser->edit($path, $visitor);
    }

    /**
     * Generate a new file from $source to $to.
     *
     * @param string $base_path
     * @param string $source
     * @param string $to
     * @param array  $data
     */
    public function put($base_path, $source, $to, $data = [])
    {
        $content = file_get_contents(__DIR__.'/stubs'.$source);

        $to = $base_path.$to;

        $to_dir = dirname($to);

        !file_exists($to_dir) && mkdir($to_dir, 0775, true);

        $content = $this->parse($data, $content);

        !file_exists($to) && file_put_contents($to, $content);
    }

    public function parse($vars, $content)
    {
        foreach ($vars as $n => $k) {
            $content = str_replace('$'.$n.'$', $k, $content);
        }

        return $content;
    }
}
