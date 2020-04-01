<?php


namespace Pressmind\ORM\Object;


/**
 * Class Route
 * @package Pressmind\ORM\Object
 * @property integer $id
 * @property string $route
 * @property integer $id_media_object
 * @property string $language
 */
class Route extends AbstractObject
{
    protected $_definitions = [
        'class' => [
            'name' => 'Route',
            'namespace' => '\Pressmind\ORM\Object',
        ],
        'database' => [
            'table_name' => 'pmt2core_routes',
            'primary_key' => 'id',
        ],
        'properties' => [
            'id' => [
                'title' => 'id',
                'name' => 'id',
                'type' => 'integer',
                'required' => false,
                'filters' => null,
                'validators' => null,
            ],
            'route' => [
                'title' => 'route',
                'name' => 'route',
                'type' => 'string',
                'required' => false,
                'filters' => null,
                'validators' => null,
            ],
            'id_media_object' => [
                'title' => 'id_media_object',
                'name' => 'id_media_object',
                'type' => 'integer',
                'required' => false,
                'filters' => null,
                'validators' => null,
            ],
            'language' => [
                'title' => 'language',
                'name' => 'language',
                'type' => 'string',
                'required' => false,
                'filters' => null,
                'validators' => null,
            ]
        ]
    ];
}
