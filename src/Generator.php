<?php

namespace Railken\Lem;

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
        $this->put($base_path, '/ModelSerializer.php.stub', "/{$name}Serializer.php", $vars);
        $this->put($base_path, '/ModelAuthorizer.php.stub', "/{$name}Authorizer.php", $vars);
        $this->put($base_path, '/Exceptions/ModelException.php.stub', "/Exceptions/{$name}Exception.php", $vars);
        $this->put($base_path, '/Exceptions/ModelNotFoundException.php.stub', "/Exceptions/{$name}NotFoundException.php", $vars);
        $this->put($base_path, '/Exceptions/ModelNotAuthorizedException.php.stub', "/Exceptions/{$name}NotAuthorizedException.php", $vars);
        $this->put($base_path, '/Exceptions/ModelAttributeException.php.stub', "/Exceptions/{$name}AttributeException.php", $vars);
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
