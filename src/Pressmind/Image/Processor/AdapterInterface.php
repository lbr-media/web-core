<?php


namespace Pressmind\Image\Processor;


interface AdapterInterface
{
    /**
     * @param Config $config
     * @param string $file
     * @param string $derivativeName
     * @return mixed
     */
    public function process($config, $file, $derivativeName);
}
