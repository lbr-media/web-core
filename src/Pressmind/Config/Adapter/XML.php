<?php


namespace Pressmind\Config\Adapter;


use Pressmind\Config\AdapterInterface;

class XML implements AdapterInterface
{

    public function __construct($name, $environment, $options = array())
    {
        parent::__construct($name, $environment, $options);
    }

    public function read()
    {
        // TODO: Implement read() method.
    }

    public function write($data)
    {
        // TODO: Implement write() method.
    }
}
