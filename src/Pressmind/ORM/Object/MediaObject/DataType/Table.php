<?php

namespace Pressmind\ORM\Object\MediaObject\DataType;
use Pressmind\ORM\Object\AbstractObject;
use Pressmind\ORM\Object\MediaObject\DataType\Table\Row;

/**
 * Class Plaintext
 * @package Pressmind\ORM\Object\MediaObject\DataType
 * @property integer $id
 * @property integer $id_media_object
 * @property string $section_name
 * @property string $language
 * @property string $var_name
 * @property Row[] $rows
 */
class Table extends AbstractObject
{
    protected $_definitions = [
        'class' => [
            'name' => 'Table',
            'namespace' => '\Pressmind\ORM\MediaObject\DataType',
        ],
        'database' => [
            'table_name' => 'pmt2core_media_object_tables',
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
                'title' => 'language',
                'name' => 'language',
                'type' => 'string',
                'required' => true,
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
            'rows' => [
                'title' => 'rows',
                'name' => 'rows',
                'type' => 'relation',
                'required' => false,
                'filters' => null,
                'validators' => null,
                'relation' => [
                    'type' => 'hasMany',
                    'class' => '\\Pressmind\\ORM\\Object\\MediaObject\\DataType\\Table\\Row',
                    'related_id' => 'id_table',
                    'on_save_related_properties' => [
                        'id' => 'id_table'
                    ],
                    'filters' => null,
                ],
            ]
        ]
    ];
}
