<?php


namespace Pressmind\ORM\Object\MediaObject\DataType;


class Keyvalue extends AbstractDataType
{
    protected $_definition = [
        'class' => [
            'name' => 'Objectlink',
            'namespace' => '\Pressmind\ORM\MediaObject\DataType',
        ],
        'properties' => [
            'value' => [
                'title' => 'value',
                'name' => 'value',
                'type' => 'string',
                'required' => false,
                'filters' => null,
                'validators' => null,
            ]
        ]

    ];
}
