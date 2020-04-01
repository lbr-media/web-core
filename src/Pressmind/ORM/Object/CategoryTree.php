<?php

namespace Pressmind\ORM\Object;

use Pressmind\ORM\Object\CategoryTree\Item;

/**
 * Class CategoryTree
 * @property integer $id
 * @property string $name
 * @property Item[] $items
 */
class CategoryTree extends AbstractObject
{
    protected $_dont_use_autoincrement_on_primary_key = true;

    protected $_definitions = [
        'class' => [
            'name' => 'CategoryTree',
        ],
        'database' => [
            'table_name' => 'pmt2core_category_trees',
            'primary_key' => 'id',
        ],
        'properties' => [
            'id' => [
                'title' => 'Id',
                'name' => 'id',
                'type' => 'integer',
                'required' => true,
                'validators' => [
                    [
                        'name' => 'maxlength',
                        'params' => 22,
                    ],
                ],
                'filters' => NULL,
            ],
            'name' => [
                'title' => 'Name',
                'name' => 'name',
                'type' => 'string',
                'required' => true,
                'validators' => [
                    [
                        'name' => 'maxlength',
                        'params' => 255
                    ]
                ],
                'filters' => NULL,
            ],
            'items' => [
                'title' => 'items',
                'name' => 'items',
                'type' => 'relation',
                'required' => false,
                'filters' => null,
                'validators' => null,
                'relation' => [
                    'type' => 'hasMany',
                    'class' => '\Pressmind\ORM\Object\CategoryTree\Item',
                    'related_id' => 'id_tree',
                    'filters' => [
                        'id_parent' => 'IS NULL'
                    ],
                    'order_columns' => [
                        'sort' => 'ASC'
                    ]
                ],
            ]
        ]
    ];

    /**
     * @TODO somehow implement this function -> should return the flat array of items as a taxonomy tree
     * @return array
     */
    public function itemsToTaxonomy() {
        $array = [];
        foreach ($this->items as $item) {
            $array[] = $item->toStdClass();
        }
        return $array;
    }

    //public function getChildren

    private function _groupArray($arr, $group, $preserveGroupKey = false, $preserveSubArrays = false) {
        $temp = array();
        foreach($arr as $key => $value) {
            $groupValue = $value[$group];
            if(!$preserveGroupKey)
            {
                unset($arr[$key][$group]);
            }
            if(!array_key_exists($groupValue, $temp)) {
                $temp[$groupValue] = array();
            }

            if(!$preserveSubArrays){
                $data = count($arr[$key]) == 1? array_pop($arr[$key]) : $arr[$key];
            } else {
                $data = $arr[$key];
            }
            $temp[$groupValue][] = $data;
        }
        return $temp;
    }
}
