<?php


namespace Pressmind\Import\Mapper;


class Picture implements MapperInterface
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
                $mapped_object->caption = $object->caption;
                $mapped_object->title = $object->title;
                $mapped_object->uri = isset($object->uri) ? $object->uri : '';
                $mapped_object->alt = $object->alt;
                $mapped_object->copyright = $object->copyright;
                $mapped_object->tmp_url = $object->image->links->web;
                $mapped_object->file_name = $object->id_media_object . '_' . $object->image->filename;
                $mapped_object->width = $object->image->width;
                $mapped_object->height = $object->image->height;
                $mapped_object->file_size = $object->image->filesize;
                $result[] = $mapped_object;
            }
        }
        return($result);
    }
}
