<?php


namespace Pressmind\Cache;


interface AdapterInterface
{
    public function add($pKey, $pValue);
    public function remove($pKey);
    public function exists($pKey);
    public function get($pKey);
}
