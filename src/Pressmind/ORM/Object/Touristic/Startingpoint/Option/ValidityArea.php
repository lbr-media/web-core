<?php

namespace Pressmind\ORM\Object\Touristic\Startingpoint\Option;

use Pressmind\ORM\Object\AbstractObject;

/**
 * Class TouristicStartingpointOptionsValidityArea
 * @property integer $id_startingpoint_option
 * @property string $zip
 * @property integer $id_startingpoint
 */
class ValidityArea extends AbstractObject
{
    protected $_definitions = array(
        'class' =>
            array(
                'name' => 'TouristicStartingpointOptionsValidityArea',
            ),
        'database' =>
            array(
                'table_name' => 'pmt2core_touristic_startingpoint_options_validity_areas',
                'primary_key' => NULL,
            ),
        'properties' =>
            array(
                'id_startingpoint_option' =>
                    array(
                        'title' => 'Id_startingpoint_option',
                        'name' => 'id_startingpoint_option',
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
                'zip' =>
                    array(
                        'title' => 'Zip',
                        'name' => 'zip',
                        'type' => 'string',
                        'required' => true,
                        'validators' =>
                            array(
                                0 =>
                                    array(
                                        'name' => 'maxlength',
                                        'params' => 5,
                                    ),
                            ),
                        'filters' => NULL,
                    ),
                'id_startingpoint' =>
                    array(
                        'title' => 'Id_startingpoint',
                        'name' => 'id_startingpoint',
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
            ),
    );
}
