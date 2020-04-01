<?php


namespace Pressmind\Import\Mapper;


class Location implements MapperInterface
{
    public function map($pIdMediaObject,$pLanguage, $pVarName, $pObject)
    {
        $result = [];
        if(is_array($pObject)) {
            foreach ($pObject as $object) {
                $mapped_object = new \stdClass();
                $mapped_object->id_media_object = $pIdMediaObject;
                $mapped_object->section_name = '';
                $mapped_object->language = $pLanguage;
                $mapped_object->var_name = $pVarName;
                $mapped_object->lat = $object->lat;
                $mapped_object->lng = $object->lng;
                $mapped_object->address = $object->address;
                $mapped_object->title = $object->title;
                $result[] = $mapped_object;
            }
        }
        return($result);
    }
}
