<?php


namespace Pressmind\ORM\Object\MediaObject\DataType\Picture;


use Pressmind\ORM\Object\AbstractObject;

/**
 * Class Derivative
 * @package Pressmind\ORM\Object\MediaObject\DataType\Picture
 * @property integer $id
 * @property integer $id_image
 * @property string $name
 * @property integer $width
 * @property integer $height
 * @property string $path
 * @property string $uri
 */
class Derivative extends AbstractObject
{
    protected $_definitions = [
        'class' => [
            'name' => 'Derivative',
            'namespace' => '\Pressmind\ORM\MediaObject\DataType\Image',
        ],
        'database' => [
            'table_name' => 'pmt2core_media_object_image_derivatives',
            'primary_key' => 'id'
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
            'id_image' => [
                'title' => 'id_image',
                'name' => 'id_image',
                'type' => 'integer',
                'required' => true,
                'filters' => null,
                'validators' => null,
            ],
            'name' => [
                'title' => 'name',
                'name' => 'name',
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
            'path' => [
                'title' => 'path',
                'name' => 'path',
                'type' => 'string',
                'required' => false,
                'filters' => null,
                'validators' => null,
            ],
            'width' => [
                'title' => 'width',
                'name' => 'width',
                'type' => 'integer',
                'required' => true,
                'filters' => null,
                'validators' => null,
            ],
            'height' => [
                'title' => 'height',
                'name' => 'height',
                'type' => 'integer',
                'required' => true,
                'filters' => null,
                'validators' => null,
            ],
        ]
    ];
}
