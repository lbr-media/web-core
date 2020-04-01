<?php


namespace Pressmind\Import\Mapper;


class Objectlink implements MapperInterface
{
    public function map($pIdMediaObject,$pLanguage, $pVarName, $pObject)
    {
        $result = [];
        if(isset($pObject->objects) && is_array($pObject->objects)) {
            foreach ($pObject->objects as $object) {
                $mapped_object = new \stdClass();
                $mapped_object->id_media_object = $pIdMediaObject;
                $mapped_object->section_name = '';
                $mapped_object->language = $pLanguage;
                $mapped_object->var_name = $pVarName;
                $mapped_object->id_media_object_link = $object;
                $mapped_object->id_object_type = isset($pObject->id_object_type) ? $pObject->id_object_type : 0;
                $mapped_object->link_type = $pObject->objectLink == 1 ? 'objectlink' : 'image';
                $result[] = $mapped_object;
            }
        }
        return($result);
    }
}
