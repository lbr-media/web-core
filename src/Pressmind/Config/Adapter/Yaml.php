<?php


namespace Pressmind\Config\Adapter;


use Pressmind\Config\AdapterInterface;

class Yaml implements AdapterInterface
{

    private $_config_file;
    private $_environment;

    public function __construct($name, $environment)
    {
        $this->_config_file = APPLICATION_PATH . '/config/' . $name . '.yaml';
        $this->_environment = $environment;
    }

    public function read()
    {
        $config = yaml_parse_file($this->_config_file);
        return $config[$this->_environment];
    }

    public function write($data)
    {
        // TODO: Implement write() method.
    }
}
