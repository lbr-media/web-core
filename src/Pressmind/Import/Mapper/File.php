<?php


namespace Pressmind\Import\Mapper;


class File implements MapperInterface
{
    public function map($pIdMediaObject,$pLanguage, $pVarName, $pObject)
    {
        $mapped_objects = [];
        if(is_array($pObject)) {
            foreach ($pObject as $file) {
                $mapped_object = new \stdClass();
                $mapped_object->id_media_object = $pIdMediaObject;
                $mapped_object->section_name = '';
                $mapped_object->language = $pLanguage;
                $mapped_object->var_name = $pVarName;
                $mapped_object->id_file = $file->id_file;
                $mapped_object->file_name = $file->filename;
                $mapped_object->file_size = $file->filesize;
                $mapped_object->file_path = null;
                $mapped_object->description = $file->description;
                $mapped_object->extension = $file->extension;
                $mapped_object->tmp_url = null;
                $mapped_object->download_url = $file->download;
                $mapped_objects[] = $mapped_object;
            }
        }
        return($mapped_objects);
    }
}
