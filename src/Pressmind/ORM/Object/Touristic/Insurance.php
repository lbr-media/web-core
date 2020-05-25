<?php

namespace Pressmind\ORM\Object\Touristic;

use Pressmind\ORM\Object\AbstractObject;
use Pressmind\ORM\Object\Touristic\Insurance\PriceTable;

/**
 * Class TouristicInsurance
 * @property integer $id
 * @property  $active
 * @property string $name
 * @property string $description
 * @property string $description_long
 * @property integer $duration_max_days
 * @property  $worldwide
 * @property  $is_additional_insurance
 * @property string $urlinfo
 * @property string $urlproduktinfo
 * @property string $urlagb
 * @property integer $pax_min
 * @property integer $pax_max
 * @property PriceTable[] $price_tables
 */
class Insurance extends AbstractObject
{
    protected $_dont_use_autoincrement_on_primary_key = true;

    protected $_definitions = array(
        'class' =>
            array(
                'name' => 'TouristicInsurance',
            ),
        'database' =>
            array(
                'table_name' => 'pmt2core_touristic_insurances',
                'primary_key' => 'id',
            ),
        'properties' =>
            array(
                'id' =>
                    array(
                        'title' => 'Id',
                        'name' => 'id',
                        'type' => 'integer',
                        'required' => true,
                        'validators' =>
                            array(
                                0 =>
                                    array(
                                        'name' => 'maxlength',
                                        'params' => 22,
                                    ),
                            ),
                        'filters' => NULL,
                    ),
                'active' =>
                    array(
                        'title' => 'Active',
                        'name' => 'active',
                        'type' => 'boolean',
                        'required' => false,
                        'validators' =>
                            array(
                                0 =>
                                    array(
                                        'name' => 'maxlength',
                                        'params' => 1,
                                    ),
                            ),
                        'filters' => NULL,
                    ),
                'name' =>
                    array(
                        'title' => 'Name',
                        'name' => 'name',
                        'type' => 'string',
                        'required' => false,
                        'validators' =>
                            array(
                                0 =>
                                    array(
                                        'name' => 'maxlength',
                                        'params' => 255,
                                    ),
                            ),
                        'filters' => NULL,
                    ),
                'description' =>
                    array(
                        'title' => 'Description',
                        'name' => 'description',
                        'type' => 'string',
                        'required' => false,
                        'validators' => NULL,
                        'filters' => NULL,
                    ),
                'description_long' =>
                    array(
                        'title' => 'Description_long',
                        'name' => 'description_long',
                        'type' => 'string',
                        'required' => false,
                        'filters' => NULL,
                    ),
                'duration_max_days' =>
                    array(
                        'title' => 'Duration_max_days',
                        'name' => 'duration_max_days',
                        'type' => 'integer',
                        'required' => false,
                        'validators' =>
                            array(
                                0 =>
                                    array(
                                        'name' => 'maxlength',
                                        'params' => 11,
                                    ),
                            ),
                        'filters' => NULL,
                    ),
                'worldwide' =>
                    array(
                        'title' => 'Worldwide',
                        'name' => 'worldwide',
                        'type' => 'boolean',
                        'required' => false,
                        'validators' =>
                            array(
                                0 =>
                                    array(
                                        'name' => 'maxlength',
                                        'params' => 1,
                                    ),
                            ),
                        'filters' => NULL,
                    ),
                'is_additional_insurance' =>
                    array(
                        'title' => 'Is_additional_insurance',
                        'name' => 'is_additional_insurance',
                        'type' => 'boolean',
                        'required' => false,
                        'validators' =>
                            array(
                                0 =>
                                    array(
                                        'name' => 'maxlength',
                                        'params' => 1,
                                    ),
                            ),
                        'filters' => NULL,
                    ),
                'urlinfo' =>
                    array(
                        'title' => 'Urlinfo',
                        'name' => 'urlinfo',
                        'type' => 'string',
                        'required' => false,
                        'validators' => NULL,
                        'filters' => NULL,
                    ),
                'urlproduktinfo' =>
                    array(
                        'title' => 'Urlproduktinfo',
                        'name' => 'urlproduktinfo',
                        'type' => 'string',
                        'required' => false,
                        'validators' => NULL,
                        'filters' => NULL,
                    ),
                'urlagb' =>
                    array(
                        'title' => 'Urlagb',
                        'name' => 'urlagb',
                        'type' => 'string',
                        'required' => false,
                        'validators' => NULL,
                        'filters' => NULL,
                    ),
                'pax_min' =>
                    array(
                        'title' => 'Pax_min',
                        'name' => 'pax_min',
                        'type' => 'integer',
                        'required' => false,
                        'validators' =>
                            array(
                                0 =>
                                    array(
                                        'name' => 'maxlength',
                                        'params' => 11,
                                    ),
                            ),
                        'filters' => NULL,
                    ),
                'pax_max' =>
                    array(
                        'title' => 'Pax_max',
                        'name' => 'pax_max',
                        'type' => 'integer',
                        'required' => false,
                        'validators' =>
                            array(
                                0 =>
                                    array(
                                        'name' => 'maxlength',
                                        'params' => 11,
                                    ),
                            ),
                        'filters' => NULL,
                    ),
                'price_tables' => array(
                    'title' => 'Price tables',
                    'name' => 'price_tables',
                    'type' => 'relation',
                    'relation' => array(
                        'type' => 'hasMany',
                        'related_id' => 'id_insurance',
                        'class' => '\\Pressmind\\ORM\\Object\\Touristic\\Insurance\\PriceTable'
                    ),
                    'required' => false,
                    'validators' => null,
                    'filters' => null
                )
            ),
    );
}
