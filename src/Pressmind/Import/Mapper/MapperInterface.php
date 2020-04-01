<?php


namespace Pressmind\Import\Mapper;


interface MapperInterface
{
    public function map($pIdMediaObject, $pLanguage, $pVarName, $pObject);
}
