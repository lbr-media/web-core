<?php

namespace Pressmind\ORM\Object\MediaObject\DataType;
use Pressmind\ORM\Object\AbstractObject;
use Pressmind\ORM\Object\MediaObject\DataType\Picture\Derivative;

/**
 * Class Plaintext
 * @package Pressmind\ORM\Object\MediaObject\DataType
 * @property integer $id
 * @property integer $id_media_object
 * @property string $section_name
 * @property string $var_name
 * @property string $file_name
 * @property integer $width
 * @property integer $height
 * @property integer $file_size
 * @property string $caption
 * @property string $title
 * @property string $uri
 * @property string $alt
 * @property string $copyright
 * @property integer $sort
 * @property string $tmp_url
 * @property string $path
 * @property string $mime_type
 * @property Derivative[] $derivatives
 */
class Picture extends AbstractObject
{
    protected $_definitions = [
        'class' => [
            'name' => 'Picture',
            'namespace' => '\Pressmind\ORM\MediaObject\DataType',
        ],
        'database' => [
            'table_name' => 'pmt2core_media_object_images',
            'primary_key' => 'id',
            'order_columns' => ['sort' => 'ASC']
        ],
        'properties' => [
            'id' => [
                'title' => 'id',
                'name' => 'id',
                'type' => 'integer',
                'required' => true,
                'filters' => null,
                'validators' => null,
            ],
            'id_media_object' => [
                'title' => 'id_media_object',
                'name' => 'id_media_object',
                'type' => 'integer',
                'required' => true,
                'filters' => null,
                'validators' => null,
            ],
            'section_name' => [
                'title' => 'section_name',
                'name' => 'section_name',
                'type' => 'string',
                'required' => false,
                'filters' => null,
                'validators' => null,
            ],
            'language' => [
                'title' => 'section_name',
                'name' => 'section_name',
                'type' => 'string',
                'required' => false,
                'filters' => null,
                'validators' => null,
            ],
            'var_name'  => [
                'title' => 'var_name',
                'name' => 'var_name',
                'type' => 'string',
                'required' => true,
                'filters' => null,
                'validators' => null,
            ],
            'caption' => [
                'title' => 'caption',
                'name' => 'caption',
                'type' => 'string',
                'required' => false,
                'filters' => null,
                'validators' => null,
            ],
            'file_name' => [
                'title' => 'file_name',
                'name' => 'file_name',
                'type' => 'string',
                'required' => false,
                'filters' => null,
                'validators' => null,
            ],
            'width' => [
                'title' => 'width',
                'name' => 'width',
                'type' => 'integer',
                'required' => false,
                'filters' => null,
                'validators' => null,
            ],
            'height' => [
                'title' => 'height',
                'name' => 'height',
                'type' => 'integer',
                'required' => false,
                'filters' => null,
                'validators' => null,
            ],
            'file_size' => [
                'title' => 'file_size',
                'name' => 'file_size',
                'type' => 'integer',
                'required' => false,
                'filters' => null,
                'validators' => null,
            ],
            'title' => [
                'title' => 'title',
                'name' => 'title',
                'type' => 'string',
                'required' => false,
                'filters' => null,
                'validators' => null,
            ],
            'uri' => [
                'title' => 'uri',
                'name' => 'uri',
                'type' => 'string',
                'required' => false,
                'filters' => null,
                'validators' => null,
            ],
            'alt' => [
                'title' => 'alt',
                'name' => 'alt',
                'type' => 'string',
                'required' => false,
                'filters' => null,
                'validators' => null,
            ],
            'copyright' => [
                'title' => 'copyright',
                'name' => 'copyright',
                'type' => 'string',
                'required' => false,
                'filters' => null,
                'validators' => null,
            ],
            'sort' => [
                'title' => 'sort',
                'name' => 'sort',
                'type' => 'integer',
                'required' => false,
                'filters' => null,
                'validators' => null,
            ],
            'tmp_url' => [
                'title' => 'tmp_url',
                'name' => 'tmp_url',
                'type' => 'string',
                'required' => false,
                'filters' => null,
                'validators' => null,
            ],
            'path' => [
                'title' => 'path',
                'name' => 'path',
                'type' => 'string',
                'required' => false,
                'filters' => null,
                'validators' => null,
            ],
            'mime_type' => [
                'title' => 'type',
                'name' => 'type',
                'type' => 'string',
                'required' => false,
                'filters' => null,
                'validators' => null,
            ],
            'derivatives' => [
                'title' => 'derivatives',
                'name' => 'derivatives',
                'type' => 'relation',
                'required' => false,
                'filters' => null,
                'validators' => null,
                'relation' => [
                    'type' => 'hasMany',
                    'class' => '\\Pressmind\\ORM\\Object\\MediaObject\\DataType\\Picture\\Derivative',
                    'related_id' => 'id_image',
                    /*'on_save_related_properties' => [
                        'id' => 'id_image'
                    ],*/
                    'filters' => null,
                ],
            ]
        ]
    ];

    /**
     * @param \stdClass $object
     * @throws \Exception
     */
    /*public function fromStdClass($object)
    {
        //print_r($object);
        // we need to check if the given object is already well formatted, if not we need to map the object ...
        $has_right_format = true;
        foreach ($object as $key => $value) {
            if(!isset($this->_definitions['properties'][$key])) {
                $has_right_format = false;
            }
        }
        if(is_a($object, '\Pressmind\ORM\Object\MediaObject\DataType\Image')) {
            $has_right_format = true;
        }
        //print_r($object);
        if(false === $has_right_format) {
            $mapped_object = new \stdClass();
            $mapped_object->id_media_object = $object->id_media_object;
            $mapped_object->section_name = '';
            $mapped_object->caption = $object->caption;
            $mapped_object->title = $object->title;
            $mapped_object->uri = $object->uri;
            $mapped_object->alt = $object->alt;
            $mapped_object->copyright = $object->copyright;
            $mapped_object->tmp_url = $object->image->links->web;
            $object = $mapped_object;
        }

        //return ['foo', $object];
        parent::fromStdClass($object); // TODO: Change the autogenerated stub
    }*/

    /**
     * @param null $derivativeName
     * @return string
     */
    public function getUri($derivativeName = null) {
        if(!is_null($derivativeName)) {
            if($derivative = $this->_hasDerivative($derivativeName)) {
                return $derivative->uri;
            }
        }
        return is_null($this->uri) ? $this->tmp_url : $this->uri . '/' . $this->file_name;
    }

    private function _hasDerivative($derivativeName)
    {
        if(is_null($this->derivatives)) {
            return false;
        }
        foreach ($this->derivatives as $derivative) {
            if($derivative->name == $derivativeName) {
                return $derivative;
            }
        }
        return false;
    }
}
